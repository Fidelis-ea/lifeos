<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineEntry extends Model
{
    protected $fillable = [
        'user_id', 'date', 'category', 'title',
        'description', 'duration_minutes', 'icon',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static array $categories = [
        'coding'   => ['label' => 'Coding',    'icon' => '💻', 'color' => '#FFD43B'],
        'learning' => ['label' => 'Learning',   'icon' => '📚', 'color' => '#9BE564'],
        'japanese' => ['label' => 'Japanese',   'icon' => '🇯🇵', 'color' => '#FF7EB6'],
        'exercise' => ['label' => 'Exercise',   'icon' => '🏋️', 'color' => '#5BC0EB'],
        'gaming'   => ['label' => 'Gaming',     'icon' => '🎮', 'color' => '#FF6B6B'],
        'work'     => ['label' => 'Work',       'icon' => '💼', 'color' => '#9BE564'],
        'personal' => ['label' => 'Personal',   'icon' => '🌟', 'color' => '#FF7EB6'],
        'goal'     => ['label' => 'Goal',       'icon' => '🎯', 'color' => '#5BC0EB'],
        'other'    => ['label' => 'Other',      'icon' => '📌', 'color' => '#ccc'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryInfoAttribute(): array
    {
        return self::$categories[$this->category] ?? self::$categories['other'];
    }

    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration_minutes) return '';
        $h = intdiv($this->duration_minutes, 60);
        $m = $this->duration_minutes % 60;
        return ($h > 0 ? $h . 'h ' : '') . ($m > 0 ? $m . 'm' : '');
    }
}
