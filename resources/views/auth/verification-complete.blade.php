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
                <h2 class="text-2xl font-bold text-[#001233] mb-2">Account Verified</h2>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Your email has been successfully verified. You can now log in using your credentials.
                </p>
            </div>

            <div class="mt-6 flex flex-col items-center gap-4">
                {{-- Loading / redirect animation --}}
                <div class="flex flex-col items-center gap-3">
                    <div class="w-10 h-10 border-4 border-[#001233]/20 border-t-[#001233] rounded-full animate-spin"></div>
                    <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase">
                        Redirecting you to the login page...
                    </p>
                </div>

                <a href="{{ route('login') }}"
                   class="mt-2 inline-flex items-center justify-center w-full py-3.5 bg-[#001233] text-white font-bold rounded-xl shadow-lg hover:bg-blue-900 transition text-xs tracking-widest uppercase">
                    Go to Login Now
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 text-center text-xs text-gray-400 font-bold uppercase tracking-widest">
            &copy; {{ date('Y') }} BusPH. All rights reserved.
        </div>
    </div>

    {{-- Auto redirect to login after a short delay --}}
    <script>
        setTimeout(function () {
            window.location.href = "{{ route('login') }}";
        }, 4000);
    </script>
</x-guest-layout>


