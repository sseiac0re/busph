<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BusPH Admin') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        
        <aside class="w-64 bg-[#001233] text-white flex flex-col fixed h-full shadow-xl z-50">
            <div class="h-16 flex items-center justify-center border-b border-gray-800 shadow-sm">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="BusPH" class="h-8 w-auto">
                    <span class="font-bold text-xl tracking-tight">AdminPanel</span>
                </a>
            </div>

            <nav class="flex-grow py-6 px-4 space-y-2 overflow-y-auto">
                <div class="px-6 pt-6 pb-4">
                    <a href="{{ route('home') }}" class="flex items-center justify-center w-full bg-white/10 hover:bg-white/20 text-white font-bold py-2 px-4 rounded-lg transition border border-white/10 mb-6">
                        {{-- Arrow Icon --}}
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Go to Website
                    </a>
                </div>

                <p class="px-4 text-xs font-bold text-white-500 uppercase tracking-wider mt-6 mb-2">Main</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-900 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <p class="px-4 text-xs font-bold text-yellow-500 uppercase tracking-wider mt-6 mb-2">Management</p>

                <a href="{{ route('admin.buses.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.buses*') ? 'bg-gray-800 text-white border-r-4 border-yellow-400' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Buses
                </a>

                <a href="{{ route('admin.routes.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.routes*') ? 'bg-gray-800 text-white border-r-4 border-yellow-400' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Routes
                </a>

                <a href="{{ route('admin.schedules.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.schedules*') ? 'bg-gray-800 text-white border-r-4 border-yellow-400' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Schedules
                </a>

                <a href="{{ route('admin.reservations.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.reservations*') ? 'bg-gray-800 text-white border-r-4 border-yellow-400' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Reservations
                </a>

                <a href="{{ route('admin.templates.index') }}" class="flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.templates.*') ? 'bg-gray-800 text-white border-r-4 border-yellow-400' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Automation Rules
                </a>

                <a href="{{ route('admin.cancellations.index') }}" 
                    class="flex items-center px-4 py-3 rounded-lg transition 
                            {{ request()->routeIs('admin.cancellations.index') ? 'bg-gray-800 text-white border-r-4 border-yellow-400' : '' }}">
                        
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V7m0 8h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ __('Cancellations') }}</span>
                            </div>
                            
                            @if(\App\Models\Reservation::where('cancellation_status', 'pending')->count() > 0)
                                <span class="ml-2 px-2 py-0.5 text-xs font-bold bg-red-600 text-white rounded-full flex-shrink-0">
                                    {{\App\Models\Reservation::where('cancellation_status', 'pending')->count()}}
                                </span>
                            @endif
                        </div>
                </a>

                <p class="px-4 text-xs font-bold text-green-500 uppercase tracking-wider mt-6 mb-2">Tools</p>
                <a href="{{ route('admin.verify') }}" 
                    class="flex items-center px-4 py-3 rounded-lg transition 
                            {{ request()->routeIs('admin.verify') ? 'bg-gray-800 text-white border-r-4 border-green-400' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    <span class="ml-3 font-medium">Verify Ticket</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold text-white shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-400 transition" title="Log Out">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 ml-64 flex flex-col min-h-screen transition-all duration-300">
            
            <main class="flex-grow p-8">
                <header class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $header ?? 'Dashboard' }}
                        </h2>
                    </div>
                    <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                        Today is <span class="font-bold text-[#001233]">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</span>
                    </div>
                </header>

                {{ $slot }}
            </main>

            <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
                <div class="max-w-7xl mx-auto px-8 flex justify-between items-center text-sm text-gray-500">
                    <p>&copy; 2025 BusPH Admin Panel. All rights reserved.</p>
                    <p class="hidden md:block">System Version Dev-1.0</p>
                </div>
            </footer>

        </div>

    </div>
</body>
</html>