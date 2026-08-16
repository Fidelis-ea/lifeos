<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HabitController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $habits = Habit::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($habit) {
                $habit->done_today = $habit->isCompletedToday();
                // Get last 30 days of logs
                $habit->monthly_logs = HabitLog::where('habit_id', $habit->id)
                    ->where('date', '>=', today()->subDays(29))
                    ->where('completed', true)
                    ->pluck('date')
                    ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
                    ->toArray();
                return $habit;
            });

        return view('habits.index', compact('habits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'icon'        => 'nullable|string|max:10',
            'color'       => 'nullable|string|max:20',
            'frequency'   => 'nullable|in:daily,weekly',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['order'] = Habit::where('user_id', auth()->id())->max('order') + 1;

        Habit::create($validated);

        return back()->with('success', 'Habit created! 🔥');
    }

    public function destroy(Habit $habit)
    {
        $this->authorize('delete', $habit);
        $habit->delete();
        return back()->with('success', 'Habit removed.');
    }

    public function log(Request $request, Habit $habit)
    {
        $this->authorize('update', $habit);

        $log = HabitLog::updateOrCreate(
            ['habit_id' => $habit->id, 'date' => today()->toDateString()],
            ['user_id' => auth()->id(), 'completed' => !$habit->isCompletedToday()]
        );

        $habit->recalculateStreak();

        if ($request->wantsJson()) {
            return response()->json([
                'completed' => $log->completed,
                'streak' => $habit->fresh()->current_streak,
            ]);
        }

        return back()->with('success', $log->completed ? 'Habit logged! 🔥' : 'Habit unchecked.');
    }
}
