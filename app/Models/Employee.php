<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_employees';

    protected $fillable = [
        'user_id',
        'department_id',
        'employee_number',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'gender',
        'civil_status',
        'address',
        'position',
        'rank_level',
        'employment_type',
        'date_hired',
        'status',
        'mobile_number',
        'email',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'date_hired' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(EmployeeFile::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(TrainingSurvey::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . substr($this->middle_name, 0, 1) . '.';
        }
        $name .= ' ' . $this->last_name;
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        return $name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePermanent($query)
    {
        return $query->where('employment_type', 'permanent');
    }

    public function scopeJobOrder($query)
    {
        return $query->where('employment_type', 'job_order');
    }

    public function scopeHigherRank($query)
    {
        return $query->where('rank_level', 'higher');
    }

    public function scopeNormalRank($query)
    {
        return $query->where('rank_level', 'normal');
    }
}
