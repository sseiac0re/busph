<x-guest-layout>
    {{-- 
      WRAPPER: 
      We use 'relative' so we can position the back button if needed, 
      though simple margin usually works better in centered layouts.
    --}}
    <div class="w-full relative">

        {{-- BACK BUTTON --}}
        {{-- Changed from absolute to a flex block to ensure it doesn't overlap on small screens --}}
        <div class="mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#001233] transition group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Trip Search
            </a>
        </div>

        {{-- MOBILE LOGO (Visible only on small screens) --}}
        <div class="lg:hidden text-center mb-8">
            <h2 class="text-3xl font-black text-[#001233]">BusPH</h2>
        </div>

        {{-- HEADING --}}
        <h2 class="text-3xl font-bold text-[#001233] mb-2">Forgot Password?</h2>
        <p class="text-gray-500 text-sm mb-8 leading-relaxed">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>

        {{-- SESSION STATUS --}}
        @if (session('status'))
            <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-lg border border-green-100 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- Email Address --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                <input id="email" pattern="^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|outlook|busph|email)\.com$" type="email" name="email" value="{{ old('email') }}" required autofocus 
                       class="w-full px-4 py-3.5 rounded-lg bg-white border border-gray-200 focus:border-[#001233] focus:ring-1 focus:ring-[#001233] outline-none transition font-medium text-gray-900" 
                       placeholder="email@example.com">
                @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full py-4 bg-[#001233] hover:bg-blue-900 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 uppercase tracking-widest text-xs">
                {{ __('Email Password Reset Link') }}
            </button>
        </form>
        
        {{-- Login Link --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">Remember your password? <a href="{{ route('login') }}" class="font-bold text-[#001233] hover:underline">Log in</a></p>
        </div>
    </div>
</x-guest-layout>