<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAttendance extends Model
{
    use HasFactory;

    protected $table = 'training_attendance';

    protected $fillable = [
        'training_id',
        'employee_id',
        'attendance_status',
        'certificate_uploaded',
        'certificate_file_id',
        'remarks',
        'attended_at',
    ];

    protected $casts = [
        'certificate_uploaded' => 'boolean',
        'attended_at' => 'datetime',
    ];

    // Relationships
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function certificateFile(): BelongsTo
    {
        return $this->belongsTo(EmployeeFile::class, 'certificate_file_id');
    }

    // Scopes
    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'attended');
    }

    public function scopeWithCertificate($query)
    {
        return $query->where('certificate_uploaded', true);
    }
}
