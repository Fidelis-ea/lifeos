@extends('layouts.app')

@section('title', 'Learning Tracker')

@section('content')
<!-- Learning Stats Card -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 font-mono text-center">
    <div class="card-brutal bg-white">
        <span class="block text-xs font-bold text-gray-500 uppercase">STUDIED TODAY</span>
        <span class="text-3xl font-headline font-extrabold text-brutalist-primary">{{ $stats['today'] }} mins</span>
    </div>
    <div class="card-brutal bg-brutalist-green text-brutalist-primary">
        <span class="block text-xs font-bold text-gray-700 uppercase">THIS WEEK</span>
        <span class="text-3xl font-headline font-extrabold">{{ round($stats['weekly'] / 60, 1) }} hours</span>
    </div>
    <div class="card-brutal bg-brutalist-yellow text-brutalist-primary">
        <span class="block text-xs font-bold text-gray-700 uppercase">THIS MONTH</span>
        <span class="text-3xl font-headline font-extrabold">{{ round($stats['monthly'] / 60, 1) }} hours</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Log Study Session -->
    <div class="space-y-8">
        <div class="card-brutal bg-brutalist-green">
            <h3 class="font-headline text-xl font-bold mb-4 flex items-center gap-2">
                <span>📚</span> Log Study Session
            </h3>
            
            <form method="POST" action="{{ route('learning.store') }}" class="space-y-4 font-mono text-sm">
                @csrf
                
                <div>
                    <label for="subject" class="block font-bold mb-1">SUBJECT</label>
                    <input type="text" name="subject" id="subject" required placeholder="e.g. Japanese Grammar, Laravel Eloquent" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div>
                    <label for="topic" class="block font-bold mb-1">TOPIC</label>
                    <input type="text" name="topic" id="topic" required placeholder="e.g. Transitive Verbs, N+1 Query Fixes" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="duration_minutes" class="block font-bold mb-1">DURATION (MIN)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" required min="1" placeholder="60" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                    <div>
                        <label for="date" class="block font-bold mb-1">DATE</label>
                        <input type="date" name="date" id="date" required value="{{ today()->toDateString() }}" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                </div>

                <div>
                    <label for="skill_id" class="block font-bold mb-1">ASSOCIATED SKILL</label>
                    <select name="skill_id" id="skill_id" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                        <option value="">None</option>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }} ({{ $skill->category }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="notes" class="block font-bold mb-1">NOTES / SUMMARY</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="What key concepts did you learn?" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2"></textarea>
                </div>

                <button type="submit" class="w-full py-3 btn-brutal-primary mt-2">
                    📚 LOG SESSION
                </button>
            </form>
        </div>
    </div>

    <!-- Study logs history -->
    <div class="lg:col-span-2 space-y-8">
        <div class="card-brutal bg-white">
            <h2 class="font-headline text-2xl font-bold mb-6">Learning Logs</h2>

            <div class="space-y-4 font-mono text-sm">
                @forelse($logs as $log)
                    <div class="border-4 border-brutalist-primary rounded-[10px] p-4 bg-white shadow-brutal-sm hover:-translate-y-0.5 transition-transform">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-2">
                            <div>
                                <span class="text-xs font-bold text-gray-500">{{ $log->date->format('M d, Y') }}</span>
                                <h3 class="font-headline text-lg font-bold leading-tight">{{ $log->subject }}</h3>
                                <span class="text-xs text-gray-600">Topic: {{ $log->topic }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($log->skill)
                                    <span class="text-[10px] font-bold uppercase bg-brutalist-pink border-2 border-brutalist-primary px-2 py-0.5 rounded">
                                        🧠 {{ $log->skill->name }}
                                    </span>
                                @endif
                                <span class="text-xs font-bold bg-brutalist-green border-2 border-brutalist-primary px-2 py-0.5 rounded">
                                    ⏱️ {{ $log->duration_minutes }} mins
                                </span>
                                <form method="POST" action="{{ route('learning.destroy', $log) }}" onsubmit="return confirm('Remove this learning log?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-7 h-7 border-2 border-brutalist-primary rounded bg-white hover:bg-brutalist-red hover:text-white font-bold flex items-center justify-center text-xs shadow-brutal-sm transition-all active:translate-x-[1px] active:translate-y-[1px] active:shadow-none">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if ($log->notes)
                            <p class="text-xs text-gray-600 bg-brutalist-bg border-2 border-brutalist-primary p-2.5 rounded-[6px] font-sans mt-2">
                                {{ $log->notes }}
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center bg-brutalist-bg rounded-[10px] border-4 border-dashed border-brutalist-primary">
                        <span class="text-5xl block mb-4">📚</span>
                        <p class="text-base font-bold">No learning logged.</p>
                        <p class="text-sm text-gray-500">Log a new session using the form on the left to start counting study hours.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
