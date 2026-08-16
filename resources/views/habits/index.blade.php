@extends('layouts.app')

@section('title', 'Habit Tracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-8">
    <!-- Left: Habit Builder Form -->
    <div class="space-y-5 lg:space-y-8">
        <div class="card-brutal bg-brutalist-green">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span>🔥</span> Build New Habit
            </h3>
            
            <form method="POST" action="{{ route('habits.store') }}" class="space-y-4 font-mono text-sm">
                @csrf
                
                <div>
                    <label for="name" class="block font-bold mb-1">HABIT NAME</label>
                    <input type="text" name="name" id="name" required placeholder="e.g. Read 20 mins, Drink water" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div>
                    <label for="description" class="block font-bold mb-1">DESCRIPTION (OPTIONAL)</label>
                    <input type="text" name="description" id="description" placeholder="Why do you want to build this habit?" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="icon" class="block font-bold mb-1">EMOJI ICON</label>
                        <input type="text" name="icon" id="icon" value="⭐" placeholder="⭐" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 text-center text-lg">
                    </div>
                    <div>
                        <label for="color" class="block font-bold mb-1">COLOR accent</label>
                        <select name="color" id="color" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white font-mono">
                            <option value="#FFD43B" class="bg-brutalist-yellow">Yellow</option>
                            <option value="#9BE564" class="bg-brutalist-green">Green</option>
                            <option value="#5BC0EB" class="bg-brutalist-blue">Blue</option>
                            <option value="#FF7EB6" class="bg-brutalist-pink">Pink</option>
                            <option value="#FF6B6B" class="bg-brutalist-red">Red</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="frequency" class="block font-bold mb-1">FREQUENCY</label>
                    <select name="frequency" id="frequency" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3 btn-brutal-primary mt-2">
                    🔥 BUILD HABIT
                </button>
            </form>
        </div>
    </div>

    <!-- Right: Habit List and Heatmaps -->
    <div class="lg:col-span-2 space-y-5 lg:space-y-8">
        <div class="card-brutal bg-white">
            <h2 class="font-headline text-xl lg:text-2xl font-bold mb-4 lg:mb-6">Active Habits & 30-Day Heatmap</h2>

            <div class="space-y-8">
                @forelse($habits as $habit)
                    {{-- Each habit card owns its own `done` state --}}
                    <div class="border-4 border-brutalist-primary rounded-[10px] p-4 lg:p-5 shadow-brutal hover:-translate-y-0.5 transition-transform bg-white"
                         x-data="{ done: {{ $habit->done_today ? 'true' : 'false' }} }">

                        <!-- Habit Info & Stats -->
                        <div class="flex flex-col gap-3 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-[8px] border-2 border-brutalist-primary flex items-center justify-center text-2xl shadow-brutal-sm" style="background-color: {{ $habit->color }}">
                                    {{ $habit->icon }}
                                </div>
                                <div>
                                    <h3 class="font-headline text-lg font-bold leading-tight">{{ $habit->name }}</h3>
                                    @if ($habit->description)
                                        <p class="text-xs text-gray-500 font-sans mt-0.5">{{ $habit->description }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Streaks & Logging -->
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex gap-2 lg:gap-4 font-mono text-xs">
                                    <div class="text-center bg-brutalist-bg border-2 border-brutalist-primary px-3 py-1 rounded-[6px]">
                                        <span class="block text-[10px] text-gray-500 font-bold uppercase">Current</span>
                                        <span class="font-bold text-sm">🔥 {{ $habit->current_streak }}d</span>
                                    </div>
                                    <div class="text-center bg-brutalist-bg border-2 border-brutalist-primary px-3 py-1 rounded-[6px]">
                                        <span class="block text-[10px] text-gray-500 font-bold uppercase">Best</span>
                                        <span class="font-bold text-sm">🏆 {{ $habit->longest_streak }}d</span>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('habits.log', $habit) }}"
                                          @submit.prevent="
                                              axios.post($el.action, new FormData($el));
                                              done = !done;
                                          ">
                                        @csrf
                                        <button type="submit"
                                                class="btn-brutal text-xs px-4 py-2 flex items-center gap-2 shadow-brutal transition-all duration-200"
                                                :class="done ? 'bg-brutalist-primary text-white' : 'bg-white hover:bg-gray-100'">
                                            ✓ <span x-text="done ? 'LOGGED' : 'LOG TODAY'">{{ $habit->done_today ? 'LOGGED' : 'LOG TODAY' }}</span>
                                        </button>
                                    </form>

                                    <!-- Delete habit button -->
                                    <form method="POST" action="{{ route('habits.destroy', $habit) }}" onsubmit="return confirm('Remove this habit?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 border-2 border-brutalist-primary rounded bg-white hover:bg-brutalist-red hover:text-white font-bold flex items-center justify-center">
                                            &times;
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- 30-Day Heatmap -->
                        <div class="pt-4 border-t-2 border-dashed border-brutalist-primary">
                            <span class="font-mono text-[10px] font-bold text-gray-400 block mb-2">30-DAY COMPLETION HEATMAP</span>
                            <div class="flex flex-wrap gap-2">
                                @for($i = 29; $i >= 0; $i--)
                                    @php
                                        $date = today()->subDays($i)->format('Y-m-d');
                                        $completed = in_array($date, $habit->monthly_logs);
                                        $tooltipDate = today()->subDays($i)->format('M d');
                                        $isToday = ($i === 0);
                                    @endphp
                                    @if($isToday)
                                        {{-- TODAY box: reactive via Alpine `done` state --}}
                                        <div class="w-6 h-6 border-2 border-brutalist-primary rounded-[4px] cursor-default transition-all duration-200 flex items-center justify-center"
                                             :class="done ? 'shadow-brutal-sm' : ''"
                                             :style="done ? 'background-color: {{ $habit->color }}' : 'background-color: #FFFFFF'"
                                             title="{{ $tooltipDate }}: Today">
                                            <span class="text-[9px] font-bold text-brutalist-primary transition-opacity duration-200"
                                                  :class="done ? 'opacity-100' : 'opacity-0'">✓</span>
                                        </div>
                                    @else
                                        {{-- Past days: static --}}
                                        <div class="w-6 h-6 border-2 border-brutalist-primary rounded-[4px] transition-all duration-100 flex items-center justify-center {{ $completed ? 'shadow-brutal-sm' : '' }}"
                                             style="background-color: {{ $completed ? $habit->color : '#FFFFFF' }}"
                                             title="{{ $tooltipDate }}: {{ $completed ? 'Completed' : 'Not completed' }}">
                                            @if ($completed)
                                                <span class="text-[9px] font-bold text-brutalist-primary">✓</span>
                                            @endif
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center font-mono">
                        <span class="text-5xl block mb-4">🌱</span>
                        <p class="text-base font-bold">No habits active.</p>
                        <p class="text-sm text-gray-500">Create a habit using the left panel to begin your tracking streak!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
