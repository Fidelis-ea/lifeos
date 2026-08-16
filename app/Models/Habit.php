<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Habit extends Model
{
    protected $fillable = [
        'user_id', 'name', 'description', 'icon', 'color',
        'frequency', 'target', 'current_streak', 'longest_streak',
        'is_active', 'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function isCompletedToday(): bool
    {
        return $this->logs()
            ->where('date', today())
            ->where('completed', true)
            ->exists();
    }

    public function getLogsForMonth(int $year, int $month): array
    {
        $logs = $this->logs()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('completed', true)
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->day)
            ->toArray();
        return $logs;
    }

    public function recalculateStreak(): void
    {
        $today = today();
        $streak = 0;
        $current = $today->copy();

        while (true) {
            $completed = $this->logs()
                ->where('date', $current->toDateString())
                ->where('completed', true)
                ->exists();

            if (!$completed) break;
            $streak++;
            $current->subDay();
        }

        $this->current_streak = $streak;
        if ($streak > $this->longest_streak) {
            $this->longest_streak = $streak;
        }
        $this->save();
    }
}
