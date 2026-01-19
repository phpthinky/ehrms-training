<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'survey_responses';

    protected $fillable = [
        'survey_template_id',
        'employee_id',
        'response_data',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'response_data' => 'array',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the survey template
     */
    public function template()
    {
        return $this->belongsTo(SurveyTemplate::class, 'survey_template_id');
    }

    /**
     * Get the employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Scope for submitted responses
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope for draft responses
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
