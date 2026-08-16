<?php

namespace App\Http\Controllers;

use App\Models\DailyEntry;
use App\Models\Habit;
use App\Models\Goal;
use App\Models\TimelineEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = today();

        // Today's check-in
        $todayEntry = DailyEntry::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Habits with today's status
        $habits = Habit::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($habit) use ($today) {
                $habit->done_today = $habit->isCompletedToday();
                return $habit;
            });

        $habitsCompletedToday = $habits->where('done_today', true)->count();
        $habitsTotal = $habits->count();

        // Goals in progress
        $activeGoals = Goal::where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->orderBy('priority', 'desc')
            ->take(3)
            ->get();

        // Recent timeline
        $recentTimeline = TimelineEntry::where('user_id', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Weekly mood average (last 7 days)
        $weeklyEntries = DailyEntry::where('user_id', $user->id)
            ->whereBetween('date', [$today->copy()->subDays(6), $today])
            ->get();

        $weeklyStats = [
            'avg_mood'        => $weeklyEntries->avg('mood') ?? 0,
            'avg_energy'      => $weeklyEntries->avg('energy') ?? 0,
            'avg_sleep'       => $weeklyEntries->avg('sleep_hours') ?? 0,
            'avg_productivity'=> $weeklyEntries->avg('productivity') ?? 0,
            'total_coding'    => $weeklyEntries->sum('coding_minutes'),
            'total_learning'  => $weeklyEntries->sum('learning_minutes'),
            'total_exercise'  => $weeklyEntries->sum('exercise_minutes'),
            'days_logged'     => $weeklyEntries->count(),
        ];

        return view('dashboard', compact(
            'todayEntry', 'habits', 'habitsCompletedToday', 'habitsTotal',
            'activeGoals', 'recentTimeline', 'weeklyStats', 'today'
        ));
    }
}
