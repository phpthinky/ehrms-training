<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TrainingTopic;
use App\Models\TrainingSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingController extends Controller
{
    /**
     * Display a listing of trainings
     */
    public function index()
    {
        $trainings = Training::with(['topic', 'creator'])
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('trainings.index', compact('trainings'));
    }

    /**
     * Show the form for creating a new training
     */
    public function create()
    {
        $topics = TrainingTopic::where('is_active', true)->get();
        return view('trainings.create', compact('topics'));
    }

    /**
     * Store a newly created training
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:internal,external',
            'training_topic_id' => 'nullable|exists:hr_training_topics,id',
            'venue' => 'nullable|string|max:255',
            'facilitator' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'rank_level' => 'required|in:all,higher,normal',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'scheduled';
        
        if ($validated['type'] === 'external') {
            $validated['requested_by'] = auth()->id();
        }

        $training = Training::create($validated);

        // Send notifications to eligible employees
        try {
            $notifiedCount = NotificationController::notifyTrainingCreated($training);
            $message = 'Training created successfully.';
            if ($notifiedCount > 0) {
                $message .= ' ' . $notifiedCount . ' employees notified.';
            }
        } catch (\Exception $e) {
            $message = 'Training created successfully. (Notifications could not be sent)';
        }

        return redirect()->route('trainings.index')
            ->with('success', $message);
    }

    /**
     * Display the specified training
     */
    public function show(Training $training)
    {
        $training->load(['topic', 'creator', 'attendances.employee']);
        return view('trainings.show', compact('training'));
    }

    /**
     * Show the form for editing the specified training
     */
    public function edit(Training $training)
    {
        $topics = TrainingTopic::where('is_active', true)->get();
        return view('trainings.edit', compact('training', 'topics'));
    }

    /**
     * Update the specified training
     */
    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'facilitator' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $training->update($validated);

        return redirect()->route('trainings.index')
            ->with('success', 'Training updated successfully.');
    }

    /**
     * Remove the specified training
     */
    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('trainings.index')
            ->with('success', 'Training deleted successfully.');
    }

    /**
     * Show my trainings (for employees)
     */
    public function myTrainings()
    {
        $employee = auth()->user()->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'Employee profile not found.');
        }

        $trainings = $employee->trainings()
            ->with('training')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('trainings.my-trainings', compact('trainings'));
    }

    /**
     * Update training status (start/complete)
     */
    public function updateStatus(Request $request, Training $training)
    {
        $action = $request->input('action');
        
        // Validate status transition
        if ($action === 'start' && $training->status !== 'upcoming') {
            return redirect()->back()
                ->with('error', 'Can only start upcoming trainings.');
        }
        
        if ($action === 'complete' && $training->status !== 'ongoing') {
            return redirect()->back()
                ->with('error', 'Can only complete ongoing trainings.');
        }

        // Check if training date has arrived (for start action)
        if ($action === 'start') {
            $today = \Carbon\Carbon::today();
            $startDate = \Carbon\Carbon::parse($training->start_date);
            
            if ($startDate->greaterThan($today)) {
                $daysUntil = $today->diffInDays($startDate, false);
                return redirect()->back()
                    ->with('warning', 'Training is scheduled to start on ' . $startDate->format('M d, Y') . ' (' . abs($daysUntil) . ' days from now). Please wait until the training date arrives.');
            }
            
            $training->status = 'ongoing';
            $successMessage = 'Training started successfully!';
        } elseif ($action === 'complete') {
            $training->status = 'completed';
            $successMessage = 'Training completed successfully!';
        } else {
            return redirect()->back()->with('error', 'Invalid action.');
        }
        
        $training->save();
        
        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Show training recommendations based on TNA (Training Needs Analysis)
     */
    public function recommendations(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // Aggregate selected topics from training_surveys
        $topicCounts = [];

        $surveys = TrainingSurvey::where('year', $year)
            ->where('status', 'submitted')
            ->get();

        foreach ($surveys as $survey) {
            $selectedTopics = json_decode($survey->selected_topics, true);

            if (is_array($selectedTopics)) {
                foreach ($selectedTopics as $topicId) {
                    if (!isset($topicCounts[$topicId])) {
                        $topicCounts[$topicId] = 0;
                    }
                    $topicCounts[$topicId]++;
                }
            }
        }

        // Sort by most requested
        arsort($topicCounts);

        // Get topic details with request counts
        $recommendations = [];
        foreach ($topicCounts as $topicId => $count) {
            $topic = TrainingTopic::find($topicId);
            if ($topic) {
                $totalResponses = $surveys->count();
                $percentage = $totalResponses > 0 ? round(($count / $totalResponses) * 100, 1) : 0;

                // Check if there's already a training scheduled for this topic this year
                $existingTraining = Training::where('training_topic_id', $topicId)
                    ->whereYear('start_date', $year)
                    ->exists();

                $recommendations[] = [
                    'topic' => $topic,
                    'request_count' => $count,
                    'percentage' => $percentage,
                    'total_responses' => $totalResponses,
                    'has_scheduled_training' => $existingTraining,
                    'priority' => $this->calculatePriority($percentage, $existingTraining)
                ];
            }
        }

        // Get available years for filter
        $availableYears = TrainingSurvey::selectRaw('DISTINCT year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('trainings.recommendations', compact('recommendations', 'year', 'availableYears'));
    }

    /**
     * Calculate priority level for training recommendation
     */
    private function calculatePriority($percentage, $hasScheduledTraining)
    {
        if ($hasScheduledTraining) {
            return 'scheduled';
        }

        if ($percentage >= 70) {
            return 'critical';
        } elseif ($percentage >= 50) {
            return 'high';
        } elseif ($percentage >= 30) {
            return 'medium';
        } else {
            return 'low';
        }
    }
}
