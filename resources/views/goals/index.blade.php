@extends('layouts.app')

@section('title', 'Goal Tracker')

@section('content')
<!-- Header Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 lg:gap-6 mb-6 lg:mb-8 font-mono text-center">
    <div class="card-brutal bg-white">
        <span class="block text-xs font-bold text-gray-500 uppercase">TOTAL GOALS</span>
        <span class="text-3xl font-headline font-extrabold">{{ $stats['total'] }}</span>
    </div>
    <div class="card-brutal bg-brutalist-blue text-brutalist-primary">
        <span class="block text-xs font-bold text-gray-700 uppercase">IN PROGRESS</span>
        <span class="text-3xl font-headline font-extrabold">{{ $stats['in_progress'] }}</span>
    </div>
    <div class="card-brutal bg-brutalist-green text-brutalist-primary">
        <span class="block text-xs font-bold text-gray-700 uppercase">COMPLETED</span>
        <span class="text-3xl font-headline font-extrabold">{{ $stats['completed'] }}</span>
    </div>
    <div class="card-brutal bg-brutalist-yellow text-brutalist-primary">
        <span class="block text-xs font-bold text-gray-700 uppercase">NOT STARTED</span>
        <span class="text-3xl font-headline font-extrabold">{{ $stats['not_started'] }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-8">
    <!-- Goal Builder Form -->
    <div class="space-y-5 lg:space-y-8">
        <div class="card-brutal bg-brutalist-blue">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span>🎯</span> Launch New Goal
            </h3>
            
            <form method="POST" action="{{ route('goals.store') }}" class="space-y-4 font-mono text-sm">
                @csrf
                
                <div>
                    <label for="title" class="block font-bold mb-1">GOAL TITLE</label>
                    <input type="text" name="title" id="title" required placeholder="e.g. Build personal portfolio" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div>
                    <label for="description" class="block font-bold mb-1">DESCRIPTION</label>
                    <textarea name="description" id="description" rows="3" placeholder="What does achieving this goal look like?" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block font-bold mb-1">CATEGORY</label>
                        <select name="category" id="category" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                            <option value="Coding">Coding</option>
                            <option value="Language">Language</option>
                            <option value="Design">Design</option>
                            <option value="Fitness">Fitness</option>
                            <option value="Finance">Finance</option>
                            <option value="Personal">Personal</option>
                        </select>
                    </div>
                    <div>
                        <label for="priority" class="block font-bold mb-1">PRIORITY</label>
                        <select name="priority" id="priority" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="target_date" class="block font-bold mb-1">TARGET DATE</label>
                        <input type="date" name="target_date" id="target_date" value="{{ today()->addMonths(3)->toDateString() }}" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                    <div>
                        <label for="color" class="block font-bold mb-1">CARD COLOR</label>
                        <select name="color" id="color" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                            <option value="#FFD43B">Yellow Accent</option>
                            <option value="#9BE564">Green Accent</option>
                            <option value="#5BC0EB" selected>Blue Accent</option>
                            <option value="#FF7EB6">Pink Accent</option>
                            <option value="#FF6B6B">Red Accent</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 btn-brutal-primary mt-2">
                    🎯 LAUNCH GOAL
                </button>
            </form>
        </div>
    </div>

    <!-- Goals checklist view -->
    <div class="lg:col-span-2 space-y-5 lg:space-y-8">
        <div class="card-brutal bg-white">
            <h2 class="font-headline text-xl lg:text-2xl font-bold mb-4 lg:mb-6">Goals & Checklists</h2>

            <div class="space-y-6">
                @forelse($goals as $goal)
                    {{-- Goal card with live progress tracking --}}
                    <div class="border-4 border-brutalist-primary rounded-[10px] p-5 shadow-brutal bg-white relative"
                         x-data="{
                             progress: {{ $goal->progress }},
                             totalTasks: {{ $goal->tasks->count() }},
                             async toggleTask(form, taskData) {
                                 const res = await axios.post(form.action, new FormData(form));
                                 if (res.data && res.data.progress !== undefined) {
                                     this.progress = res.data.progress;
                                 }
                                 taskData.completed = !taskData.completed;
                             }
                         }">
                        <!-- Top header -->
                        <div class="flex justify-between items-start gap-4 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-[10px] font-bold uppercase border-2 border-brutalist-primary bg-white px-2 py-0.5 rounded" style="border-color: {{ $goal->color }}">
                                    {{ $goal->category }}
                                </span>
                                <span class="font-mono text-[10px] font-bold uppercase bg-brutalist-primary text-white px-2 py-0.5 rounded">
                                    {{ $goal->priority }}
                                </span>
                            </div>
                            
                            <!-- Delete and Status buttons -->
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('goals.update', $goal) }}">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="border-2 border-brutalist-primary rounded text-xs p-1 font-mono bg-white cursor-pointer">
                                        <option value="not_started" {{ $goal->status === 'not_started' ? 'selected' : '' }}>Not Started</option>
                                        <option value="in_progress" {{ $goal->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $goal->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="archived" {{ $goal->status === 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </form>

                                <form method="POST" action="{{ route('goals.destroy', $goal) }}" onsubmit="return confirm('Remove this goal?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-6 h-6 border-2 border-brutalist-primary rounded bg-white hover:bg-brutalist-red hover:text-white font-bold flex items-center justify-center text-xs">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Goal Title -->
                        <h3 class="font-headline text-xl font-extrabold mb-1 flex items-center gap-2">
                            <span>🎯</span> {{ $goal->title }}
                        </h3>
                        @if ($goal->description)
                            <p class="text-sm text-gray-600 mb-4">{{ $goal->description }}</p>
                        @endif

                        <!-- Progress Bar (live via Alpine) -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center text-xs font-mono font-bold mb-1">
                                <span>PROGRESS</span>
                                <span x-text="progress + '%'">{{ $goal->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 border-2 border-brutalist-primary h-6 rounded-[6px] overflow-hidden relative shadow-brutal-sm">
                                <div class="h-full transition-all duration-500 ease-out"
                                     style="background-color: {{ $goal->color }}"
                                     :style="`width: ${progress}%`"></div>
                                <span class="absolute inset-0 text-xs font-mono font-bold flex items-center justify-center"
                                      x-text="progress + '%'">{{ $goal->progress }}%</span>
                            </div>
                        </div>

                        <!-- Date Target -->
                        @if ($goal->target_date)
                            <div class="flex items-center gap-1.5 text-xs font-mono text-gray-500 mb-4">
                                <span>📅</span> Target Date: {{ $goal->target_date->format('M d, Y') }}
                            </div>
                        @endif

                        <!-- Task Checklist -->
                        <div class="pt-4 border-t-2 border-dashed border-brutalist-primary space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-[10px] font-bold text-gray-400 block">GOAL TASK CHECKLIST</span>
                                <span class="font-mono text-[10px] font-bold text-gray-400" x-text="`${progress}% done`"></span>
                            </div>
                            
                            <!-- Tasks list -->
                            <div class="space-y-2">
                                @forelse($goal->tasks as $task)
                                    <div class="flex items-center justify-between bg-brutalist-bg border-2 border-brutalist-primary rounded-[6px] p-2.5 shadow-brutal-sm"
                                         x-data="{ completed: {{ $task->completed ? 'true' : 'false' }} }">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('goals.toggleTask', $task) }}"
                                                  @submit.prevent="toggleTask($el, $data)">
                                                @csrf
                                                <button type="submit"
                                                        class="w-5 h-5 rounded-[4px] border-2 border-brutalist-primary font-extrabold flex items-center justify-center text-xs select-none transition-all"
                                                        :class="completed ? 'bg-brutalist-primary text-white' : 'bg-white text-transparent'">
                                                    X
                                                </button>
                                            </form>
                                            <span class="font-mono text-xs font-bold transition-all duration-200"
                                                  :class="completed ? 'line-through text-gray-400' : ''">
                                                {{ $task->title }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] font-mono text-gray-400 italic">No sub-tasks. Create one below to begin progress tracking.</p>
                                @endforelse
                            </div>

                            <!-- Add new task to this goal -->
                            <form method="POST" action="{{ route('goals.storeTask', $goal) }}" class="flex gap-2 mt-3">
                                @csrf
                                <input type="text" name="title" required placeholder="Add sub-task..." class="flex-1 border-2 border-brutalist-primary rounded-[6px] p-1.5 text-xs font-mono focus:outline-none">
                                <button type="submit" class="btn-brutal bg-white hover:bg-gray-100 text-xs px-3 py-1.5 shadow-brutal-sm">
                                    + ADD
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center font-mono bg-brutalist-bg rounded-[10px] border-4 border-dashed border-brutalist-primary">
                        <span class="text-5xl block mb-4">🎯</span>
                        <p class="text-base font-bold">No goals created yet.</p>
                        <p class="text-sm text-gray-500">Log a new goal on the left panel to organize your milestones.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
