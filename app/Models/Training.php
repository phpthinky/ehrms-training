<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_trainings';

    protected $fillable = [
        'training_topic_id',
        'created_by',
        'title',
        'description',
        'type',
        'rank_level',
        'venue',
        'facilitator',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'duration_hours',
        'requested_by',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'date',
    ];

    // Relationships
    public function topic(): BelongsTo
    {
        return $this->belongsTo(TrainingTopic::class, 'training_topic_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    // Scopes
    public function scopeInternal($query)
    {
        return $query->where('type', 'internal');
    }

    public function scopeExternal($query)
    {
        return $query->where('type', 'external');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('start_date', '>=', now());
    }
}
