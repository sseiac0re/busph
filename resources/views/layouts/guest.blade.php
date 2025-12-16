<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'BusPH') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* 1. Entry Animation (Already there) */
            @keyframes driveIn {
                0% { opacity: 0; transform: translateX(-200px); }
                70% { transform: translateX(10px); }
                100% { opacity: 1; transform: translateX(0); }
            }

            /* 2. Loop Animation (Already there) */
            @keyframes busBounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-6px); }
            }

            /* 3. NEW: Exit Animation (Drive Away) */
            @keyframes driveOut {
                0% { transform: translateX(0) scale(1); opacity: 1; }
                20% { transform: translateX(-20px) scale(0.95); } /* Wind up / pullback effect */
                100% { transform: translateX(100vw) scale(1); opacity: 0; } /* Zoom off to right */
            }

            /* Classes */
            .bus-wrapper-entry {
                animation: driveIn 1.2s ease-out forwards;
            }

            .bus-image-running {
                animation: busBounce 0.6s ease-in-out infinite;
                filter: drop-shadow(0 10px 5px rgba(0,0,0,0.3));
            }

            /* NEW CLASS to trigger via JS */
            .bus-drive-away {
                animation: driveOut 1s ease-in forwards !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            
            <div class="hidden lg:flex lg:w-1/2 bg-[#001233] items-center justify-center">
                <div class="text-center p-10">
                    
                    <div id="bus-wrapper" class="bus-wrapper-entry inline-block">
                        <img src="{{ asset('images/logo.png') }}" 
                            alt="BusPH Logo" 
                            class="w-64 h-auto mx-auto bus-image-running">
                    </div>

                    <h1 class="text-white text-6xl font-bold mt-6">Welcome to BusPH</h1>
                    <p class="text-white mt-3 text-3xl">Ticket To Go</p>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#F8F9FA] px-8 py-12">
                <div class="w-full max-w-lg space-y-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>