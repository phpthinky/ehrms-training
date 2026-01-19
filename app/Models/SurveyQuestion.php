<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $table = 'survey_questions';

    protected $fillable = [
        'question_text',
        'question_type',
        'options',
        'help_text',
        'is_default',
    ];

    protected $casts = [
        'options' => 'array',
        'is_default' => 'boolean',
    ];

    /**
     * Get templates using this question
     */
    public function templates()
    {
        return $this->belongsToMany(SurveyTemplate::class, 'survey_template_questions')
            ->withPivot('is_required', 'order', 'custom_options');
    }

    /**
     * Scope for default questions (from PDF template)
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
