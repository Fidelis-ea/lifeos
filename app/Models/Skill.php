<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    protected $fillable = [
        'user_id', 'name', 'category', 'current_level', 'target_level', 'learning_hours', 'notes'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function progressHistory(): HasMany
    {
        return $this->hasMany(SkillProgress::class)->orderBy('logged_date');
    }

    public function learningLogs(): HasMany
    {
        return $this->hasMany(LearningLog::class);
    }
}
