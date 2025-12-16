<x-app-layout>
    <div class="bg-white min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-10 border-b border-gray-200 pb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001233] mb-4">Terms and Conditions</h1>
                <p class="text-gray-500">Last Updated: December 14, 2025</p>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    Welcome to <strong>BusPH</strong>. By accessing our website, creating an account, or booking a ticket, you agree to be bound by these Terms and Conditions. Please read them carefully before using our services.
                </p>
            </div>

            {{-- Terms Content --}}
            <div class="space-y-12 text-gray-700">

                {{-- Section 1 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">1. Booking Policy</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Availability:</strong> All bookings are subject to seat availability at the time of reservation. A booking is not confirmed until full payment has been received and a Booking ID/Transaction ID has been generated.</li>
                        <li><strong>Accuracy of Information:</strong> You are responsible for ensuring that all details provided during booking (names, dates, routes) are correct. BusPH is not liable for errors made by the user during the booking process.</li>
                        <li><strong>Non-Transferability:</strong> Tickets are non-transferable unless explicitly authorized by BusPH management upon request.</li>
                    </ul>
                </section>

                {{-- Section 2 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">2. Payment and Pricing</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Currency:</strong> All transactions are processed in Philippine Peso (PHP).</li>
                        <li><strong>Payment Methods:</strong> We accept payments via Credit/Debit Card, GCash, and Maya. Full payment is required to secure your reservation.</li>
                        <li><strong>Price Changes:</strong> BusPH reserves the right to adjust ticket prices without prior notice. However, once a ticket is booked and paid for, the price is fixed and no additional charges will be applied.</li>
                        <li><strong>Discounts:</strong> Valid IDs (Student, Senior Citizen, PWD) must be presented at the terminal upon boarding. Failure to present a valid ID for a discounted ticket may result in the passenger paying the full fare difference or being denied boarding.</li>
                    </ul>
                </section>

                {{-- Section 3 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">3. Cancellations and Refunds</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Standard Cancellation:</strong> Requests made <strong>24 hours or more</strong> before the scheduled departure time are eligible for a refund, subject to a 10% processing fee.</li>
                        <li><strong>Late Cancellation:</strong> Requests made <strong>less than 24 hours</strong> before departure are considered non-refundable.</li>
                        <li><strong>Operator Cancellation:</strong> In the event that BusPH cancels a trip due to technical issues, safety concerns, or force majeure, passengers will be entitled to a <strong>full refund</strong> or a free rebooking.</li>
                        <li><strong>Refund Process:</strong> Approved refunds will be credited back to the original payment method within 5-10 business days.</li>
                    </ul>
                </section>

                {{-- Section 4 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">4. Boarding and Departure</h3>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>Check-in Time:</strong> Passengers must arrive at the terminal at least <strong>30 minutes</strong> before the scheduled departure time.</li>
                        <li><strong>No-Show Policy:</strong> Passengers who fail to arrive by the departure time will be considered "No-Show." The seat will be forfeited, and the ticket is non-refundable.</li>
                        <li><strong>Documentation:</strong> You must present your Electronic Ticket (QR Code) and a valid government-issued ID upon boarding.</li>
                    </ul>
                </section>

                {{-- Section 5 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">5. Baggage Policy</h3>
                    <p class="mb-2">Passengers are allowed:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li><strong>One (1) Hand Carry Bag:</strong> Maximum of 5kg, must fit in the overhead bin.</li>
                        <li><strong>One (1) Checked Luggage:</strong> Maximum of 15kg to be stored in the bus compartment.</li>
                    </ul>
                    <p class="mt-2 text-sm italic"><strong>Prohibited Items:</strong> Flammables, explosives, illegal drugs, firearms, foul-smelling items (e.g., durian, dried fish unless sealed properly), and live animals (except standard pets with carriers, subject to specific bus rules).</p>
                </section>

                {{-- Section 6 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">6. Passenger Conduct</h3>
                    <p>BusPH reserves the right to refuse carriage to any person who:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Is under the influence of alcohol or drugs.</li>
                        <li>Behaves in a disorderly, threatening, or abusive manner toward staff or other passengers.</li>
                        <li>Refuses to comply with safety regulations.</li>
                    </ul>
                </section>

                {{-- Section 7 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">7. Limitation of Liability</h3>
                    <p class="leading-relaxed">
                        BusPH shall not be liable for delays caused by traffic conditions, weather, road repairs, or other unforeseen circumstances. We are not responsible for loss or damage to hand-carried baggage inside the bus cabin. Liability for checked luggage is limited to the amount stipulated by the Land Transportation Franchising and Regulatory Board (LTFRB).
                    </p>
                </section>

                {{-- Section 8 --}}
                <section>
                    <h3 class="text-xl font-bold text-[#001233] mb-3">8. Changes to Terms</h3>
                    <p>
                        BusPH reserves the right to modify these Terms and Conditions at any time. Continued use of the website or our services constitutes acceptance of the revised terms.
                    </p>
                </section>

            </div>

            {{-- Footer Contact --}}
            <div class="mt-16 bg-gray-50 rounded-xl p-8 border border-gray-200 text-center">
                <p class="font-bold text-[#001233]">Questions about our Terms?</p>
                <p class="text-gray-600 mb-4">If you have any questions or concerns regarding this agreement, please contact us.</p>
                <a href="{{ route('contact') }}" class="inline-block bg-[#001233] text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-900 transition">
                    Contact Support
                </a>
            </div>

        </div>
    </div>
</x-app-layout>