@extends('layouts.app')

@section('title', 'Daily Check-in')

@section('content')
<form method="POST" action="{{ route('checkin.store') }}" class="space-y-8 max-w-4xl">
    @csrf

    <!-- Page Header -->
    <div class="card-brutal bg-white">
        <h1 class="font-headline text-3xl font-extrabold mb-1">Daily Log — {{ $today->format('F d, Y') }}</h1>
        <p class="font-mono text-sm text-gray-500">Record your metrics, study hours, and notes to feed your statistics.</p>
    </div>

    <!-- Vitals Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Mood -->
        <div class="card-brutal-yellow card-brutal flex flex-col justify-between">
            <div>
                <label for="mood" class="font-mono font-bold text-xs block mb-1">MOOD LEVEL</label>
                <span class="text-3xl">😊</span>
            </div>
            <div class="mt-4">
                <input type="range" name="mood" id="mood" min="1" max="10" value="{{ $entry ? $entry->mood : 7 }}" class="w-full accent-brutalist-primary bg-white h-2 rounded-lg border-2 border-brutalist-primary cursor-pointer" oninput="document.getElementById('mood_val').innerText = this.value">
                <div class="flex justify-between items-center mt-2 font-mono font-bold text-sm">
                    <span>1</span>
                    <span id="mood_val" class="bg-white px-2 py-0.5 border-2 border-brutalist-primary rounded">{{ $entry ? $entry->mood : 7 }}</span>
                    <span>10</span>
                </div>
            </div>
        </div>

        <!-- Energy -->
        <div class="card-brutal-green card-brutal flex flex-col justify-between">
            <div>
                <label for="energy" class="font-mono font-bold text-xs block mb-1">ENERGY LEVEL</label>
                <span class="text-3xl">⚡</span>
            </div>
            <div class="mt-4">
                <input type="range" name="energy" id="energy" min="1" max="10" value="{{ $entry ? $entry->energy : 7 }}" class="w-full accent-brutalist-primary bg-white h-2 rounded-lg border-2 border-brutalist-primary cursor-pointer" oninput="document.getElementById('energy_val').innerText = this.value">
                <div class="flex justify-between items-center mt-2 font-mono font-bold text-sm">
                    <span>1</span>
                    <span id="energy_val" class="bg-white px-2 py-0.5 border-2 border-brutalist-primary rounded">{{ $entry ? $entry->energy : 7 }}</span>
                    <span>10</span>
                </div>
            </div>
        </div>

        <!-- Sleep Duration -->
        <div class="card-brutal-blue card-brutal flex flex-col justify-between">
            <div>
                <label for="sleep_hours" class="font-mono font-bold text-xs block mb-1">SLEEP DURATION</label>
                <span class="text-3xl">🌙</span>
            </div>
            <div class="mt-4">
                <input type="number" step="0.1" name="sleep_hours" id="sleep_hours" value="{{ $entry ? $entry->sleep_hours : 7.0 }}" class="w-full border-4 border-brutalist-primary rounded-[8px] p-2 font-mono font-bold text-sm focus:outline-none focus:ring-0 focus:border-brutalist-yellow">
                <div class="mt-2 text-right font-mono text-[10px] font-bold">HOURS (e.g. 7.5)</div>
            </div>
        </div>

        <!-- Productivity -->
        <div class="card-brutal-pink card-brutal flex flex-col justify-between">
            <div>
                <label for="productivity" class="font-mono font-bold text-xs block mb-1">PRODUCTIVITY</label>
                <span class="text-3xl">📈</span>
            </div>
            <div class="mt-4">
                <input type="range" name="productivity" id="productivity" min="1" max="10" value="{{ $entry ? $entry->productivity : 7 }}" class="w-full accent-brutalist-primary bg-white h-2 rounded-lg border-2 border-brutalist-primary cursor-pointer" oninput="document.getElementById('prod_val').innerText = this.value">
                <div class="flex justify-between items-center mt-2 font-mono font-bold text-sm">
                    <span>1</span>
                    <span id="prod_val" class="bg-white px-2 py-0.5 border-2 border-brutalist-primary rounded">{{ $entry ? $entry->productivity : 7 }}</span>
                    <span>10</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities Grid -->
    <div class="card-brutal bg-white">
        <h3 class="font-headline text-xl font-bold mb-6 flex items-center gap-2">
            <span>⏱️</span> Log Activities Today
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-6">
            <!-- Coding -->
            <div class="border-4 border-brutalist-primary rounded-[8px] p-4 bg-brutalist-bg flex flex-col justify-between">
                <div>
                    <span class="text-xl">💻</span>
                    <label for="coding_minutes" class="font-mono font-bold text-xs block mt-1">CODING</label>
                </div>
                <div class="mt-4">
                    <input type="number" name="coding_minutes" id="coding_minutes" value="{{ $entry ? $entry->coding_minutes : 0 }}" class="w-full border-2 border-brutalist-primary rounded-[4px] p-1 font-mono text-sm">
                    <div class="mt-1 font-mono text-[9px] text-gray-500">MINUTES</div>
                </div>
            </div>

            <!-- Learning -->
            <div class="border-4 border-brutalist-primary rounded-[8px] p-4 bg-brutalist-bg flex flex-col justify-between">
                <div>
                    <span class="text-xl">📚</span>
                    <label for="learning_minutes" class="font-mono font-bold text-xs block mt-1">LEARNING</label>
                </div>
                <div class="mt-4">
                    <input type="number" name="learning_minutes" id="learning_minutes" value="{{ $entry ? $entry->learning_minutes : 0 }}" class="w-full border-2 border-brutalist-primary rounded-[4px] p-1 font-mono text-sm">
                    <div class="mt-1 font-mono text-[9px] text-gray-500">MINUTES</div>
                </div>
            </div>

            <!-- Exercise -->
            <div class="border-4 border-brutalist-primary rounded-[8px] p-4 bg-brutalist-bg flex flex-col justify-between">
                <div>
                    <span class="text-xl">🏋️</span>
                    <label for="exercise_minutes" class="font-mono font-bold text-xs block mt-1">EXERCISE</label>
                </div>
                <div class="mt-4">
                    <input type="number" name="exercise_minutes" id="exercise_minutes" value="{{ $entry ? $entry->exercise_minutes : 0 }}" class="w-full border-2 border-brutalist-primary rounded-[4px] p-1 font-mono text-sm">
                    <div class="mt-1 font-mono text-[9px] text-gray-500">MINUTES</div>
                </div>
            </div>

            <!-- Gaming -->
            <div class="border-4 border-brutalist-primary rounded-[8px] p-4 bg-brutalist-bg flex flex-col justify-between">
                <div>
                    <span class="text-xl">🎮</span>
                    <label for="gaming_minutes" class="font-mono font-bold text-xs block mt-1">GAMING</label>
                </div>
                <div class="mt-4">
                    <input type="number" name="gaming_minutes" id="gaming_minutes" value="{{ $entry ? $entry->gaming_minutes : 0 }}" class="w-full border-2 border-brutalist-primary rounded-[4px] p-1 font-mono text-sm">
                    <div class="mt-1 font-mono text-[9px] text-gray-500">MINUTES</div>
                </div>
            </div>

            <!-- Japanese -->
            <div class="border-4 border-brutalist-primary rounded-[8px] p-4 bg-brutalist-bg flex flex-col justify-between">
                <div>
                    <span class="text-xl">🇯🇵</span>
                    <label for="japanese_minutes" class="font-mono font-bold text-xs block mt-1">JAPANESE</label>
                </div>
                <div class="mt-4">
                    <input type="number" name="japanese_minutes" id="japanese_minutes" value="{{ $entry ? $entry->japanese_minutes : 0 }}" class="w-full border-2 border-brutalist-primary rounded-[4px] p-1 font-mono text-sm">
                    <div class="mt-1 font-mono text-[9px] text-gray-500">MINUTES</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Note -->
    <div class="card-brutal bg-white">
        <label for="notes" class="font-headline text-xl font-bold block mb-4">✍️ Daily Note</label>
        <textarea name="notes" id="notes" rows="4" placeholder="Write down what you did, thought, or accomplished today..." class="w-full border-4 border-brutalist-primary rounded-[8px] p-4 font-sans text-sm focus:outline-none focus:ring-0 focus:border-brutalist-yellow">{{ $entry ? $entry->notes : '' }}</textarea>
    </div>

    <!-- Save Button -->
    <div>
        <button type="submit" class="w-full py-4 text-lg btn-brutal-primary shadow-brutal-lg">
            💾 SAVE TODAY'S CHECK-IN
        </button>
    </div>
</form>
@endsection
