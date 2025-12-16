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

        <div x-data="{ 
            error: null,
            serverError: @js($errors->get('name')->first()),
            validate(value) {
                if (!value || value.trim().length === 0) {
                    this.error = 'Name is required.';
                } else if (value.length > 255) {
                    this.error = 'Name must not exceed 255 characters.';
                } else {
                    const parts = value.split(',').map(p => p.trim());
                    if (parts.length < 2) {
                        this.error = 'Please follow the format: Surname, First Name Middle Name';
                    } else {
                        this.error = null;
                    }
                }
            },
            get displayError() {
                return this.error || this.serverError;
            }
        }">
            <label for="name" class="block text-lg text-[#001233] mb-1">Full Name:</label>
            <p class="text-xs text-[#001233] italic mb-2">Strictly follow the format Surname, First Name Middle Name</p>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus
                x-on:input="validate($event.target.value)"
                x-on:blur="validate($event.target.value)"
                :class="error ? 'border-red-500' : 'border-transparent'"
                class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" />
            <p x-show="displayError" x-text="error || serverError" class="text-sm text-red-600 dark:text-red-400 mt-2"></p>
        </div>

        <div x-data="{ 
            error: null,
            serverError: @js($errors->get('valid_id')->first()),
            validate(file) {
                if (!file) {
                    this.error = 'Please upload a valid ID.';
                } else {
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!allowedTypes.includes(file.type)) {
                        this.error = 'File must be JPG, PNG, or PDF format.';
                    } else if (file.size > 2048 * 1024) {
                        this.error = 'File size must not exceed 2MB.';
                    } else {
                        this.error = null;
                    }
                }
            },
            get displayError() {
                return this.error || this.serverError;
            }
        }">
            <label class="block text-lg text-[#001233] mb-1">Upload Valid ID:</label>
            <p class="text-xs text-[#001233] mb-2 leading-tight">
                Please upload one valid government-issued ID (e.g., Passport, UMID, Driver's License) in JPG, PNG, or PDF format.
            </p>
            
            <div class="relative">
                <input type="file" name="valid_id" id="valid_id" class="hidden" 
                    x-on:change="
                        const file = $event.target.files[0];
                        if (file) {
                            document.getElementById('file-label').innerText = file.name;
                            validate(file);
                        }
                    " />
                <label for="valid_id" 
                    :class="error ? 'border-red-500' : 'border-transparent'"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border cursor-pointer hover:bg-gray-200 transition focus-within:bg-white focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                    <span id="file-label" class="text-gray-500">Select file...</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#001233]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </label>
            </div>
            <p x-show="displayError" x-text="error || serverError" class="text-sm text-red-600 dark:text-red-400 mt-2"></p>
        </div>

        <div x-data="{ 
            error: null,
            serverError: @js($errors->get('email')->first()),
            validate(value) {
                if (!value || value.trim().length === 0) {
                    this.error = 'Email is required.';
                } else {
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|outlook|busph|email)\.com$/i;
                    if (!emailRegex.test(value)) {
                        this.error = 'Please enter a valid email address (gmail.com, yahoo.com, hotmail.com, outlook.com, busph.com, or email.com).';
                    } else {
                        this.error = null;
                    }
                }
            },
            get displayError() {
                return this.error || this.serverError;
            }
        }">
            <label for="email" class="block text-lg text-[#001233] mb-2">Email:</label>
            <input id="email" type="text" name="email" :value="old('email')" required
                x-on:input="validate($event.target.value)"
                x-on:blur="validate($event.target.value)"
                :class="error ? 'border-red-500' : 'border-transparent'"
                class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" />
            <p x-show="displayError" x-text="error || serverError" class="text-sm text-red-600 dark:text-red-400 mt-2"></p>
        </div>

        <div x-data="{ 
            show: false,
            error: null,
            serverError: @js($errors->get('password')->first()),
            validate(value) {
                if (!value || value.length === 0) {
                    this.error = 'Password is required.';
                } else if (value.length < 8) {
                    this.error = 'Password must be at least 8 characters.';
                } else {
                    this.error = null;
                }
            },
            get displayError() {
                return this.error || this.serverError;
            }
        }">
            <label for="password" class="block text-lg text-[#001233] mb-2">Password:</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                    x-on:input="validate($event.target.value)"
                    x-on:blur="validate($event.target.value)"
                    :class="error ? 'border-red-500' : 'border-transparent'"
                    class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition pr-12" />
                
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#001233] hover:text-blue-700 cursor-pointer"
                >
                    <!-- Show password icon -->
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

                    <!-- Hide password icon -->
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
            <p x-show="displayError" x-text="error || serverError" class="text-sm text-red-600 dark:text-red-400 mt-2"></p>
        </div>

        <div x-data="{ 
            show: false,
            error: null,
            serverError: @js($errors->get('password_confirmation')->first()),
            validate(value) {
                const passwordInput = document.querySelector('[name=password]');
                const passwordValue = passwordInput?.value || '';
                if (!value || value.length === 0) {
                    this.error = 'Please confirm your password.';
                } else if (passwordValue && value !== passwordValue) {
                    this.error = 'Passwords do not match.';
                } else {
                    this.error = null;
                }
            },
            get displayError() {
                return this.error || this.serverError;
            }
        }">
            <label for="password_confirmation" class="block text-lg text-[#001233] mb-2">Confirm Password:</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                    x-on:input="validate($event.target.value)"
                    x-on:blur="validate($event.target.value)"
                    :class="error ? 'border-red-500' : 'border-transparent'"
                    class="block w-full px-4 py-3 rounded-lg bg-[#F3F4F6] border focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition pr-12" />
                
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#001233] hover:text-blue-700 cursor-pointer"
                >
                    <!-- Show password icon -->
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

                    <!-- Hide password icon -->
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
            <p x-show="displayError" x-text="error || serverError" class="text-sm text-red-600 dark:text-red-400 mt-2"></p>
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