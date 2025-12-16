<x-guest-layout>
    <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#001233] mb-6 transition">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    Back to Trip Search
    </a>
    <div class="mb-10">
        <h2 class="text-3xl font-normal text-[#001233] mb-2">Login to your User Account</h2>
        <div class="h-0.5 w-full bg-[#001233]"></div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-lg text-[#001233] mb-2">Email:</label>
            <input id="email" pattern="^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|outlook|busph|email)\.com$" type="email" name="email" :value="old('email')" required autofocus
                class="block w-full px-4 py-3.5 rounded-xl bg-[#F0F2F5] border-transparent focus:border-[#001233] focus:bg-white focus:ring-0 transition shadow-sm" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="block text-lg text-[#001233] mb-2">Password:</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
                    class="block w-full px-4 py-3.5 rounded-xl bg-[#F0F2F5] border-transparent focus:border-[#001233] focus:bg-white focus:ring-0 transition shadow-sm pr-12" />
                
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#001233] hover:text-blue-700 cursor-pointer"
                >
                    <!-- Show password -->
                    <svg
                        x-show="!show"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 stroke-current"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.064 7-9.542 7
                            -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    <!-- Hide password -->
                    <svg
                        x-show="show"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 stroke-current"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19
                            c-4.478 0-8.268-2.943-9.542-7
                            a10.05 10.05 0 011.557-3.269" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.702 6.702A9.956 9.956 0 0112 5
                            c4.478 0 8.268 2.943 9.542 7
                            a9.956 9.956 0 01-4.043 5.14" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end">
            @if (Route::has('password.request'))
                <a class="text-sm text-[#001233] italic hover:underline" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-[#001233] text-white font-bold rounded-lg hover:bg-opacity-90 transition shadow-lg tracking-wide text-lg">
            LOGIN
        </button>

        <div class="text-center text-sm text-[#001233] pt-4">
            No account yet?
            <a href="{{ route('register') }}" class="font-bold border-b border-[#001233] pb-0.5 hover:text-blue-800">Create Account</a>
        </div>
    </form>
</x-guest-layout>