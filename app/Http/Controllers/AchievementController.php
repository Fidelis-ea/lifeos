<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Check/update achievements
        $this->checkAchievements($user);

        $unlocked = $user->achievements()->withPivot('unlocked_at')->get();
        $unlockedIds = $unlocked->pluck('id')->toArray();
        $allAchievements = Achievement::all();
        $locked = $allAchievements->whereNotIn('id', $unlockedIds);

        $unlockedCount = $unlocked->count();
        $totalCount = $allAchievements->count();
        $totalXp = $user->xp;
        $level = $user->level;

        return view('achievements.index', compact('unlocked', 'locked', 'unlockedCount', 'totalCount', 'totalXp', 'level'));
    }

    private function checkAchievements($user): void
    {
        // Pre-load all achievements and user's unlocked IDs in bulk (2 queries)
        $allAchievements = Achievement::all()->keyBy('name');
        $unlockedIds = UserAchievement::where('user_id', $user->id)
            ->pluck('achievement_id')
            ->flip();

        // Gather stats in minimal queries
        $hasCheckin = \App\Models\DailyEntry::where('user_id', $user->id)->exists();
        $maxStreak = \App\Models\Habit::where('user_id', $user->id)->max('longest_streak') ?? 0;
        $totalCodingMinutes = \App\Models\DailyEntry::where('user_id', $user->id)->sum('coding_minutes');
        $completedProjects = \App\Models\Project::where('user_id', $user->id)->where('status', 'completed')->count();

        // Check each achievement without per-achievement DB lookups
        $toUnlock = [];

        if ($hasCheckin) $toUnlock[] = 'First Entry';
        if ($maxStreak >= 7) $toUnlock[] = '7 Day Streak';
        if ($maxStreak >= 30) $toUnlock[] = '30 Day Streak';
        if ($totalCodingMinutes >= 600) $toUnlock[] = 'Code Warrior';
        if ($totalCodingMinutes >= 6000) $toUnlock[] = '100 Hours Coding';
        if ($completedProjects >= 1) $toUnlock[] = 'Builder';
        if ($completedProjects >= 5) $toUnlock[] = 'Project Builder';

        // Batch-unlock all new achievements
        $xpGained = 0;
        foreach ($toUnlock as $name) {
            $achievement = $allAchievements[$name] ?? null;
            if (!$achievement) continue;
            if (isset($unlockedIds[$achievement->id])) continue;

            UserAchievement::create([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'unlocked_at' => today(),
            ]);
            $xpGained += 250;
        }

        if ($xpGained > 0) {
            $user->xp += $xpGained;
            $newLevel = floor($user->xp / 1000) + 1;
            if ($newLevel > $user->level) {
                $user->level = $newLevel;
            }
            $user->save();
        }
    }
}
