<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    /**
     * Display question bank
     */
    public function index()
    {
        $questions = SurveyQuestion::withCount('templates')
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $questionTypes = [
            'training_programs' => 'Training Programs (from database)',
            'checkbox' => 'Multiple Choice (Checkboxes)',
            'radio' => 'Single Choice (Radio)',
            'text' => 'Short Text',
            'textarea' => 'Long Text',
            'number' => 'Number',
            'scale' => 'Rating Scale (1-5)',
        ];

        return view('survey-questions.index', compact('questions', 'questionTypes'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $questionTypes = [
            'training_programs' => 'Training Programs (from database)',
            'checkbox' => 'Multiple Choice (Checkboxes)',
            'radio' => 'Single Choice (Radio)',
            'text' => 'Short Text',
            'textarea' => 'Long Text',
            'number' => 'Number',
            'scale' => 'Rating Scale (1-5)',
        ];

        return view('survey-questions.create', compact('questionTypes'));
    }

    /**
     * Store new question
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:training_programs,checkbox,radio,text,textarea,number,scale',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'help_text' => 'nullable|string|max:500',
            'is_default' => 'boolean',
        ]);

        // Clean up options for specific types
        $options = null;
        if (in_array($validated['question_type'], ['checkbox', 'radio'])) {
            if (!empty($validated['options'])) {
                $options = array_values(array_filter($validated['options']));
            }
        }

        $question = SurveyQuestion::create([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'options' => $options,
            'help_text' => $validated['help_text'] ?? null,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()
            ->route('survey-questions.index')
            ->with('success', 'Question added to bank successfully!');
    }

    /**
     * Show question details
     */
    public function show(SurveyQuestion $surveyQuestion)
    {
        $surveyQuestion->load('templates');

        return view('survey-questions.show', compact('surveyQuestion'));
    }

    /**
     * Show edit form
     */
    public function edit(SurveyQuestion $surveyQuestion)
    {
        $questionTypes = [
            'training_programs' => 'Training Programs (from database)',
            'checkbox' => 'Multiple Choice (Checkboxes)',
            'radio' => 'Single Choice (Radio)',
            'text' => 'Short Text',
            'textarea' => 'Long Text',
            'number' => 'Number',
            'scale' => 'Rating Scale (1-5)',
        ];

        return view('survey-questions.edit', compact('surveyQuestion', 'questionTypes'));
    }

    /**
     * Update question
     */
    public function update(Request $request, SurveyQuestion $surveyQuestion)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:training_programs,checkbox,radio,text,textarea,number,scale',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'help_text' => 'nullable|string|max:500',
            'is_default' => 'boolean',
        ]);

        // Clean up options
        $options = null;
        if (in_array($validated['question_type'], ['checkbox', 'radio'])) {
            if (!empty($validated['options'])) {
                $options = array_values(array_filter($validated['options']));
            }
        }

        $surveyQuestion->update([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'options' => $options,
            'help_text' => $validated['help_text'] ?? null,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()
            ->route('survey-questions.index')
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Delete question
     */
    public function destroy(SurveyQuestion $surveyQuestion)
    {
        // Check if question is used in any templates
        if ($surveyQuestion->templates()->exists()) {
            return back()->with('error', 'Cannot delete question that is used in survey templates.');
        }

        $surveyQuestion->delete();

        return redirect()
            ->route('survey-questions.index')
            ->with('success', 'Question deleted successfully!');
    }

    /**
     * Get question preview (AJAX)
     */
    public function preview(SurveyQuestion $surveyQuestion)
    {
        $html = '';

        switch ($surveyQuestion->question_type) {
            case 'training_programs':
                $programs = TrainingProgram::where('is_active', true)->orderBy('order')->get();
                $html = view('survey-questions.preview.training-programs', compact('surveyQuestion', 'programs'))->render();
                break;

            case 'checkbox':
            case 'radio':
                $html = view('survey-questions.preview.choices', compact('surveyQuestion'))->render();
                break;

            case 'text':
                $html = view('survey-questions.preview.text', compact('surveyQuestion'))->render();
                break;

            case 'textarea':
                $html = view('survey-questions.preview.textarea', compact('surveyQuestion'))->render();
                break;

            case 'number':
                $html = view('survey-questions.preview.number', compact('surveyQuestion'))->render();
                break;

            case 'scale':
                $html = view('survey-questions.preview.scale', compact('surveyQuestion'))->render();
                break;
        }

        return response()->json(['html' => $html]);
    }
}
