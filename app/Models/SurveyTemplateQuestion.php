<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyTemplateQuestion extends Model
{
    protected $table = 'survey_template_questions';

    protected $fillable = [
        'survey_template_id',
        'survey_question_id',
        'is_required',
        'order',
        'custom_options',
    ];

    protected $casts = [
        'custom_options' => 'array',
        'is_required' => 'boolean',
    ];

    /**
     * Get the template
     */
    public function template()
    {
        return $this->belongsTo(SurveyTemplate::class, 'survey_template_id');
    }

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
}
