<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyEntry extends Model
{
    protected $fillable = [
        'user_id', 'date', 'mood', 'energy', 'sleep_hours',
        'productivity', 'notes', 'coding_minutes', 'learning_minutes',
        'exercise_minutes', 'gaming_minutes', 'japanese_minutes',
    ];

    protected $casts = [
        'date' => 'date',
        'sleep_hours' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSleepFormattedAttribute(): string
    {
        $hours = floor($this->sleep_hours);
        $minutes = ($this->sleep_hours - $hours) * 60;
        return $hours . 'h' . ($minutes > 0 ? ' ' . (int)$minutes . 'm' : '');
    }

    public static function formatMinutes(int $minutes): string
    {
        if ($minutes === 0) return '0m';
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return ($h > 0 ? $h . 'h' : '') . ($m > 0 ? ' ' . $m . 'm' : '');
    }
}
