<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'employee_id',
        'contact_number',
        'department',
        'position',
        'employment_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(EmployeeFile::class, 'uploaded_by');
    }

    // Role Check Methods
    public function isHRAdmin(): bool
    {
        return $this->role === 'hr_admin';
    }

    public function isAdminAssistant(): bool
    {
        return $this->role === 'admin_assistant';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isGuest(): bool
    {
        return $this->role === 'guest';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['hr_admin', 'admin_assistant']);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Scope queries
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHRStaff($query)
    {
        return $query->whereIn('role', ['hr_admin', 'admin_assistant']);
    }

    public function scopeEmployees($query)
    {
        return $query->where('role', 'employee');
    }
}
