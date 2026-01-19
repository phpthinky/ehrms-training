<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_files';

    protected $fillable = [
        'employee_id',
        'uploaded_by',
        'file_name',
        'file_path',
        'file_type',
        'file_category',
        'file_size',
        'description',
        'visibility',
        'status',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Helper methods
    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return 'Unknown';
        
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeViewableByEmployee($query)
    {
        return $query->whereIn('visibility', ['employee_view', 'public']);
    }
}
