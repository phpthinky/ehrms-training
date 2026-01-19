<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingTopic extends Model
{
    use HasFactory;

    protected $table = 'training_topics';

    protected $fillable = [
        'title',
        'description',
        'category',
        'rank_level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
