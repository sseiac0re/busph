<x-guest-layout>
        <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#001233] mb-6 transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Back to Trip Search
        </a>
    <div class="mb-8">
        <h2 class="text-3xl font-normal text-[#001233] mb-2">Create your BusPH Account</h2>
        <div class="h-0.5 w-full bg-[#001233]"></div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="name" class="block text-lg text-[#001233] mb-1">Full Name:</label>
            <p class="text-xs text-[#001233] italic mb-2">Strictly follow the format Surname, First Name Middle Name</p>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus
                class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border border-transparent focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <label class="block text-lg text-[#001233] mb-1">Upload Valid ID:</label>
            <p class="text-xs text-[#001233] mb-2 leading-tight">
                Please upload one valid government-issued ID (e.g., Passport, UMID, Driver's License) in JPG, PNG, or PDF format.
            </p>
            
            <div class="relative">
                <input type="file" name="valid_id" id="valid_id" class="hidden" onchange="document.getElementById('file-label').innerText = this.files[0].name" />
                <label for="valid_id" class="flex items-center justify-between w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border border-transparent cursor-pointer hover:bg-gray-200 transition focus-within:bg-white focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                    <span id="file-label" class="text-gray-500">Select file...</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#001233]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </label>
            </div>
            <x-input-error :messages="$errors->get('valid_id')" class="mt-2" />
        </div>

        <div>
            <label for="email" class="block text-lg text-[#001233] mb-2">Email:</label>
            <input id="email" pattern="^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|outlook|busph|email)\.com$" type="email" name="email" :value="old('email')" required
                class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border border-transparent focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label for="password" class="block text-lg text-[#001233] mb-2">Password:</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border border-transparent focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-lg text-[#001233] mb-2">Confirm Password:</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border border-transparent focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" />
        </div>

        <div class="flex items-start pt-2">
            <div class="flex items-center h-6">
                <input id="consent" name="consent" type="checkbox" required
                    class="h-5 w-5 text-[#001233] border-2 border-gray-400 rounded focus:ring-[#001233] checked:bg-[#001233]" />
            </div>
            <div class="ml-3 text-sm leading-tight">
                <label for="consent" class="text-[#001233]">
                    By proceeding, I consent to the collection and processing of my personal information under the Data Privacy Act of 2012.
                </label>
            </div>
        </div>

        <button type="submit" class="w-full py-3.5 px-4 bg-[#001233] text-white font-bold rounded-lg hover:bg-opacity-90 transition shadow-lg tracking-wide text-lg mt-4">
            CREATE ACCOUNT
        </button>
    </form>
</x-guest-layout>