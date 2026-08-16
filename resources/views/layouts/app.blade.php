<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LifeOS') }}</title>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- ChartJS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-brutalist-bg text-brutalist-primary min-h-screen">
        <div x-data="{ sidebarOpen: false }">

            <!-- ===================== MOBILE OVERLAY ===================== -->
            <div
                x-show="sidebarOpen"
                x-transition.opacity
                style="display:none"
                class="fixed inset-0 bg-black/40 z-30 lg:hidden"
                @click="sidebarOpen = false">
            </div>

            <!-- ===================== SIDEBAR ===================== -->
            <aside
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="fixed top-0 left-0 w-72 h-screen bg-white border-r-4 border-brutalist-primary flex flex-col z-40 transition-transform duration-300 ease-in-out overflow-y-auto">

                <!-- Logo -->
                <div class="p-5 border-b-4 border-brutalist-primary flex items-center justify-between shrink-0">
                    <a href="{{ route('dashboard') }}" class="font-headline text-2xl font-extrabold tracking-tight flex items-center gap-2">
                        <span>⚡</span> LifeOS
                    </a>
                    <!-- Close button (mobile only) -->
                    <button @click="sidebarOpen = false" class="lg:hidden w-8 h-8 border-2 border-brutalist-primary rounded-[6px] bg-brutalist-bg font-bold flex items-center justify-center text-sm">
                        ✕
                    </button>
                </div>

                <!-- Nav links -->
                <nav class="flex-1 p-4 space-y-1.5 font-mono text-sm overflow-y-auto">
                    <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('dashboard') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">🏠</span> Dashboard
                    </a>
                    <a href="{{ route('checkin') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('checkin') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">✏️</span> Daily Check-in
                    </a>
                    <a href="{{ route('timeline.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('timeline.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">📅</span> Timeline
                    </a>
                    <a href="{{ route('habits.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('habits.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">🔥</span> Habits
                    </a>
                    <a href="{{ route('goals.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('goals.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">🎯</span> Goals
                    </a>
                    <a href="{{ route('skills.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('skills.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">🧠</span> Skills
                    </a>
                    <a href="{{ route('learning.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('learning.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">📚</span> Learning
                    </a>
                    <a href="{{ route('projects.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('projects.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">🛠️</span> Projects
                    </a>
                    <a href="{{ route('finance.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('finance.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">💰</span> Finance
                    </a>
                    <a href="{{ route('achievements.index') }}" @click="sidebarOpen = false" class="flex items-center gap-3 p-2.5 rounded-[8px] border-2 border-transparent hover:border-brutalist-primary hover:bg-brutalist-yellow transition-all duration-100 {{ request()->routeIs('achievements.index') ? 'bg-brutalist-yellow border-brutalist-primary font-bold shadow-brutal-sm' : '' }}">
                        <span class="text-lg w-6 text-center">🏆</span> Achievements
                    </a>
                </nav>

                <!-- Profile summary -->
                @auth
                <div class="p-4 border-t-4 border-brutalist-primary bg-brutalist-bg shrink-0">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full border-2 border-brutalist-primary bg-brutalist-pink font-bold flex items-center justify-center text-sm shadow-brutal-sm shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-bold text-sm truncate">{{ auth()->user()->name }}</h4>
                            <span class="text-[10px] font-mono uppercase bg-brutalist-primary text-white px-2 py-0.5 rounded-[4px]">
                                LVL {{ auth()->user()->level }}
                            </span>
                        </div>
                    </div>
                    <!-- XP Bar -->
                    <div class="w-full bg-white border-2 border-brutalist-primary h-4 rounded-[4px] overflow-hidden mb-3 relative">
                        <div class="bg-brutalist-green h-full" style="width: {{ (auth()->user()->xp % 1000) / 10 }}%"></div>
                        <span class="absolute inset-0 text-[9px] font-mono font-bold flex items-center justify-center text-brutalist-primary">
                            {{ auth()->user()->xp % 1000 }} / 1000 XP
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-white text-xs font-mono font-bold border-2 border-brutalist-primary rounded-[8px] hover:bg-brutalist-red hover:text-white transition-all shadow-brutal-sm active:translate-x-[2px] active:translate-y-[2px] active:shadow-none">
                            🚪 LOGOUT
                        </button>
                    </form>
                </div>
                @endauth
            </aside>

            <!-- ===================== MAIN CONTENT ===================== -->
            <div class="lg:pl-72 min-h-screen flex flex-col">

                <!-- Topbar -->
                <header class="h-16 lg:h-20 bg-white border-b-4 border-brutalist-primary flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Hamburger button (mobile only) -->
                        <button
                            @click="sidebarOpen = true"
                            class="lg:hidden shrink-0 w-9 h-9 border-2 border-brutalist-primary rounded-[6px] bg-brutalist-yellow font-bold flex items-center justify-center shadow-brutal-sm text-base">
                            ☰
                        </button>
                        <h2 class="font-headline text-lg lg:text-2xl font-bold tracking-tight truncate">
                            @yield('title', 'Welcome back!')
                        </h2>
                    </div>

                    <div class="flex items-center gap-2 lg:gap-4 shrink-0">
                        <span class="hidden sm:inline font-mono text-xs lg:text-sm bg-brutalist-bg border-2 border-brutalist-primary px-2 lg:px-3 py-1 lg:py-1.5 rounded-[6px] font-bold shadow-brutal-sm whitespace-nowrap">
                            📅 {{ today()->format('M d, Y') }}
                        </span>
                        <a href="{{ route('checkin') }}" class="btn-brutal bg-brutalist-yellow text-brutalist-primary text-xs px-3 lg:px-4 py-2 lg:py-2.5 shadow-brutal hover:shadow-brutal-md hover:-translate-x-[2px] hover:-translate-y-[2px] active:translate-x-[4px] active:translate-y-[4px] active:shadow-none whitespace-nowrap">
                            📝 <span class="hidden sm:inline">LOG </span>TODAY
                        </a>
                    </div>
                </header>

                <!-- Page content container -->
                <main class="p-4 sm:p-6 lg:p-8 w-full max-w-7xl mx-auto flex-1">
                    <!-- Flash messages -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-brutalist-green border-4 border-brutalist-primary rounded-[10px] shadow-brutal font-bold flex items-center justify-between">
                            <span>🎉 {{ session('success') }}</span>
                            <button onclick="this.parentElement.remove()" class="font-mono font-bold text-lg ml-4">&times;</button>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-brutalist-red border-4 border-brutalist-primary rounded-[10px] shadow-brutal font-bold text-white">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>⚠️ {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
