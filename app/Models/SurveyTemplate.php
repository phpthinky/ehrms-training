<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyTemplate extends Model
{
    use HasFactory;

    protected $table = 'survey_templates';

    protected $fillable = [
        'year',
        'title',
        'description',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the creator of the template
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the questions for this template
     */
    public function questions()
    {
        return $this->belongsToMany(SurveyQuestion::class, 'survey_template_questions')
            ->withPivot('is_required', 'order', 'custom_options')
            ->orderByPivot('order');
    }

    /**
     * Get all responses for this template
     */
    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    /**
     * Get the active template
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
