<x-app-layout>
    <div class="bg-white min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-10 border-b border-gray-200 pb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001233] mb-4">Privacy Policy</h1>
                <p class="text-gray-500">Last Updated: December 14, 2025</p>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    At <strong>BusPH</strong>, we value your privacy and are committed to protecting your personal data. This Privacy Policy explains how we collect, use, and safeguard your information when you use our booking platform.
                </p>
            </div>

            {{-- Policy Content --}}
            <div class="space-y-12 text-gray-700">

                {{-- Section 1 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">1. Information We Collect</h3>
                    <p class="mb-4">We collect information necessary to process your bookings and improve our services:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Personal Information:</strong> Name, email address, mobile number, and age/category (e.g., Student, Senior Citizen) for discount verification.</li>
                        <li><strong>Booking Details:</strong> Origin, destination, travel dates, seat preferences, and bus class choices.</li>
                        <li><strong>Payment Information:</strong> Transaction IDs and payment status. <em>Note: We do not store your credit card or sensitive banking details. All payments are processed via secure third-party gateways.</em></li>
                    </ul>
                </section>

                {{-- Section 2 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">2. How We Use Your Information</h3>
                    <p class="mb-2">Your data is used strictly for the following purposes:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Processing Bookings:</strong> To generate your tickets, reserve your seats, and communicate booking confirmations.</li>
                        <li><strong>Passenger Manifests:</strong> To comply with LTFRB and bus operator requirements for passenger lists.</li>
                        <li><strong>Customer Support:</strong> To assist you with cancellations, refunds, or inquiries.</li>
                        <li><strong>Updates:</strong> To notify you of schedule changes, delays, or platform maintenance.</li>
                    </ul>
                </section>

                {{-- Section 3 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">3. Data Sharing and Disclosure</h3>
                    <p class="mb-2">We do not sell your personal data. However, we may share your information with:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Bus Operators:</strong> Your name and seat number are shared with the specific bus company operating your trip for boarding verification.</li>
                        <li><strong>Legal Authorities:</strong> If required by law, court order, or government regulation.</li>
                    </ul>
                </section>

                {{-- Section 4 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">4. Data Security</h3>
                    <p class="leading-relaxed">
                        We implement industry-standard security measures, including encryption and secure server infrastructure, to protect your data from unauthorized access, alteration, or disclosure.
                    </p>
                </section>

                {{-- Section 5 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">5. Cookies and Tracking</h3>
                    <p class="leading-relaxed">
                        BusPH uses cookies to manage your login session and booking progress. These are essential for the website to function correctly. You may choose to disable cookies in your browser, but this may prevent you from making a booking.
                    </p>
                </section>

                {{-- Section 6 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">6. Your Rights</h3>
                    <p class="leading-relaxed mb-4">
                        You have the right to access, correct, or delete your personal information stored on our platform. You can manage your profile details directly through your Dashboard.
                    </p>
                    <p>
                        If you wish to delete your account permanently, please contact our support team.
                    </p>
                </section>

            </div>

            {{-- Footer Contact --}}
            <div class="mt-16 bg-blue-50 rounded-xl p-8 border border-blue-100 text-center">
                <p class="font-bold text-[#001233]">Have questions about your data?</p>
                <p class="text-gray-600 mb-4">Our Data Privacy Officer is here to assist you.</p>
                <a href="{{ route('contact') }}" class="inline-block bg-white text-[#001233] border border-gray-300 px-6 py-2 rounded-lg font-bold hover:bg-gray-50 transition">
                    Contact Support
                </a>
            </div>

        </div>
    </div>
</x-app-layout>