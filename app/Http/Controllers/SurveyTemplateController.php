<?php

namespace App\Http\Controllers;

use App\Models\SurveyTemplate;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;

class SurveyTemplateController extends Controller
{
    /**
     * Display listing of survey templates
     */
    public function index()
    {
        $templates = SurveyTemplate::with('creator')
            ->withCount('questions', 'responses')
            ->orderBy('year', 'desc')
            ->paginate(15);

        return view('survey-templates.index', compact('templates'));
    }

    /**
     * Show form to create new template
     */
    public function create()
    {
        // Get next year if current year already has a template
        $currentYear = date('Y');
        $existingYears = SurveyTemplate::pluck('year')->toArray();

        $suggestedYear = $currentYear;
        while (in_array($suggestedYear, $existingYears)) {
            $suggestedYear++;
        }

        return view('survey-templates.create', compact('suggestedYear'));
    }

    /**
     * Store new template
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|unique:hr_survey_templates,year',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // If setting as active, deactivate all others
        if ($request->is_active) {
            SurveyTemplate::where('is_active', true)->update(['is_active' => false]);
        }

        $template = SurveyTemplate::create([
            'year' => $validated['year'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->is_active ?? false,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('survey-templates.show', $template)
            ->with('success', 'Survey template created successfully!');
    }

    /**
     * Show template details
     */
    public function show(SurveyTemplate $surveyTemplate)
    {
        $surveyTemplate->load([
            'creator',
            'questions' => function ($query) {
                $query->orderByPivot('order');
            },
            'responses.employee.department'
        ]);

        $stats = [
            'total_questions' => $surveyTemplate->questions->count(),
            'total_responses' => $surveyTemplate->responses()->where('status', 'submitted')->count(),
            'total_employees' => \App\Models\Employee::where('status', 'active')->count(),
            'response_rate' => 0,
        ];

        if ($stats['total_employees'] > 0) {
            $stats['response_rate'] = round(($stats['total_responses'] / $stats['total_employees']) * 100, 1);
        }

        return view('survey-templates.show', compact('surveyTemplate', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit(SurveyTemplate $surveyTemplate)
    {
        return view('survey-templates.edit', compact('surveyTemplate'));
    }

    /**
     * Update template
     */
    public function update(Request $request, SurveyTemplate $surveyTemplate)
    {
        $validated = $request->validate([
            'year' => 'required|integer|unique:hr_survey_templates,year,' . $surveyTemplate->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        // If setting as active, deactivate all others
        if ($request->is_active) {
            SurveyTemplate::where('id', '!=', $surveyTemplate->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $surveyTemplate->update([
            'year' => $validated['year'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()
            ->route('survey-templates.show', $surveyTemplate)
            ->with('success', 'Survey template updated successfully!');
    }

    /**
     * Delete template
     */
    public function destroy(SurveyTemplate $surveyTemplate)
    {
        // Check if has responses
        if ($surveyTemplate->responses()->exists()) {
            return back()->with('error', 'Cannot delete template with existing responses.');
        }

        $surveyTemplate->delete();

        return redirect()
            ->route('survey-templates.index')
            ->with('success', 'Survey template deleted successfully!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(SurveyTemplate $surveyTemplate)
    {
        if (!$surveyTemplate->is_active) {
            // Deactivate all others
            SurveyTemplate::where('is_active', true)->update(['is_active' => false]);
            $surveyTemplate->update(['is_active' => true]);
            $message = 'Survey template activated successfully!';
        } else {
            $surveyTemplate->update(['is_active' => false]);
            $message = 'Survey template deactivated successfully!';
        }

        return back()->with('success', $message);
    }

    /**
     * Duplicate template to new year
     */
    public function duplicate(Request $request, SurveyTemplate $surveyTemplate)
    {
        $validated = $request->validate([
            'year' => 'required|integer|unique:hr_survey_templates,year',
        ]);

        $newTemplate = SurveyTemplate::create([
            'year' => $validated['year'],
            'title' => $surveyTemplate->title,
            'description' => $surveyTemplate->description,
            'is_active' => false,
            'created_by' => auth()->id(),
        ]);

        // Copy all questions with same settings
        foreach ($surveyTemplate->questions as $question) {
            $newTemplate->questions()->attach($question->id, [
                'is_required' => $question->pivot->is_required,
                'order' => $question->pivot->order,
                'custom_options' => $question->pivot->custom_options,
            ]);
        }

        return redirect()
            ->route('survey-templates.show', $newTemplate)
            ->with('success', "Template duplicated to year {$validated['year']}!");
    }
}
