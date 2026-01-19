<?php

namespace App\Http\Controllers;

use App\Models\SurveyTemplate;
use App\Models\SurveyResponse;
use App\Models\Employee;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyResponseController extends Controller
{
    /**
     * Show active survey form for employees
     */
    public function showForm()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee record found.');
        }

        // Get active survey template
        $template = SurveyTemplate::where('is_active', true)->first();

        if (!$template) {
            return view('survey-responses.no-active-survey');
        }

        // Check if already submitted
        $existingResponse = SurveyResponse::where('survey_template_id', $template->id)
            ->where('employee_id', $employee->id)
            ->first();

        if ($existingResponse && $existingResponse->status === 'submitted') {
            return view('survey-responses.already-submitted', compact('template', 'existingResponse'));
        }

        // Load questions with order
        $template->load(['questions' => function ($query) {
            $query->orderByPivot('order');
        }]);

        // Get training programs for training_programs question type
        $trainingPrograms = TrainingProgram::where('is_active', true)->orderBy('order')->get();

        return view('survey-responses.form', compact('template', 'employee', 'existingResponse', 'trainingPrograms'));
    }

    /**
     * Submit survey response
     */
    public function submit(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        $template = SurveyTemplate::where('is_active', true)->first();

        if (!$template) {
            return back()->with('error', 'No active survey available.');
        }

        // Check if already submitted
        $existing = SurveyResponse::where('survey_template_id', $template->id)
            ->where('employee_id', $employee->id)
            ->where('status', 'submitted')
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted this survey.');
        }

        // Validate based on template questions
        $rules = [];
        foreach ($template->questions as $question) {
            $fieldName = 'question_' . $question->id;

            if ($question->pivot->is_required) {
                $rules[$fieldName] = 'required';
            } else {
                $rules[$fieldName] = 'nullable';
            }

            // Additional validation based on question type
            if ($question->question_type === 'training_programs' || $question->question_type === 'checkbox') {
                $rules[$fieldName] = ($question->pivot->is_required ? 'required' : 'nullable') . '|array';
            } elseif ($question->question_type === 'number') {
                $rules[$fieldName] .= '|numeric';
            }
        }

        $validated = $request->validate($rules);

        // Prepare response data
        $responseData = [];
        foreach ($template->questions as $question) {
            $fieldName = 'question_' . $question->id;
            $responseData[$question->id] = $validated[$fieldName] ?? null;
        }

        // Create or update response
        $response = SurveyResponse::updateOrCreate(
            [
                'survey_template_id' => $template->id,
                'employee_id' => $employee->id,
            ],
            [
                'response_data' => $responseData,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('survey.form')
            ->with('success', 'Survey submitted successfully! Thank you for your input.');
    }

    /**
     * Save as draft
     */
    public function saveDraft(Request $request)
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'No employee record found.']);
        }

        $template = SurveyTemplate::where('is_active', true)->first();

        if (!$template) {
            return response()->json(['success' => false, 'message' => 'No active survey available.']);
        }

        // Save as draft (no validation required)
        $response = SurveyResponse::updateOrCreate(
            [
                'survey_template_id' => $template->id,
                'employee_id' => $employee->id,
            ],
            [
                'response_data' => $request->all(),
                'status' => 'draft',
            ]
        );

        return response()->json(['success' => true, 'message' => 'Draft saved!']);
    }

    /**
     * Analytics dashboard (HR only)
     */
    public function analytics(SurveyTemplate $surveyTemplate)
    {
        $surveyTemplate->load(['questions' => function ($query) {
            $query->orderByPivot('order');
        }]);

        // Get all responses
        $responses = SurveyResponse::where('survey_template_id', $surveyTemplate->id)
            ->where('status', 'submitted')
            ->with('employee.department')
            ->get();

        // Calculate statistics
        $totalEmployees = Employee::where('status', 'active')->count();
        $totalResponses = $responses->count();
        $responseRate = $totalEmployees > 0 ? round(($totalResponses / $totalEmployees) * 100, 1) : 0;

        // Department breakdown
        $byDepartment = $responses->groupBy('employee.department.name')->map(function ($items) {
            return $items->count();
        })->sortDesc();

        // Analyze each question
        $questionAnalysis = [];
        foreach ($surveyTemplate->questions as $question) {
            $analysis = [
                'question' => $question,
                'total_responses' => 0,
                'data' => [],
            ];

            foreach ($responses as $response) {
                $answer = $response->response_data[$question->id] ?? null;

                if ($answer !== null) {
                    $analysis['total_responses']++;

                    // Aggregate based on question type
                    if ($question->question_type === 'training_programs' || $question->question_type === 'checkbox') {
                        // Multiple choice - count each selection
                        if (is_array($answer)) {
                            foreach ($answer as $value) {
                                if (!isset($analysis['data'][$value])) {
                                    $analysis['data'][$value] = 0;
                                }
                                $analysis['data'][$value]++;
                            }
                        }
                    } elseif ($question->question_type === 'radio') {
                        // Single choice
                        if (!isset($analysis['data'][$answer])) {
                            $analysis['data'][$answer] = 0;
                        }
                        $analysis['data'][$answer]++;
                    } elseif ($question->question_type === 'scale' || $question->question_type === 'number') {
                        // Numeric - calculate average
                        $analysis['data'][] = $answer;
                    } else {
                        // Text responses
                        $analysis['data'][] = $answer;
                    }
                }
            }

            // Calculate averages for numeric questions
            if (in_array($question->question_type, ['scale', 'number']) && !empty($analysis['data'])) {
                $analysis['average'] = round(array_sum($analysis['data']) / count($analysis['data']), 2);
            }

            // Sort training programs by popularity
            if ($question->question_type === 'training_programs') {
                arsort($analysis['data']);
            }

            $questionAnalysis[] = $analysis;
        }

        // Get training programs for reference
        $trainingPrograms = TrainingProgram::where('is_active', true)->orderBy('order')->get()->keyBy('id');

        return view('survey-responses.analytics', compact(
            'surveyTemplate',
            'totalEmployees',
            'totalResponses',
            'responseRate',
            'byDepartment',
            'questionAnalysis',
            'trainingPrograms'
        ));
    }

    /**
     * Export responses to Excel
     */
    public function export(SurveyTemplate $surveyTemplate)
    {
        // TODO: Implement Excel export using Laravel Excel
        return back()->with('info', 'Excel export feature coming soon!');
    }

    /**
     * View individual response (HR only)
     */
    public function show(SurveyResponse $surveyResponse)
    {
        $surveyResponse->load([
            'employee.department',
            'template.questions' => function ($query) {
                $query->orderByPivot('order');
            }
        ]);

        $trainingPrograms = TrainingProgram::where('is_active', true)->get()->keyBy('id');

        return view('survey-responses.show', compact('surveyResponse', 'trainingPrograms'));
    }

    /**
     * List all responses for a template (HR only)
     */
    public function index(SurveyTemplate $surveyTemplate)
    {
        $responses = SurveyResponse::where('survey_template_id', $surveyTemplate->id)
            ->where('status', 'submitted')
            ->with('employee.department')
            ->orderBy('submitted_at', 'desc')
            ->paginate(30);

        return view('survey-responses.index', compact('surveyTemplate', 'responses'));
    }
}
