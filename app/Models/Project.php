<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'status', 'progress', 'start_date', 'end_date', 'tech_stack', 'github_url', 'demo_url', 'image'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function getTechStackArrayAttribute(): array
    {
        return $this->tech_stack ? array_map('trim', explode(',', $this->tech_stack)) : [];
    }

    public function recalculateProgress(): void
    {
        $total = $this->tasks()->count();
        if ($total === 0) return;
        $completed = $this->tasks()->where('completed', true)->count();
        $this->progress = (int) round(($completed / $total) * 100);
        $this->save();
    }
}
