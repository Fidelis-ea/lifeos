<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTask;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)
            ->with('tasks')
            ->orderByRaw("FIELD(status, 'in_progress', 'not_started', 'completed', 'archived')")
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|in:not_started,in_progress,completed,archived',
            'tech_stack' => 'nullable|string|max:200', // comma-separated values
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['progress'] = 0;

        Project::create($validated);

        return back()->with('success', 'Project created! 🛠️');
    }

    public function storeTask(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'title' => 'required|string|max:200',
        ]);

        $project->tasks()->create([
            'title' => $request->title,
            'completed' => false,
        ]);

        $project->recalculateProgress();

        return back()->with('success', 'Project task added!');
    }

    public function toggleTask(ProjectTask $task)
    {
        $this->authorize('update', $task->project);
        $task->update(['completed' => !$task->completed]);
        $task->project->recalculateProgress();

        return back();
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,archived',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $project->update($validated);

        return back()->with('success', 'Project updated!');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();

        return back()->with('success', 'Project deleted.');
    }
}
