@extends('layouts.app')

@section('title', 'Skill Tracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Skill Creator -->
    <div class="space-y-8">
        <div class="card-brutal bg-brutalist-pink">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span>🧠</span> Unlock New Skill
            </h3>
            
            <form method="POST" action="{{ route('skills.store') }}" class="space-y-4 font-mono text-sm">
                @csrf
                
                <div>
                    <label for="name" class="block font-bold mb-1">SKILL NAME</label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Laravel, Figma, Japanese" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div>
                    <label for="category" class="block font-bold mb-1">CATEGORY</label>
                    <select name="category" id="category" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                        <option value="Programming">Programming</option>
                        <option value="Design">Design</option>
                        <option value="Language">Language</option>
                        <option value="Soft Skills">Soft Skills</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="current_level" class="block font-bold mb-1">START LEVEL (%)</label>
                        <input type="number" name="current_level" id="current_level" required min="0" max="100" value="10" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                    <div>
                        <label for="target_level" class="block font-bold mb-1">TARGET LEVEL (%)</label>
                        <input type="number" name="target_level" id="target_level" required min="0" max="100" value="100" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block font-bold mb-1">INITIAL NOTES (OPTIONAL)</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Describe your current status..." class="w-full border-2 border-brutalist-primary rounded-[6px] p-2"></textarea>
                </div>

                <button type="submit" class="w-full py-3 btn-brutal-primary mt-2">
                    🧠 ADD SKILL
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Skills progress lists -->
    <div class="lg:col-span-2 space-y-8">
        <div class="card-brutal bg-white">
            <h2 class="font-headline text-2xl font-bold mb-6">Skills Mastery</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($skills as $skill)
                    <div class="card-brutal bg-white relative hover:-translate-y-0.5 transition-transform flex flex-col justify-between">
                        <div>
                            <!-- Header -->
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-mono text-[9px] font-bold uppercase bg-brutalist-primary text-white px-2 py-0.5 rounded">
                                    {{ $skill->category }}
                                </span>
                                <span class="font-mono text-xs font-bold text-gray-500">
                                    ⏱️ {{ $skill->learning_hours }} hrs logged
                                </span>
                            </div>

                            <!-- Name -->
                            <h3 class="font-headline text-xl font-bold mb-2">{{ $skill->name }}</h3>
                            @if ($skill->notes)
                                <p class="text-xs text-gray-600 mb-4">{{ $skill->notes }}</p>
                            @endif

                            <!-- Level bar -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center font-mono text-xs mb-1">
                                    <span>LEVEL</span>
                                    <span>{{ $skill->current_level }}% / {{ $skill->target_level }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 border-2 border-brutalist-primary h-5 rounded-[4px] overflow-hidden relative shadow-brutal-sm">
                                    <div class="bg-brutalist-pink h-full" style="width: {{ $skill->current_level }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Progress Form -->
                        <div class="pt-4 border-t-2 border-dashed border-brutalist-primary mt-4">
                            <span class="font-mono text-[9px] font-bold text-gray-400 block mb-2">UPDATE PROGRESS</span>
                            
                            <form method="POST" action="{{ route('skills.updateProgress', $skill) }}" class="flex flex-col gap-2">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="number" name="level" required min="0" max="100" value="{{ $skill->current_level }}"
                                           class="w-20 shrink-0 border-2 border-brutalist-primary rounded-[6px] p-1.5 font-mono text-xs">
                                    <input type="text" name="notes" placeholder="Update note..."
                                           class="min-w-0 flex-1 border-2 border-brutalist-primary rounded-[6px] p-1.5 text-xs font-mono">
                                </div>
                                <button type="submit" class="w-full btn-brutal bg-white hover:bg-gray-100 px-3 py-1.5 text-xs shadow-brutal-sm">
                                    📝 LOG PROGRESS
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 py-12 text-center font-mono bg-brutalist-bg rounded-[10px] border-4 border-dashed border-brutalist-primary">
                        <span class="text-5xl block mb-4">🧠</span>
                        <p class="text-base font-bold">No skills logged yet.</p>
                        <p class="text-sm text-gray-500">Record a new skill on the left panel to begin your learning tracking journey.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
