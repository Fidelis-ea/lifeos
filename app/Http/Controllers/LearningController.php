<?php

namespace App\Http\Controllers;

use App\Models\LearningLog;
use App\Models\Skill;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $logs = LearningLog::where('user_id', $user->id)
            ->with('skill')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->paginate(15);

        $skills = Skill::where('user_id', $user->id)->get();

        // Calculate statistics
        $stats = [
            'today' => LearningLog::where('user_id', $user->id)->where('date', today())->sum('duration_minutes'),
            'weekly' => LearningLog::where('user_id', $user->id)->whereBetween('date', [today()->startOfWeek(), today()->endOfWeek()])->sum('duration_minutes'),
            'monthly' => LearningLog::where('user_id', $user->id)->whereMonth('date', today()->month)->whereYear('date', today()->year)->sum('duration_minutes'),
        ];

        return view('learning.index', compact('logs', 'skills', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:100',
            'topic' => 'required|string|max:150',
            'duration_minutes' => 'required|integer|min:1',
            'date' => 'required|date',
            'skill_id' => 'nullable|exists:skills,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();
        LearningLog::create($validated);

        // If skill linked, add duration to skill hours
        if (!empty($validated['skill_id'])) {
            $skill = Skill::find($validated['skill_id']);
            if ($skill && $skill->user_id === auth()->id()) {
                $skill->increment('learning_hours', round($validated['duration_minutes'] / 60, 1));
            }
        }

        return back()->with('success', 'Learning time logged! 📚');
    }

    public function destroy(LearningLog $log)
    {
        abort_if($log->user_id !== auth()->id(), 403);
        $log->delete();
        return back()->with('success', 'Log deleted.');
    }
}
