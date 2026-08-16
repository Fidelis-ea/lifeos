<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'category',
        'start_date', 'target_date', 'progress', 'status',
        'priority', 'icon', 'color',
    ];

    protected $casts = [
        'start_date' => 'date',
        'target_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(GoalTask::class)->orderBy('order');
    }

    public function recalculateProgress(): void
    {
        $total = $this->tasks()->count();
        if ($total === 0) return;
        $completed = $this->tasks()->where('completed', true)->count();
        $this->progress = (int) round(($completed / $total) * 100);
        $this->save();
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'in_progress' => '#5BC0EB',
            'completed'   => '#9BE564',
            'archived'    => '#999',
            default       => '#ccc',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'high'   => '#FF6B6B',
            'medium' => '#FFD43B',
            'low'    => '#9BE564',
            default  => '#ccc',
        };
    }
}
