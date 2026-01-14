<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSurvey extends Model
{
    use HasFactory;

    protected $table = 'hr_training_surveys';

    protected $fillable = [
        'employee_id',
        'year',
        'selected_topics',
        'other_topics',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'selected_topics' => 'array',
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }
}
