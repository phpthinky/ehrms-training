<?php

namespace App\Http\Controllers;

use App\Models\TrainingSurvey;
use App\Models\TrainingTopic;
use App\Models\Employee;
use Illuminate\Http\Request;

class TrainingSurveyController extends Controller
{
    /**
     * Display survey form for employees
     */
    public function form()
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found.');
        }

        $currentYear = date('Y');
        
        // Check if already submitted this year
        $existingSurvey = TrainingSurvey::where('employee_id', $employee->id)
            ->where('year', $currentYear)
            ->first();

        // Get all available topics
        $topics = TrainingTopic::where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('training-survey.form', compact('employee', 'existingSurvey', 'topics', 'currentYear'));
    }

    /**
     * Submit survey
     */
    public function submit(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        $currentYear = date('Y');

        // Check if already submitted
        $existing = TrainingSurvey::where('employee_id', $employee->id)
            ->where('year', $currentYear)
            ->first();

        if ($existing && $existing->status === 'submitted') {
            return back()->with('error', 'You have already submitted your survey for ' . $currentYear);
        }

        $validated = $request->validate([
            'topics' => 'required|array|min:1|max:5',
            'topics.*' => 'exists:training_topics,id',
            'other_topics' => 'nullable|string|max:1000',
        ]);

        // Create or update survey
        $survey = TrainingSurvey::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'year' => $currentYear,
            ],
            [
                'selected_topics' => $validated['topics'],
                'other_topics' => $validated['other_topics'] ?? null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('training-survey.form')
            ->with('success', 'Training survey submitted successfully! Thank you for your input.');
    }

    /**
     * HR: View all survey results
     */
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $surveys = TrainingSurvey::with(['employee.department'])
            ->where('year', $year)
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Get summary statistics
        $stats = $this->getSurveyStats($year);

        return view('training-survey.index', compact('surveys', 'year', 'stats'));
    }

    /**
     * HR: View individual survey
     */
    public function show($id)
    {
        $survey = TrainingSurvey::with(['employee.department'])
            ->findOrFail($id);

        $selectedTopicIds = $survey->selected_topics ?? [];
        $topics = TrainingTopic::whereIn('id', $selectedTopicIds)->get();

        return view('training-survey.show', compact('survey', 'topics'));
    }

    /**
     * Get survey statistics
     */
    protected function getSurveyStats($year)
    {
        $surveys = TrainingSurvey::with('employee.department')
            ->where('year', $year)
            ->where('status', 'submitted')
            ->get();

        $totalEmployees = Employee::where('status', 'active')->count();
        $submittedCount = $surveys->count();

        // Topic popularity
        $topicCounts = [];
        foreach ($surveys as $survey) {
            $topics = $survey->selected_topics ?? [];
            foreach ($topics as $topicId) {
                if (!isset($topicCounts[$topicId])) {
                    $topicCounts[$topicId] = 0;
                }
                $topicCounts[$topicId]++;
            }
        }

        // Get topic details
        $topicStats = [];
        foreach ($topicCounts as $topicId => $count) {
            $topic = TrainingTopic::find($topicId);
            if ($topic) {
                $topicStats[] = [
                    'topic' => $topic,
                    'count' => $count,
                    'percentage' => $submittedCount > 0 ? round(($count / $submittedCount) * 100, 1) : 0,
                ];
            }
        }

        usort($topicStats, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        $departmentStats = $surveys->groupBy('employee.department.name')
            ->map(function ($group) {
                return $group->count();
            });

        return [
            'total_employees' => $totalEmployees,
            'submitted_count' => $submittedCount,
            'pending_count' => $totalEmployees - $submittedCount,
            'response_rate' => $totalEmployees > 0 ? round(($submittedCount / $totalEmployees) * 100, 1) : 0,
            'topic_stats' => $topicStats,
            'department_stats' => $departmentStats,
        ];
    }

    /**
     * HR view of all survey submissions
     */
    public function hrIndex()
    {
        $currentYear = date('Y');

        // Get survey statistics
        $stats = $this->getSurveyStats($currentYear);

        // Get all submitted surveys with employee
        $surveys = TrainingSurvey::with(['employee.department'])
            ->where('year', $currentYear)
            ->where('status', 'submitted')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get all topics with request counts
        $topicCounts = [];
        foreach ($surveys as $survey) {
            $topicIds = $survey->selected_topics ?? [];
            foreach ($topicIds as $topicId) {
                if (!isset($topicCounts[$topicId])) {
                    $topic = TrainingTopic::find($topicId);
                    if ($topic) {
                        $topicCounts[$topicId] = [
                            'topic' => $topic,
                            'count' => 0,
                        ];
                    }
                }
                if (isset($topicCounts[$topicId])) {
                    $topicCounts[$topicId]['count']++;
                }
            }
        }

        // Sort by count descending
        usort($topicCounts, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        return view('training-survey.hr-index', compact('surveys', 'stats', 'topicCounts', 'currentYear'));
    }
}

