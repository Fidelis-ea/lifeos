@extends('layouts.app')

@section('title', 'Timeline Log')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Log Form (Neubrutalist Card) -->
    <div class="space-y-8">
        <div class="card-brutal bg-brutalist-yellow">
            <h3 class="font-headline text-xl font-bold mb-4">➕ Log New Activity</h3>
            
            <form method="POST" action="{{ route('timeline.store') }}" class="space-y-4 font-mono text-sm">
                @csrf
                
                <div>
                    <label for="title" class="block font-bold mb-1">ACTIVITY TITLE</label>
                    <input type="text" name="title" id="title" required placeholder="e.g. Completed portfolio UI design" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 focus:outline-none focus:ring-0">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block font-bold mb-1">CATEGORY</label>
                        <select name="category" id="category" required class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 focus:outline-none focus:ring-0 bg-white">
                            @foreach ($categories as $key => $info)
                                <option value="{{ $key }}">{{ $info['icon'] }} {{ $info['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block font-bold mb-1">DATE</label>
                        <input type="date" name="date" id="date" required value="{{ today()->toDateString() }}" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 focus:outline-none focus:ring-0">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="duration_minutes" class="block font-bold mb-1">DURATION (MIN)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" placeholder="e.g. 60" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 focus:outline-none focus:ring-0">
                    </div>
                </div>

                <div>
                    <label for="description" class="block font-bold mb-1">DESCRIPTION (OPTIONAL)</label>
                    <textarea name="description" id="description" rows="3" placeholder="Add some notes..." class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 focus:outline-none focus:ring-0"></textarea>
                </div>

                <button type="submit" class="w-full py-3 btn-brutal-primary mt-2">
                    📢 ADD TO TIMELINE
                </button>
            </form>
        </div>

        <!-- Filter Card -->
        <div class="card-brutal bg-white">
            <h3 class="font-headline text-lg font-bold mb-4">🔍 Filters</h3>
            <form method="GET" action="{{ route('timeline.index') }}" class="space-y-4 font-mono text-xs">
                <div>
                    <label for="filter_category" class="block font-bold mb-1">CATEGORY</label>
                    <select name="category" id="filter_category" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2 bg-white">
                        <option value="">All Categories</option>
                        @foreach ($categories as $key => $info)
                            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $info['icon'] }} {{ $info['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="date_from" class="block font-bold mb-1">FROM</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                    <div>
                        <label for="date_to" class="block font-bold mb-1">TO</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full border-2 border-brutalist-primary rounded-[6px] p-2">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 py-2 btn-brutal-white">APPLY</button>
                    <a href="{{ route('timeline.index') }}" class="py-2 px-3 border-2 border-brutalist-primary rounded-[8px] font-bold hover:bg-gray-100 flex items-center justify-center">CLEAR</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Timeline Entries List -->
    <div class="md:col-span-2 space-y-6">
        <div class="card-brutal bg-white">
            <h2 class="font-headline text-2xl font-bold mb-6">Activity Timeline</h2>
            
            <div class="relative pl-8 border-l-4 border-brutalist-primary space-y-8">
                @forelse($entries as $entry)
                    <div class="relative">
                        <!-- Dot Indicator -->
                        <div class="absolute -left-[40px] top-1.5 w-6 h-6 rounded-full border-4 border-brutalist-primary flex items-center justify-center text-xs shadow-brutal-sm" style="background-color: {{ $entry->category_info['color'] }}">
                            {{ $entry->category_info['icon'] }}
                        </div>
                        
                        <div class="card-brutal bg-white p-4 relative hover:-translate-y-[2px] transition-transform">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <span class="font-mono text-[10px] font-bold text-gray-500">{{ $entry->date->format('M d, Y') }}</span>
                                    <h4 class="font-headline text-lg font-bold leading-tight">{{ $entry->title }}</h4>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if ($entry->duration_minutes)
                                        <span class="font-mono text-xs font-bold bg-gray-100 border-2 border-brutalist-primary px-2 py-0.5 rounded">
                                            ⏱️ {{ $entry->duration_formatted }}
                                        </span>
                                    @endif
                                    
                                    <!-- Delete Entry button -->
                                    <form method="POST" action="{{ route('timeline.destroy', $entry) }}" onsubmit="return confirm('Delete this activity entry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-6 h-6 border-2 border-brutalist-primary rounded bg-brutalist-red hover:bg-red-600 text-white font-bold flex items-center justify-center text-xs">
                                            &times;
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            @if ($entry->description)
                                <p class="text-sm text-gray-700 font-sans mt-2">{{ $entry->description }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center font-mono">
                        <span class="text-4xl mb-2 block">🤷‍♂️</span>
                        <p class="text-sm">No activities logged yet for the selected filter.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
