@extends('layouts.app')

@section('title', 'Project Tracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Project Creator -->
    <div class="space-y-8">
        <div class="card-brutal bg-brutalist-yellow">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span>🛠️</span> Start New Project
            </h3>
            
            <form method="POST" action="{{ route('projects.store') }}" class="space-y-4 font-mono text-sm">
                @csrf
                
                <div>
                    <label for="name" class="block font-bold mb-1">PROJECT NAME</label>
                    <input type="text" name="name" id="name" required placeholder="e.g. LifeOS, BersihKita" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div>
                    <label for="description" class="block font-bold mb-1">DESCRIPTION</label>
                    <textarea name="description" id="description" rows="3" placeholder="What does this project do?" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2"></textarea>
                </div>

                <div>
                    <label for="tech_stack" class="block font-bold mb-1">TECH STACK (COMMA SEPARATED)</label>
                    <input type="text" name="tech_stack" id="tech_stack" placeholder="e.g. Laravel, Vue, MySQL" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="github_url" class="block font-bold mb-1">GITHUB URL</label>
                        <input type="url" name="github_url" id="github_url" placeholder="https://..." class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                    <div>
                        <label for="demo_url" class="block font-bold mb-1">DEMO URL</label>
                        <input type="url" name="demo_url" id="demo_url" placeholder="https://..." class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block font-bold mb-1">START DATE</label>
                        <input type="date" name="start_date" id="start_date" value="{{ today()->toDateString() }}" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                    <div>
                        <label for="status" class="block font-bold mb-1">STATUS</label>
                        <select name="status" id="status" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                            <option value="not_started">Not Started</option>
                            <option value="in_progress" selected>In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 btn-brutal-primary mt-2">
                    🛠️ CREATE PROJECT
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Projects list & tasks -->
    <div class="lg:col-span-2 space-y-8">
        <div class="card-brutal bg-white">
            <h2 class="font-headline text-2xl font-bold mb-6">Active Projects</h2>

            <div class="space-y-8">
                @forelse($projects as $project)
                    <div class="border-4 border-brutalist-primary rounded-[10px] p-5 shadow-brutal bg-white">
                        <!-- Top details -->
                        <div class="flex justify-between items-start gap-4 mb-4">
                            <div>
                                <span class="font-mono text-[9px] font-bold uppercase border-2 border-brutalist-primary bg-white px-2 py-0.5 rounded">
                                    {{ str_replace('_', ' ', $project->status) }}
                                </span>
                                <h3 class="font-headline text-2xl font-extrabold mt-1">{{ $project->name }}</h3>
                            </div>
                            
                            <!-- Links & Actions -->
                            <div class="flex gap-2">
                                @if ($project->github_url)
                                    <a href="{{ $project->github_url }}" target="_blank" class="w-8 h-8 border-2 border-brutalist-primary rounded bg-white hover:bg-gray-100 flex items-center justify-center shadow-brutal-sm" title="GitHub Code">
                                        🐙
                                    </a>
                                @endif
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" class="w-8 h-8 border-2 border-brutalist-primary rounded bg-white hover:bg-gray-100 flex items-center justify-center shadow-brutal-sm" title="Live Demo">
                                        🔗
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Remove this project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 border-2 border-brutalist-primary rounded bg-white hover:bg-brutalist-red hover:text-white font-bold flex items-center justify-center text-xs shadow-brutal-sm transition-all active:translate-x-[1px] active:translate-y-[1px] active:shadow-none" title="Delete Project">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Description -->
                        @if ($project->description)
                            <p class="text-sm text-gray-700 mb-4">{{ $project->description }}</p>
                        @endif

                        <!-- Tech Stack chips -->
                        @if ($project->tech_stack)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach ($project->tech_stack_array as $tech)
                                    <span class="text-[10px] font-mono font-bold bg-brutalist-bg border-2 border-brutalist-primary px-2 py-0.5 rounded-[4px]">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center text-xs font-mono font-bold mb-1">
                                <span>COMPLETION PROGRESS</span>
                                <span>{{ $project->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 border-2 border-brutalist-primary h-6 rounded-[6px] overflow-hidden relative shadow-brutal-sm">
                                <div class="bg-brutalist-yellow h-full" style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>

                        <!-- Task Manager -->
                        <div class="pt-4 border-t-2 border-dashed border-brutalist-primary space-y-4">
                            <span class="font-mono text-[10px] font-bold text-gray-400 block">PROJECT TASKS</span>
                            
                            <div class="space-y-2">
                                @forelse($project->tasks as $task)
                                    <div class="flex items-center justify-between bg-brutalist-bg border-2 border-brutalist-primary rounded-[6px] p-2.5 shadow-brutal-sm"
                                         x-data="{ completed: {{ $task->completed ? 'true' : 'false' }} }">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('projects.toggleTask', $task) }}"
                                                  @submit.prevent="
                                                      axios.post($el.action, new FormData($el));
                                                      completed = !completed;
                                                  ">
                                                @csrf
                                                <button type="submit" class="w-5 h-5 rounded-[4px] border-2 border-brutalist-primary font-extrabold flex items-center justify-center text-xs select-none transition-all"
                                                        :class="completed ? 'bg-brutalist-primary text-white' : 'bg-white text-transparent'">
                                                    X
                                                </button>
                                            </form>
                                            <span class="font-mono text-xs font-bold"
                                                  :class="completed ? 'line-through text-gray-400' : ''">
                                                {{ $task->title }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] font-mono text-gray-400 italic">No tasks listed for this project.</p>
                                @endforelse
                            </div>

                            <!-- Add task form -->
                            <form method="POST" action="{{ route('projects.storeTask', $project) }}" class="flex gap-2 font-mono text-xs">
                                @csrf
                                <input type="text" name="title" required placeholder="Add project milestone/task..." class="flex-1 border-2 border-brutalist-primary rounded-[6px] p-2 focus:outline-none">
                                <button type="submit" class="btn-brutal bg-white hover:bg-gray-100 px-4 py-2 shadow-brutal-sm">
                                    + ADD TASK
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center bg-brutalist-bg rounded-[10px] border-4 border-dashed border-brutalist-primary">
                        <span class="text-5xl block mb-4">🛠️</span>
                        <p class="text-base font-bold">No active projects.</p>
                        <p class="text-sm text-gray-500">Log a new project on the left panel to begin managing milestone checklists.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
