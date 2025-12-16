<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BusPH') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F3F4F6]">
        
        {{-- WRAPPER: Forces the footer to the bottom --}}
        <div class="min-h-screen flex flex-col justify-between">
            
            {{-- 1. NAVIGATION (Fixed at top) --}}
            @include('layouts.navigation')

            {{-- 2. MAIN CONTENT (Grows to fill empty space) --}}
            <main class="flex-grow w-full">
                {{ $slot }}
            </main>

            {{-- 3. FOOTER (Always at the bottom) --}}
            <x-footer />
            
        </div>
    </body>
</html>