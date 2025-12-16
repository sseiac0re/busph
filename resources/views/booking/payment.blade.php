<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- PROGRESS BAR --}}
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center text-[#001233]">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#001233] text-white font-bold text-sm">1</span>
                    </div>
                    <div class="w-16 h-0.5 bg-[#001233]"></div>
                    <div class="flex items-center text-[#001233]">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#001233] text-white font-bold text-sm">2</span>
                    </div>
                    <div class="w-16 h-0.5 bg-[#001233]"></div>
                    <div class="flex items-center text-[#001233]">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#001233] text-white font-bold text-sm">3</span>
                        <span class="ml-2 font-bold text-sm">Payment</span>
                    </div>
                </div>
            </div>

            {{-- FORM START --}}
            <form action="{{ route('booking.process') }}" method="POST" id="paymentForm">
                @csrf
                
                {{-- HIDDEN DATA --}}
                <input type="hidden" name="schedule_id" value="{{ $data['schedule_id'] }}">
                <input type="hidden" name="seats" value="{{ $data['seats'] }}">
                <input type="hidden" name="trip_type" value="{{ $data['trip_type'] ?? 'one_way' }}">
                <input type="hidden" name="contact_phone" value="{{ $data['contact_phone'] }}">
                <input type="hidden" name="contact_email" value="{{ $data['contact_email'] }}">
                
                @if(isset($data['passengers']) && is_array($data['passengers']))
                    @php $seatList = explode(',', $data['seats']); @endphp
                    @foreach($data['passengers'] as $index => $passenger)
                        <input type="hidden" name="passengers[{{ $index }}][first_name]" value="{{ $passenger['first_name'] }}">
                        <input type="hidden" name="passengers[{{ $index }}][surname]" value="{{ $passenger['surname'] }}">
                        <input type="hidden" name="passengers[{{ $index }}][discount_id]" value="{{ $passenger['discount_id'] ?? '' }}">
                        {{-- ✅ CRITICAL FIX: Passing Type and Seat to prevent crash --}}
                        <input type="hidden" name="passengers[{{ $index }}][type]" value="{{ $passenger['type'] ?? 'adult' }}">
                        <input type="hidden" name="passengers[{{ $index }}][seat]" value="{{ $passenger['seat'] ?? ($seatList[$index] ?? '') }}">
                    @endforeach
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- LEFT: PAYMENT DETAILS (Interactive) --}}
                    <div class="lg:col-span-2 space-y-6" x-data="{ method: 'card' }">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-[#001233] mb-6">Payment Method</h2>
                            
                            {{-- OPTION 1: CREDIT CARD --}}
                            <div class="border rounded-lg mb-4 overflow-hidden transition-all duration-200"
                                 :class="method === 'card' ? 'border-[#001233] ring-1 ring-[#001233] bg-blue-50/10' : 'border-gray-200 hover:bg-gray-50'">
                                <label class="flex items-center p-4 cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" x-model="method" class="text-[#001233] focus:ring-[#001233]">
                                    <div class="ml-3 flex items-center gap-3">
                                        <span class="font-bold text-gray-800">Credit / Debit Card</span>
                                        <div class="flex gap-1">
                                            {{-- Visa --}}
                                            <div class="h-6 w-10">
                                                <svg class="h-full w-full" viewBox="0 0 48 32" fill="none">
                                                    <rect width="48" height="32" rx="2" fill="#1A1F71"/>
                                                    <path d="M19.7 7.7L18 25H15.2L17.2 7.7H19.7ZM28.6 7.7C27.9 7.7 26.6 8 26 9.4L22.2 25H19.2L24.8 7.7H28.6ZM34.5 10.9C34.4 10.6 33.7 9.9 32.3 9.9C30.6 9.9 29.3 10.8 29.3 12.6C29.3 14 30.5 14.7 31.4 15.1C32.4 15.6 32.7 15.9 32.7 16.4C32.7 17.2 31.7 17.5 30.9 17.5C29.6 17.5 28.8 17.1 28.3 16.9L27.8 19.3C28.4 19.6 29.6 20 31 20C34.6 20 35.6 18.2 35.6 16.2C35.6 14.4 34.3 13.7 33.2 13.2C32.1 12.6 31.9 12.3 31.9 11.8C31.9 11.2 32.6 10.9 33.3 10.9C33.9 10.9 34.9 11 35.7 11.4L36.3 8.9C35.8 8.7 35.1 8.5 34.5 8.5V10.9ZM42.6 7.7L40.2 19.4L39.3 15C38.9 13.2 38.8 12.4 38.3 10.7L37.2 7.7H34.3L37.7 25H40.7L45.8 7.7H42.6Z" fill="white"/>
                                                </svg>
                                            </div>
                                            {{-- Mastercard --}}
                                            <div class="h-6 w-10">
                                                <svg class="h-full w-full" viewBox="0 0 48 32" fill="none">
                                                    <rect width="48" height="32" rx="2" fill="#252525"/>
                                                    <circle cx="18" cy="16" r="9" fill="#EB001B"/>
                                                    <circle cx="30" cy="16" r="9" fill="#F79E1B" fill-opacity="0.9"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                {{-- Card Inputs (Only show if 'card' is selected) --}}
                                <div x-show="method === 'card'" x-transition class="p-4 border-t border-gray-200 bg-gray-50/50">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Card Number</label>
                                            <input type="text" id="card_number" 
                                            name="card_number" 
                                            :required="method === 'card'" 
                                            placeholder="0000 0000 0000 0000" 
                                            maxlength="19" 
                                            pattern="\d{4} \d{4} \d{4} \d{4}"
                                            oninput="formatCardNumber(this)"
                                            class="w-full rounded-lg border-gray-300 focus:ring-[#001233] focus:border-[#001233]">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Expiration</label>
                                                <input type="text"
                                                id="expiration_date" 
                                                name="expiration_date" 
                                                :required="method === 'card'" 
                                                placeholder="MM/YY" 
                                                maxlength="5" 
                                                pattern="(0[1-9]|1[0-2])\/\d{2}"
                                                title="Enter a valid expiration date (MM/YY)"
                                                oninput="formatExpiryDate(this)"
                                                class="w-full rounded-lg border-gray-300 focus:ring-[#001233] focus:border-[#001233]">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">CVC / CVV</label>
                                                <input type="text"
                                                id="cvv" 
                                                name="cvv" 
                                                :required="method === 'card'" 
                                                placeholder="123" 
                                                maxlength="3" 
                                                pattern="\d{3}"
                                                title="Enter the 3-digit security code"
                                                oninput="formatCVV(this)"
                                                class="w-full rounded-lg border-gray-300 focus:ring-[#001233] focus:border-[#001233]">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Cardholder Name</label>
                                            <input type="text"
                                            id="card_holder_name" 
                                            name="card_holder_name" 
                                            :required="method === 'card'" 
                                            placeholder="JUAN DELA CRUZ" 
                                            pattern="^[A-Z\s]+$"
                                            title="Name must be in ALL CAPS and contain letters only."
                                            style="text-transform: uppercase;" 
                                            oninput="formatName(this)"
                                            class="w-full rounded-lg border-gray-300 focus:ring-[#001233] focus:border-[#001233]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- OPTION 2: GCASH --}}
                            <div class="border rounded-lg mb-4 overflow-hidden transition-all duration-200"
                                 :class="method === 'gcash' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/10' : 'border-gray-200 hover:bg-gray-50'">
                                <label class="flex items-center p-4 cursor-pointer">
                                    <input type="radio" name="payment_method" value="gcash" x-model="method" class="text-blue-500 focus:ring-blue-500">
                                    <div class="ml-3 flex items-center gap-3">
                                        <span class="font-bold text-gray-800">GCash</span>
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-bold">E-Wallet</span>
                                    </div>
                                </label>
                                
                                <div x-show="method === 'gcash'" x-transition class="p-4 border-t border-gray-200 bg-gray-50/50">
                                    <p class="text-sm text-gray-600 mb-3">You will be redirected to GCash to complete your payment securely.</p>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">GCash Mobile Number</label>
                                        <input type="text" id="mobile_number" 
                                        :required="method === 'gcash'" 
                                        onkeydown="return event.key !== 'e' && event.key !== 'E'"
                                        placeholder="+63 9XX XXX XXXX"
                                        pattern="^\+639|09\d{9}$" 
                                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- OPTION 3: MAYA --}}
                            <div class="border rounded-lg mb-4 overflow-hidden transition-all duration-200"
                                 :class="method === 'maya' ? 'border-green-500 ring-1 ring-green-500 bg-green-50/10' : 'border-gray-200 hover:bg-gray-50'">
                                <label class="flex items-center p-4 cursor-pointer">
                                    <input type="radio" name="payment_method" value="maya" x-model="method" class="text-green-500 focus:ring-green-500">
                                    <div class="ml-3 flex items-center gap-3">
                                        <span class="font-bold text-gray-800">Maya</span>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-bold">E-Wallet</span>
                                    </div>
                                </label>
                                
                                <div x-show="method === 'maya'" x-transition class="p-4 border-t border-gray-200 bg-gray-50/50">
                                    <p class="text-sm text-gray-600 mb-3">Scan QR code or login to Maya to pay.</p>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Maya Account Number</label>
                                        <input type="text" id="mobile_number_maya" 
                                        :required="method === 'maya'" 
                                        onkeydown="return event.key !== 'e' && event.key !== 'E'"
                                        placeholder="+63 9XX XXX XXXX"
                                        pattern="^\+639|09\d{9}$" 
                                        class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: SUMMARY --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 sticky top-4">
                            <h3 class="text-xl font-bold text-[#001233] mb-6 border-b pb-4">Order Summary</h3>

                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Route</span>
                                    <span class="font-bold text-gray-900">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</span>
                                </div>
                                
                                {{-- DETAILED FARE BREAKDOWN --}}
                                <div class="border-t border-dashed border-gray-200 pt-4 mt-4">
                                    <p class="text-xs font-bold text-gray-500 uppercase mb-3">Fare Breakdown</p>
                                    
                                    @foreach($priceBreakdown as $item)
                                        <div class="flex justify-between items-start mb-3 text-sm">
                                            <div>
                                                <span class="block font-medium text-gray-800">
                                                    {{ $item['name'] }} 
                                                    <span class="text-xs text-gray-400">(Seat {{ $item['seat'] }})</span>
                                                </span>
                                                @if($item['is_discounted'])
                                                    <span class="bg-green-100 text-green-700 text-[10px] px-1.5 py-0.5 rounded font-bold">
                                                        20% DISCOUNT APPLIED
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                @if($item['is_discounted'])
                                                    <span class="block text-xs text-gray-400 line-through">₱ {{ number_format($item['original_price'], 2) }}</span>
                                                    <span class="block font-bold text-green-600">₱ {{ number_format($item['final_price'], 2) }}</span>
                                                @else
                                                    <span class="font-bold text-gray-900">₱ {{ number_format($item['final_price'], 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-gray-600 font-bold">Total Amount</span>
                                    <span class="text-3xl font-black text-[#001233]">₱ {{ number_format($totalPrice, 2) }}</span>
                                </div>

                                {{-- ACTION BUTTONS --}}
                                <div class="space-y-3">
                                    <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-lg font-bold hover:bg-green-700 transition shadow-lg text-lg flex justify-center items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Pay ₱ {{ number_format($totalPrice, 2) }}
                                    </button>

                                    <a href="{{ route('home') }}" class="block w-full bg-gray-100 text-gray-600 py-3 rounded-lg font-bold hover:bg-gray-200 transition text-center border border-gray-200">
                                        Cancel Transaction
                                    </a>
                                </div>
                                
                                <p class="text-xs text-center text-gray-400 mt-4">
                                    Secured by PayMongo. Your data is encrypted.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

{{-- SCRIPTS (Kept exactly as provided) --}}
<script>
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const btn = this.querySelector('button[type="submit"]');
        if (this.checkValidity()) {
            btn.disabled = true;
            btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing Payment...`;
            btn.classList.add('bg-gray-800', 'cursor-not-allowed');
        }
    });

    document.getElementById('mobile_number').addEventListener('input', function(e) {
        let input = e.target.value;
        e.target.value = input.replace(/[^0-9+]/g, ''); 
    });

    document.getElementById('mobile_number_maya').addEventListener('input', function(e) {
        let input = e.target.value;
        e.target.value = input.replace(/[^0-9+]/g, ''); 
    });

    function formatCardNumber(input) {
        let value = input.value.replace(/\D/g, '');
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) formattedValue += ' ';
            formattedValue += value[i];
        }
        input.value = formattedValue;
    }

    function formatExpiryDate(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length >= 2) value = value.substring(0, 2) + '/' + value.substring(2, 4);
        input.value = value;
    }

    function formatCVV(input) {
        input.value = input.value.replace(/\D/g, '');
    }

    function formatName(input) {
        let value = input.value.toUpperCase();
        value = value.replace(/[^A-Z\s]/g, '');
        input.value = value;
    }
</script>