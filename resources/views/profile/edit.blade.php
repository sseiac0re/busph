<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="mb-8 flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-[#001233] flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-black text-[#001233]">Account Settings</h1>
                    <p class="text-gray-500">Manage your profile and security preferences.</p>
                </div>
            </div>

            <div class="space-y-8">
                
                {{-- 1. UPDATE PROFILE INFORMATION --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="p-2 bg-blue-50 rounded-lg text-[#001233]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#001233]">Profile Information</h2>
                            <p class="text-sm text-gray-500">Update your account's profile information and email address.</p>
                        </div>
                    </div>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Full Name</label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" 
                                           class="w-full pl-4 pr-4 py-3 rounded-xl border-gray-200 focus:border-[#001233] focus:ring-[#001233] transition shadow-sm font-semibold text-gray-700 placeholder-gray-300">
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Email Address</label>
                                <div class="relative">
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" 
                                           class="w-full pl-4 pr-4 py-3 rounded-xl border-gray-200 focus:border-[#001233] focus:ring-[#001233] transition shadow-sm font-semibold text-gray-700 placeholder-gray-300">
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-2 text-sm text-amber-600 bg-amber-50 p-3 rounded-lg border border-amber-100 flex items-start gap-2">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <div>
                                            <p class="font-bold">Your email is unverified.</p>
                                            <button form="send-verification" class="underline text-amber-800 hover:text-amber-900">Click here to re-send the verification email.</button>
                                        </div>
                                    </div>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-[#001233] text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-900 transition shadow-md flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Save Changes
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Saved.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- 2. UPDATE PASSWORD --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="p-2 bg-blue-50 rounded-lg text-[#001233]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[#001233]">Update Password</h2>
                            <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                        </div>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6" id="passwordForm">
                        @csrf
                        @method('put')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Current Password --}}
                            <div x-data="{ show: false }">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Current Password</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="current_password" required autocomplete="current-password" 
                                           class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 focus:border-[#001233] focus:ring-[#001233] transition shadow-sm font-semibold text-gray-700 placeholder-gray-300">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.575-3.125M17.657 17.657a3 3 0 00-4.243 0m4.242-4.242l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            {{-- New Password --}}
                            <div x-data="{ show: false }">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">New Password</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" id="new_password" required autocomplete="new-password" minlength="8"
                                           class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 focus:border-[#001233] focus:ring-[#001233] transition shadow-sm font-semibold text-gray-700 placeholder-gray-300">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.575-3.125M17.657 17.657a3 3 0 00-4.243 0m4.242-4.242l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            {{-- Confirm Password --}}
                            <div x-data="{ show: false }">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Confirm Password</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password_confirmation" id="confirm_password" required autocomplete="new-password" 
                                           class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-200 focus:border-[#001233] focus:ring-[#001233] transition shadow-sm font-semibold text-gray-700 placeholder-gray-300">
                                    <button type="button" @click="show = !show" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.575-3.125M17.657 17.657a3 3 0 00-4.243 0m4.242-4.242l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-[#001233] text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-900 transition shadow-md flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Update Password
                            </button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Password Updated.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- 3. DELETE ACCOUNT (Optional Safe Zone) --}}
                <div class="bg-red-50 p-8 rounded-2xl shadow-sm border border-red-100" x-data="{ showDelete: false }">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-100 rounded-lg text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-red-800">Delete Account</h2>
                                <p class="text-sm text-red-600">Permanently delete your account and all data.</p>
                            </div>
                        </div>
                        <button @click="showDelete = !showDelete" class="text-red-600 font-bold hover:bg-red-100 px-4 py-2 rounded-lg transition">
                            <span x-text="showDelete ? 'Cancel' : 'Delete Account'"></span>
                        </button>
                    </div>

                    <div x-show="showDelete" x-transition class="mt-6 pt-6 border-t border-red-200">
                        <p class="text-sm text-gray-600 mb-4">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Confirm Password</label>
                                <input type="password" name="password" required placeholder="Enter your password to confirm"
                                       class="w-full max-w-md pl-4 pr-4 py-3 rounded-xl border-gray-200 focus:border-red-500 focus:ring-red-500 transition shadow-sm">
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                            </div>

                            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-md flex items-center gap-2" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
                                Yes, Delete My Account
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- CLIENT SIDE VALIDATION SCRIPT --}}
    <script>
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;

            if (newPass.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return;
            }

            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('New Password and Confirm Password do not match.');
            }
        });
    </script>
</x-app-layout>