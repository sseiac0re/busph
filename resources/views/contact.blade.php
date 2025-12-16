<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-[#001233]">Get in Touch</h2>
                <p class="mt-4 text-lg text-gray-500">We are here to help you 24/7.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Info Card --}}
                <div class="bg-[#001233] rounded-2xl shadow-xl p-10 text-white flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-6">Contact Information</h3>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <div><p class="font-bold">Phone</p><p class="text-blue-200">+63 912 345 6789</p></div>
                            </div>
                            <div class="flex items-start gap-4">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <div><p class="font-bold">Email</p><p class="text-blue-200">busph.help@gmail.com</p></div>
                            </div>
                            <div class="flex items-start gap-4">    
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <div><p class="font-bold">Location</p><p class="text-blue-200">Araneta Center Bus Terminal, QC</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Card --}}
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
    
                    {{-- SUCCESS / ERROR ALERTS --}}
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-lg border border-red-200">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        
                        {{-- Name --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#001233] focus:ring-[#001233] transition"
                                   placeholder="Your Name">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" pattern="^[a-zA-Z0-9._%+-]+@(gmail|yahoo|hotmail|outlook|busph|email)\.com$" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#001233] focus:ring-[#001233] transition"
                                   placeholder="email@example.com">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- ✅ ADDED: Subject Field --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#001233] focus:ring-[#001233] transition"
                                   placeholder="e.g. Lost Item, Refund, General Inquiry">
                            @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Message --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                            <textarea name="message" rows="5" required
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#001233] focus:ring-[#001233] transition"
                                      placeholder="How can we help?">{{ old('message') }}</textarea>
                            @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="w-full bg-[#001233] text-white font-bold py-4 rounded-lg shadow-lg hover:bg-blue-900 transition transform hover:scale-[1.02]">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>