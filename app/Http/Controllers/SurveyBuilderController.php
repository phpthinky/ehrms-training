<?php

namespace App\Http\Controllers;

use App\Models\SurveyTemplate;
use App\Models\SurveyQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyBuilderController extends Controller
{
    /**
     * Show form builder for a template
     */
    public function index(SurveyTemplate $surveyTemplate)
    {
        $surveyTemplate->load(['questions' => function ($query) {
            $query->orderByPivot('order');
        }]);

        $availableQuestions = SurveyQuestion::whereNotIn('id', $surveyTemplate->questions->pluck('id'))
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('survey-builder.index', compact('surveyTemplate', 'availableQuestions'));
    }

    /**
     * Add question to template
     */
    public function addQuestion(Request $request, SurveyTemplate $surveyTemplate)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:survey_questions,id',
            'is_required' => 'boolean',
        ]);

        // Get max order
        $maxOrder = $surveyTemplate->questions()->max('order') ?? 0;

        // Attach question
        $surveyTemplate->questions()->attach($validated['question_id'], [
            'is_required' => $request->is_required ?? false,
            'order' => $maxOrder + 1,
            'custom_options' => null,
        ]);

        return back()->with('success', 'Question added to survey!');
    }

    /**
     * Remove question from template
     */
    public function removeQuestion(SurveyTemplate $surveyTemplate, SurveyQuestion $surveyQuestion)
    {
        $surveyTemplate->questions()->detach($surveyQuestion->id);

        return back()->with('success', 'Question removed from survey!');
    }

    /**
     * Update question settings in template
     */
    public function updateQuestion(Request $request, SurveyTemplate $surveyTemplate, SurveyQuestion $surveyQuestion)
    {
        $validated = $request->validate([
            'is_required' => 'boolean',
            'custom_options' => 'nullable|array',
        ]);

        $surveyTemplate->questions()->updateExistingPivot($surveyQuestion->id, [
            'is_required' => $request->is_required ?? false,
            'custom_options' => $validated['custom_options'] ?? null,
        ]);

        return back()->with('success', 'Question settings updated!');
    }

    /**
     * Reorder questions (AJAX)
     */
    public function reorderQuestions(Request $request, SurveyTemplate $surveyTemplate)
    {
        $validated = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:survey_questions,id',
            'questions.*.order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($surveyTemplate, $validated) {
            foreach ($validated['questions'] as $questionData) {
                $surveyTemplate->questions()->updateExistingPivot($questionData['id'], [
                    'order' => $questionData['order'],
                ]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Question order updated!']);
    }

    /**
     * Get question details (AJAX)
     */
    public function getQuestion(SurveyQuestion $surveyQuestion)
    {
        return response()->json([
            'success' => true,
            'question' => $surveyQuestion,
        ]);
    }

    /**
     * Bulk add default questions
     */
    public function addDefaultQuestions(SurveyTemplate $surveyTemplate)
    {
        $defaultQuestions = SurveyQuestion::where('is_default', true)
            ->whereNotIn('id', $surveyTemplate->questions->pluck('id'))
            ->get();

        if ($defaultQuestions->isEmpty()) {
            return back()->with('info', 'No default questions available to add.');
        }

        $maxOrder = $surveyTemplate->questions()->max('order') ?? 0;

        foreach ($defaultQuestions as $index => $question) {
            $surveyTemplate->questions()->attach($question->id, [
                'is_required' => false,
                'order' => $maxOrder + $index + 1,
                'custom_options' => null,
            ]);
        }

        return back()->with('success', "Added {$defaultQuestions->count()} default questions to survey!");
    }
}
