<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalTrainingRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'external_training_requests';

    protected $fillable = [
        'employee_id',
        'training_title',
        'training_description',
        'training_provider',
        'training_venue',
        'start_date',
        'end_date',
        'estimated_cost',
        'purpose',
        'request_form_path',
        'request_form_name',
        'department_head_letter_path',
        'department_head_letter_name',
        'requesting_party_document_path',
        'requesting_party_document_name',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'hr_remarks',
        'training_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
            default => ucfirst($this->status),
        };
    }

    public function getFormattedCostAttribute(): string
    {
        if (!$this->estimated_cost) {
            return 'Not specified';
        }
        return '₱' . number_format($this->estimated_cost, 2);
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // Helper methods
    public function hasAllDocuments(): bool
    {
        return $this->request_form_path
            && $this->department_head_letter_path
            && $this->requesting_party_document_path;
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canBeSubmitted(): bool
    {
        return $this->status === 'draft' && $this->hasAllDocuments();
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'pending';
    }
}
