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

        // Let's check/update achievements for this user programmatically so it updates live
        $this->checkAchievements($user);

        $unlocked = $user->achievements()->withPivot('unlocked_at')->get();
        $unlockedIds = $unlocked->pluck('id')->toArray();
        $allAchievements = Achievement::all();
        $locked = $allAchievements->whereNotIn('id', $unlockedIds);

        // Calculate statistics
        $unlockedCount = $unlocked->count();
        $totalCount = $allAchievements->count();
        $totalXp = $user->xp;
        $level = $user->level;

        return view('achievements.index', compact('unlocked', 'locked', 'unlockedCount', 'totalCount', 'totalXp', 'level'));
    }

    private function checkAchievements($user): void
    {
        // 1. First Entry check
        $hasCheckin = \App\Models\DailyEntry::where('user_id', $user->id)->exists();
        if ($hasCheckin) {
            $this->unlock($user, 'First Entry');
        }

        // 2. 7 Day Streak / 30 Day Streak
        $maxStreak = \App\Models\Habit::where('user_id', $user->id)->max('longest_streak') ?? 0;
        if ($maxStreak >= 7) {
            $this->unlock($user, '7 Day Streak');
        }
        if ($maxStreak >= 30) {
            $this->unlock($user, '30 Day Streak');
        }

        // 3. Code Warrior
        $totalCodingMinutes = \App\Models\DailyEntry::where('user_id', $user->id)->sum('coding_minutes');
        if ($totalCodingMinutes >= 600) { // 10 hours
            $this->unlock($user, 'Code Warrior');
        }
        if ($totalCodingMinutes >= 6000) { // 100 hours
            $this->unlock($user, '100 Hours Coding');
        }

        // 4. Project Builder
        $completedProjects = \App\Models\Project::where('user_id', $user->id)->where('status', 'completed')->count();
        if ($completedProjects >= 1) {
            $this->unlock($user, 'Builder');
        }
        if ($completedProjects >= 5) {
            $this->unlock($user, 'Project Builder');
        }
    }

    private function unlock($user, $achievementName): void
    {
        $achievement = Achievement::where('name', $achievementName)->first();
        if (!$achievement) return;

        $exists = UserAchievement::where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->exists();

        if (!$exists) {
            UserAchievement::create([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'unlocked_at' => today(),
            ]);

            // Add XP and handle leveling up
            $xpAwarded = 250; // default XP per achievement
            $user->xp += $xpAwarded;
            
            // Basic level up algorithm: level = floor(xp / 1000) + 1
            $newLevel = floor($user->xp / 1000) + 1;
            if ($newLevel > $user->level) {
                $user->level = $newLevel;
            }
            $user->save();
        }
    }
}
