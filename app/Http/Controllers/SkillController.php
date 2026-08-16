<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\SkillProgress;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $skills = Skill::where('user_id', $user->id)
            ->with('progressHistory')
            ->get();

        return view('skills.index', compact('skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'current_level' => 'required|integer|min:0|max:100',
            'target_level' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['learning_hours'] = 0;

        $skill = Skill::create($validated);

        // Save initial progress history
        SkillProgress::create([
            'skill_id' => $skill->id,
            'level' => $skill->current_level,
            'logged_date' => today()->toDateString(),
            'notes' => 'Initial level recorded',
        ]);

        return back()->with('success', 'Skill added! 🧠');
    }

    public function updateProgress(Request $request, Skill $skill)
    {
        $this->authorize('update', $skill);

        $validated = $request->validate([
            'level' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:255',
        ]);

        $skill->update(['current_level' => $validated['level']]);

        SkillProgress::create([
            'skill_id' => $skill->id,
            'level' => $validated['level'],
            'logged_date' => today()->toDateString(),
            'notes' => $validated['notes'] ?? 'Updated skill level',
        ]);

        return back()->with('success', 'Skill progress logged! 📈');
    }
}
