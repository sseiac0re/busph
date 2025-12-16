<nav x-data="{ open: false }" class="bg-[#001233] border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/logo.png') }}" class="block h-9 w-auto bg-white/10 p-1 rounded" alt="BusPH" />
                        <span class="text-white font-bold text-xl tracking-tight hidden md:block">BusPH</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                        {{-- 1. DASHBOARD (Always Visible - Active when on User Dashboard) --}}
                        <x-nav-link :href="route('home')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white hover:border-gray-300 focus:text-white focus:border-gray-300">
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        {{-- 2. MY BOOKINGS --}}
                        <x-nav-link :href="route('user.bookings.index')" :active="request()->routeIs('user.bookings.*')" class="text-gray-300 hover:text-white hover:border-gray-300 focus:text-white focus:border-gray-300">
                            {{ __('My Bookings') }}
                        </x-nav-link>

                        {{-- 3. ABOUT US --}}
                        <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-gray-300 hover:text-white hover:border-gray-300 focus:text-white focus:border-gray-300">
                            {{ __('About Us') }}
                        </x-nav-link>
                        
                        {{-- 4. CONTACT --}}
                        <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-gray-300 hover:text-white hover:border-gray-300 focus:text-white focus:border-gray-300">
                            {{ __('Contact') }}
                        </x-nav-link>

                        {{-- 5. FAQ --}}
                        <x-nav-link :href="route('faq')" :active="request()->routeIs('faq')" class="text-gray-300 hover:text-white hover:border-gray-300 focus:text-white focus:border-gray-300">
                            {{ __('FAQ') }}
                        </x-nav-link>

                        {{-- 6. ADMIN PANEL SHORTCUT (Only for Admins - Yellow Text) --}}
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-yellow-400 hover:text-yellow-300 hover:border-yellow-400 focus:outline-none transition duration-150 ease-in-out">
                                {{ __('Admin Panel') }}
                            </a>
                        @endif
                    @else
                        {{-- LINKS FOR GUESTS --}}
                        <x-nav-link :href="route('home')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white hover:border-gray-300">
                            {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-gray-300 hover:text-white hover:border-gray-300">
                            {{ __('About Us') }}
                        </x-nav-link>
                        <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-gray-300 hover:text-white hover:border-gray-300">
                            {{ __('Contact') }}
                        </x-nav-link>
                        <x-nav-link :href="route('faq')" :active="request()->routeIs('faq')" class="text-gray-300 hover:text-white hover:border-gray-300">
                            {{ __('FAQ') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-[#001233] hover:text-white focus:outline-none transition ease-in-out duration-150">
                                <div class="font-bold">{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="text-sm bg-white text-[#001233] px-4 py-2 rounded font-bold hover:bg-gray-100 transition">Register</a>
                    </div>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#0a1e45] border-t border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.bookings.index')" :active="request()->routeIs('user.bookings.*')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('My Bookings') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('About Us') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('Contact') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('faq')" :active="request()->routeIs('faq')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('FAQ') }}
                </x-responsive-nav-link>

                @if(Auth::user()->role === 'admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-yellow-400 hover:text-yellow-300 hover:bg-white/10 border-transparent font-bold">
                        {{ __('Admin Panel') }}
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('About Us') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('Contact') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('faq')" :active="request()->routeIs('faq')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('FAQ') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')" class="text-gray-300 hover:text-white hover:bg-white/10 border-transparent">
                    {{ __('Login') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-700">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300 hover:text-white hover:bg-white/10">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" class="text-gray-300 hover:text-white hover:bg-white/10"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>