<x-guest-layout>
    <div class="flex flex-col items-center">
        {{-- Logo --}}
        <div class="mb-8">
            <a href="/" class="flex items-center gap-2 text-[#001233] no-underline">
                <img src="{{ asset('images/logo.png') }}" class="block h-9 w-auto bg-white/10 p-1 rounded" alt="BusPH" />
                <span class="text-3xl font-black tracking-tighter">BusPH</span>
            </a>
        </div>

        {{-- Main Card --}}
        <div class="w-full sm:max-w-md bg-white px-8 py-10 shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100 relative">
            
            {{-- Decorative Header Bar --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-[#001233]"></div>

            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-[#001233] mb-2">Verify your email</h2>
                <p class="text-sm text-gray-500 leading-relaxed">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg border border-green-100 flex items-start gap-2">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</span>
                </div>
            @endif

            <div class="mt-8 space-y-4">
                {{-- Resend Button --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-[#001233] text-white font-bold rounded-xl shadow-lg hover:bg-blue-900 transition transform hover:-translate-y-0.5 uppercase text-xs tracking-widest">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-sm text-gray-400 hover:text-[#001233] font-bold transition underline decoration-gray-300 underline-offset-4 hover:decoration-[#001233]">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
        
        {{-- Footer Copyright --}}
        <div class="mt-8 text-center text-xs text-gray-400 font-bold uppercase tracking-widest">
            &copy; {{ date('Y') }} BusPH. All rights reserved.
        </div>
    </div>
</x-guest-layout>