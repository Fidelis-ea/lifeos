@extends('layouts.app')

@section('title', 'Achievements')

@section('content')
<!-- Header Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8 font-mono text-center">
    <div class="card-brutal" style="background-color: #FF7EB6;">
        <span class="block text-xs font-bold text-gray-700 uppercase">UNLOCKED / TOTAL</span>
        <span class="text-2xl lg:text-3xl font-headline font-extrabold" style="color: #111111;">{{ $unlockedCount }} / {{ $totalCount }}</span>
    </div>
    <div class="card-brutal" style="background-color: #FFD43B;">
        <span class="block text-xs font-bold text-gray-700 uppercase">CURRENT XP</span>
        <span class="text-2xl lg:text-3xl font-headline font-extrabold">{{ $totalXp }} XP</span>
    </div>
    <div class="card-brutal text-white" style="background-color: #111111;">
        <span class="block text-xs font-bold text-yellow-300 uppercase">CHARACTER LEVEL</span>
        <span class="text-2xl lg:text-3xl font-headline font-extrabold">LEVEL {{ $level }}</span>
    </div>
</div>

<!-- Unlocked Achievements -->
<div class="card-brutal bg-white mb-8">
    <h2 class="font-headline text-2xl font-bold mb-6 flex items-center gap-2">
        <span class="bg-brutalist-green px-2 py-0.5 border-2 border-brutalist-primary rounded text-sm">✓ UNLOCKED</span> Unlocked Achievements
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($unlocked as $ach)
            <div class="card-brutal bg-white relative hover:-translate-y-0.5 transition-transform flex flex-col justify-between" style="border-color: #111111">
                <div class="text-center py-4">
                    <span class="text-5xl block mb-3 animate-pulse">{{ $ach->icon }}</span>
                    <h3 class="font-headline text-lg font-bold leading-tight mb-1">{{ $ach->name }}</h3>
                    <p class="text-xs text-gray-500 font-sans px-2">{{ $ach->description }}</p>
                </div>
                <div class="pt-3 border-t border-dashed border-brutalist-primary text-center font-mono text-[9px] font-bold text-gray-400">
                    UNLOCKED AT {{ $ach->pivot->unlocked_at ? \Carbon\Carbon::parse($ach->pivot->unlocked_at)->format('Y-m-d') : today()->format('Y-m-d') }}
                </div>
            </div>
        @empty
            <p class="font-mono text-sm text-gray-500 col-span-full py-4 text-center">No achievements unlocked yet. Finish check-ins, streaks, or coding hours to unlock!</p>
        @endforelse
    </div>
</div>

<!-- Locked Achievements -->
<div class="card-brutal bg-white">
    <h2 class="font-headline text-2xl font-bold mb-6 flex items-center gap-2">
        <span class="bg-gray-200 px-2 py-0.5 border-2 border-brutalist-primary rounded text-sm">🔒 LOCKED</span> Locked Achievements
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($locked as $ach)
            <div class="card-brutal bg-gray-50 border-brutalist-primary opacity-60 relative flex flex-col justify-between">
                <!-- Lock overlay icon -->
                <div class="absolute right-3 top-3 text-xs bg-gray-200 border border-brutalist-primary px-1.5 py-0.5 rounded font-mono font-bold">
                    🔒 LOCK
                </div>
                
                <div class="text-center py-4">
                    <span class="text-5xl block mb-3 filter grayscale">{{ $ach->icon }}</span>
                    <h3 class="font-headline text-lg font-bold leading-tight mb-1">{{ $ach->name }}</h3>
                    <p class="text-xs text-gray-500 font-sans px-2">{{ $ach->description }}</p>
                </div>

                <div class="pt-3 border-t border-dashed border-brutalist-primary text-center font-mono text-[9px] font-bold text-gray-400">
                    REQ: {{ strtoupper(str_replace('_', ' ', $ach->requirement_type)) }} >= {{ $ach->requirement_value }}
                </div>
            </div>
        @empty
            <p class="font-mono text-sm text-gray-500 col-span-full py-4 text-center">Incredible! You have unlocked all achievements! 👑</p>
        @endforelse
    </div>
</div>
@endsection
