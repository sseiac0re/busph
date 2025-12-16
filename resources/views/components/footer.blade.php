<footer class="bg-[#001233] text-white py-8 mt-auto border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
        
        {{-- Column 1: Brand --}}
        <div>
            <div class="flex items-center justify-center md:justify-start gap-2 mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="BusPH" class="h-6 w-auto bg-white/10 p-1 rounded">
                <h4 class="font-bold text-lg">BusPH</h4>
            </div>
            <p class="text-gray-400 text-sm">
                Simplifying your travel across the Philippines. Book fast, travel safe, and arrive happy.
            </p>
        </div>

        {{-- Column 2: Quick Links --}}
        <div>
            <h4 class="font-bold text-lg mb-4 text-yellow-400">Quick Links</h4>
            <ul class="text-gray-400 text-sm space-y-2">
                <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact Us</a></li>
                <li><a href="{{ route('user.bookings.index') }}" class="hover:text-white transition">My Bookings</a></li>
            </ul>
        </div>

        {{-- Column 3: Support --}}
        <div>
            <h4 class="font-bold text-lg mb-4 text-yellow-400">Support</h4>
            <ul class="text-gray-400 text-sm space-y-2">
                <li><a href="{{ route('faq') }}" class="hover:text-white transition">Frequently Asked Questions</a></li>
                <li><a href="{{ route('terms') }}" class="hover:text-white transition">Terms & Conditions</a></li>
                <li><a href="{{ route('privacyPolicy') }}" class="hover:text-white transition">Privacy Policy</a></li>
            </ul>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs text-gray-500">
        <p>&copy; {{ date('Y') }} BusPH. All rights reserved.</p>
    </div>
</footer>