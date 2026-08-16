@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-8">
    <!-- Greeting & Quick Stats (Mood, Sleep, etc.) -->
    <div class="lg:col-span-2 space-y-5 lg:space-y-8">
        <!-- Welcoming Card -->
        <div class="card-brutal bg-brutalist-yellow">
            <h1 class="font-headline text-2xl sm:text-3xl lg:text-4xl font-extrabold mb-2">Hello, {{ auth()->user()->name }}! 👋</h1>
            <p class="font-mono text-xs sm:text-sm">Welcome to your Personal Life Operating System. Make today count by completing your habits and tracking your goals.</p>
        </div>

        <!-- Vitals row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <!-- Mood -->
            <div class="card-brutal bg-white hover:-translate-y-1 transition-transform">
                <span class="text-xs font-mono font-bold block text-gray-500 mb-1">MOOD</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-headline font-extrabold">{{ $todayEntry ? $todayEntry->mood : 'N/A' }}</span>
                    <span class="text-xs text-gray-500 font-mono">/10</span>
                </div>
                <span class="text-2xl mt-2 block">{{ $todayEntry && $todayEntry->mood >= 8 ? '😊' : ($todayEntry && $todayEntry->mood >= 5 ? '😐' : ($todayEntry ? '😭' : '❔')) }}</span>
            </div>

            <!-- Energy -->
            <div class="card-brutal bg-white hover:-translate-y-1 transition-transform">
                <span class="text-xs font-mono font-bold block text-gray-500 mb-1">ENERGY</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-headline font-extrabold">{{ $todayEntry ? $todayEntry->energy : 'N/A' }}</span>
                    <span class="text-xs text-gray-500 font-mono">/10</span>
                </div>
                <span class="text-2xl mt-2 block">⚡</span>
            </div>

            <!-- Sleep -->
            <div class="card-brutal bg-white hover:-translate-y-1 transition-transform">
                <span class="text-xs font-mono font-bold block text-gray-500 mb-1">SLEEP</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-headline font-extrabold truncate">{{ $todayEntry ? $todayEntry->sleep_formatted : 'N/A' }}</span>
                </div>
                <span class="text-2xl mt-2 block">🌙</span>
            </div>

            <!-- Productivity -->
            <div class="card-brutal bg-white hover:-translate-y-1 transition-transform">
                <span class="text-xs font-mono font-bold block text-gray-500 mb-1">PRODUCTIVITY</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-headline font-extrabold">{{ $todayEntry ? $todayEntry->productivity : 'N/A' }}</span>
                    <span class="text-xs text-gray-500 font-mono">/10</span>
                </div>
                <span class="text-2xl mt-2 block">📈</span>
            </div>
        </div>

        <!-- Weekly Averages Chart -->
        <div class="card-brutal bg-white">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span>📊</span> Weekly Productivity Analysis (Last 7 Days)
            </h3>
            @if ($weeklyStats['days_logged'] > 0)
                <div class="h-64 relative">
                    <canvas id="weeklyProductivityChart"></canvas>
                </div>
            @else
                <div class="h-64 flex flex-col items-center justify-center border-2 border-dashed border-brutalist-primary rounded-[10px] bg-brutalist-bg">
                    <span class="text-3xl mb-2">🤷‍♂️</span>
                    <p class="font-mono text-sm">No data recorded this week. Log today to start statistics!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Sidebar on Dashboard -->
    <div class="space-y-8">
        <!-- Habits streak summary -->
        <div class="card-brutal bg-brutalist-green"
             x-data="{ completedCount: {{ $habitsCompletedToday }}, total: {{ $habitsTotal }} }">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center justify-between">
                <span>🔥 Habits Today</span>
                <span class="font-mono text-sm bg-white border-2 border-brutalist-primary px-2 py-0.5 rounded-[4px]"
                      x-text="completedCount + '/' + total">
                    {{ $habitsCompletedToday }}/{{ $habitsTotal }}
                </span>
            </h3>
            
            <div class="space-y-3">
                @forelse($habits as $habit)
                    <div class="bg-white border-2 border-brutalist-primary rounded-[8px] p-3 flex items-center justify-between shadow-brutal-sm"
                         x-data="{ done: {{ $habit->done_today ? 'true' : 'false' }} }">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $habit->icon }}</span>
                            <div>
                                <h4 class="font-bold text-sm leading-tight">{{ $habit->name }}</h4>
                                <span class="text-[10px] font-mono text-gray-500">Streak: {{ $habit->current_streak }}d</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('habits.log', $habit) }}"
                              @submit.prevent="
                                  axios.post($el.action, new FormData($el));
                                  if (done) { completedCount = Math.max(0, completedCount - 1); }
                                  else { completedCount = Math.min(total, completedCount + 1); }
                                  done = !done;
                              ">
                            @csrf
                            <button type="submit"
                                    class="w-8 h-8 rounded-[6px] border-2 border-brutalist-primary font-bold flex items-center justify-center select-none transition-all duration-200"
                                    :class="done ? 'bg-brutalist-primary text-white' : 'bg-white text-gray-300 hover:bg-gray-100 hover:text-gray-500'">
                                ✓
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm font-mono text-center py-4">No habits created yet. <a href="{{ route('habits.index') }}" class="underline font-bold">Add some!</a></p>
                @endforelse
            </div>
        </div>


        <!-- Goals summary -->
        <div class="card-brutal bg-brutalist-blue">
            <h3 class="font-headline text-xl font-bold mb-4">🎯 Active Goals</h3>
            <div class="space-y-4">
                @forelse($activeGoals as $goal)
                    <div class="bg-white border-2 border-brutalist-primary rounded-[8px] p-4 shadow-brutal-sm">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-sm">{{ $goal->title }}</h4>
                            <span class="text-[10px] font-mono uppercase bg-brutalist-primary text-white px-2 py-0.5 rounded-[4px]">{{ $goal->priority }}</span>
                        </div>
                        <div class="w-full bg-gray-200 border-2 border-brutalist-primary h-6 rounded-[6px] overflow-hidden relative">
                            <div class="bg-brutalist-green h-full" style="width: {{ $goal->progress }}%"></div>
                            <span class="absolute inset-0 text-xs font-mono font-bold flex items-center justify-center">
                                {{ $goal->progress }}%
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm font-mono text-center py-4">No active goals. <a href="{{ route('goals.index') }}" class="underline font-bold">Create one!</a></p>
                @endforelse
            </div>
        </div>

        <!-- Recent activities timeline -->
        <div class="card-brutal bg-brutalist-pink">
            <h3 class="font-headline text-xl font-bold mb-4">📅 Recent Activities</h3>
            <div class="space-y-3 font-mono text-xs">
                @forelse($recentTimeline as $entry)
                    <div class="border-b border-brutalist-primary/30 pb-2 last:border-0 last:pb-0">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-sm">{{ $entry->title }}</span>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 border border-brutalist-primary rounded bg-white" style="background-color: {{ $entry->category_info['color'] }}">
                                {{ $entry->category_info['icon'] }} {{ $entry->category }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-600 text-[10px]">
                            <span>{{ $entry->date->format('M d, Y') }}</span>
                            <span>{{ $entry->duration_formatted }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-center py-4 font-mono">No recent timeline entries.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if ($weeklyStats['days_logged'] > 0)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('weeklyProductivityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Mood Scale',
                        data: [7, 8, 6, 8, 7, 9, 8], // Sample values
                        borderColor: '#FFD43B',
                        backgroundColor: '#FFD43B',
                        borderWidth: 4,
                        tension: 0.1,
                        fill: false
                    },
                    {
                        label: 'Productivity Scale',
                        data: [6, 7, 8, 8, 9, 6, 7], // Sample values
                        borderColor: '#FF7EB6',
                        backgroundColor: '#FF7EB6',
                        borderWidth: 4,
                        tension: 0.1,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                family: 'Space Mono',
                                weight: 'bold'
                            },
                            color: '#111111'
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 10,
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            font: {
                                family: 'Space Mono'
                            },
                            color: '#111111'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Space Mono'
                            },
                            color: '#111111'
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
