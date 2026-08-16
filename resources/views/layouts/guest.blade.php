<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LifeOS') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-brutalist-primary antialiased bg-brutalist-bg">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="font-headline text-5xl font-extrabold tracking-tight flex items-center gap-2 drop-shadow-md">
                    <span>⚡</span> LifeOS
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white border-4 border-brutalist-primary shadow-brutal-xl rounded-[10px]">
                {{ $slot }}
            </div>
            
            <p class="mt-6 font-mono text-[10px] text-gray-500 font-bold uppercase tracking-widest text-center">
                Track your life. Build your habits. Achieve your goals.
            </p>
        </div>
    </body>
</html>
