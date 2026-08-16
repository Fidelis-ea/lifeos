<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalTask;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $goals = Goal::where('user_id', $user->id)
            ->with('tasks')
            ->orderByRaw("CASE status WHEN 'in_progress' THEN 0 WHEN 'not_started' THEN 1 WHEN 'completed' THEN 2 WHEN 'archived' THEN 3 ELSE 4 END")
            ->orderByRaw("CASE priority WHEN 'high' THEN 0 WHEN 'medium' THEN 1 WHEN 'low' THEN 2 ELSE 3 END")
            ->get();

        $stats = [
            'total'       => $goals->count(),
            'in_progress' => $goals->where('status', 'in_progress')->count(),
            'completed'   => $goals->where('status', 'completed')->count(),
            'not_started' => $goals->where('status', 'not_started')->count(),
        ];

        return view('goals.index', compact('goals', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string|max:500',
            'category'    => 'nullable|string|max:50',
            'target_date' => 'nullable|date',
            'priority'    => 'nullable|in:low,medium,high',
            'icon'        => 'nullable|string|max:10',
            'color'       => 'nullable|string|max:20',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['start_date'] = today()->toDateString();
        $validated['status'] = 'not_started';

        Goal::create($validated);

        return back()->with('success', 'Goal created! 🎯');
    }

    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'status'   => 'nullable|in:not_started,in_progress,completed,archived',
            'progress' => 'nullable|integer|min:0|max:100',
            'title'    => 'nullable|string|max:200',
        ]);

        $goal->update($validated);

        return back()->with('success', 'Goal updated!');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        $goal->delete();
        return back()->with('success', 'Goal deleted.');
    }

    public function toggleTask(GoalTask $task)
    {
        $this->authorize('update', $task->goal);
        $task->update(['completed' => !$task->completed]);
        $task->goal->recalculateProgress();

        if (request()->wantsJson()) {
            return response()->json([
                'completed' => $task->completed,
                'progress'  => $task->goal->fresh()->progress,
            ]);
        }
        return back();
    }

    public function storeTask(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);
        $request->validate(['title' => 'required|string|max:200']);

        $goal->tasks()->create([
            'title' => $request->title,
            'order' => $goal->tasks()->max('order') + 1,
        ]);

        $goal->recalculateProgress();
        return back()->with('success', 'Task added!');
    }
}
