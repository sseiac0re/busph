<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
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
                        <span class="ml-2 font-bold text-sm">Passenger Info</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center text-gray-400">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-300 font-bold text-sm">3</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('booking.confirm') }}" method="POST" id="bookingForm">
                @csrf
                {{-- CURRENT TRIP DATA --}}
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                <input type="hidden" name="seats" value="{{ implode(',', $selectedSeats) }}">
                
                {{-- PASSED DATA --}}
                <input type="hidden" name="trip_type" value="{{ $request->trip_type }}">
                <input type="hidden" name="return_date" value="{{ $request->return_date }}">
                <input type="hidden" name="passengers_adult" value="{{ $request->passengers_adult }}">
                <input type="hidden" name="passengers_child" value="{{ $request->passengers_child }}">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- LEFT COLUMN: FORMS --}}
                    <div class="lg:col-span-2 space-y-8">
                        
                        {{-- 1. CONTACT DETAILS --}}
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-[#001233] mb-4">Contact Details</h2>
                            <p class="text-sm text-gray-500 mb-6">Your tickets will be sent to this email address.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Mobile Number*</label>
                                    <input type="text" id="mobile_number" name="contact_phone" 
                                           placeholder="+63 9XX XXX XXXX or 09XX XXX XXXX" required 
                                           class="w-full rounded-lg border-gray-300 focus:ring-[#001233] focus:border-[#001233]">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address*</label>
                                    <input type="email" name="contact_email" required 
                                           class="w-full rounded-lg border-gray-300 focus:ring-[#001233] focus:border-[#001233]">
                                </div>
                            </div>
                        </div>

                        {{-- 2. PASSENGER DETAILS --}}
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="text-lg font-bold text-[#001233] mb-4 border-b pb-2">Passenger Details</h3>
                            <p class="text-xs text-gray-400 mb-6">Names must match your valid ID.</p>

                            {{-- ADULTS --}}
                            @for ($i = 0; $i < $request->passengers_adult; $i++)
                                <div class="mb-6 border-b border-gray-100 pb-4 last:border-0">
                                    <h4 class="text-sm font-bold text-gray-500 uppercase mb-3 flex justify-between">
                                        <span>Adult Passenger {{ $i + 1 }}</span>
                                        <span class="text-xs text-gray-400 font-normal">Seat: {{ $selectedSeats[$i] }}</span>
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">First Name</label>
                                            <input type="text" name="passengers[{{ $i }}][first_name]" required 
                                                   oninput="validateName(this)"
                                                   style="text-transform: uppercase;"
                                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                            <input type="hidden" name="passengers[{{ $i }}][type]" value="adult">
                                            <input type="hidden" name="passengers[{{ $i }}][seat]" value="{{ $selectedSeats[$i] }}">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Surname</label>
                                            <input type="text" name="passengers[{{ $i }}][surname]" required 
                                                   oninput="validateName(this)"
                                                   style="text-transform: uppercase;"
                                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">
                                            Discount ID (Optional)
                                        </label>
                                        <input
                                            type="text"
                                            name="passengers[{{ $i }}][discount_id]"
                                            maxlength="9"
                                            pattern="\d{4}-\d{4}"
                                            title="Format: 1234-5678"
                                            placeholder="1234-5678"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-[#001233] focus:ring-[#001233]"
                                            oninput="formatDiscountId(this)"
                                        >
                                    </div>
                                </div>
                            @endfor

                            {{-- CHILDREN --}}
                            @php $seatOffset = $request->passengers_adult; @endphp
                            @for ($j = 0; $j < $request->passengers_child; $j++)
                                <div class="mb-6 border-b border-gray-100 pb-4 last:border-0">
                                    <h4 class="text-sm font-bold text-green-600 uppercase mb-3 flex justify-between items-center">
                                        <span>Child Passenger {{ $j + 1 }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-400 font-normal text-black">Seat: {{ $selectedSeats[$seatOffset + $j] }}</span>
                                            <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded-full font-bold">20% OFF</span>
                                        </div>
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">First Name</label>
                                            <input type="text" name="passengers[{{ $seatOffset + $j }}][first_name]" required 
                                                   oninput="validateName(this)"
                                                   style="text-transform: uppercase;"
                                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                            <input type="hidden" name="passengers[{{ $seatOffset + $j }}][type]" value="child">
                                            <input type="hidden" name="passengers[{{ $seatOffset + $j }}][seat]" value="{{ $selectedSeats[$seatOffset + $j] }}">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Surname</label>
                                            <input type="text" name="passengers[{{ $seatOffset + $j }}][surname]" required 
                                                   oninput="validateName(this)"
                                                   style="text-transform: uppercase;"
                                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: SUMMARY --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 sticky top-4">
                            <h3 class="text-xl font-bold text-[#001233] mb-6 border-b pb-4">Trip Summary</h3>
                            
                            {{-- 1. OUTBOUND TRIP (If exists) --}}
                            @if(isset($outboundSchedule) && $outboundSchedule)
                                <div class="mb-6 pb-6 border-b border-dashed border-gray-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded uppercase">Trip 1: Outbound</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-md font-bold text-gray-700">
                                        <span>{{ $outboundSchedule->route->origin }}</span>
                                        <span class="text-gray-400">&rarr;</span>
                                        <span>{{ $outboundSchedule->route->destination }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($outboundSchedule->departure_time)->format('M d • h:i A') }}
                                    </div>
                                    <div class="mt-2 text-xs text-gray-400 font-bold">
                                        Seats: {{ implode(', ', $outboundSeats ?? []) }}
                                    </div>
                                </div>
                            @endif

                            {{-- 2. CURRENT (RETURN) TRIP --}}
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold {{ isset($outboundSchedule) ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500' }} px-2 py-1 rounded uppercase">
                                        {{ isset($outboundSchedule) ? 'Trip 2: Return' : 'One Way Trip' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-lg font-bold text-[#001233]">
                                    <span>{{ $schedule->route->origin }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    <span>{{ $schedule->route->destination }}</span>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">
                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->format('M d, Y • h:i A') }}
                                </div>
                                <div class="mt-2">
                                    {{-- ✅ ADDED: CHANGE LINK HERE --}}
                                    <div class="flex justify-between items-end">
                                        <span class="text-xs font-bold text-gray-500">Selected Seats:</span>
                                        <a href="{{ route('booking.seats', $schedule->id) }}?trip_type={{ $request->trip_type }}&return_date={{ $request->return_date }}" 
                                           class="text-[10px] font-bold text-blue-600 hover:text-blue-800 hover:underline uppercase">
                                            Change
                                        </a>
                                    </div>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($selectedSeats as $seat)
                                            <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs font-bold border border-gray-200">{{ $seat }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- PRICE BREAKDOWN --}}
                            <div class="border-t pt-4 mt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600 font-bold">Grand Total</span>
                                    <span class="text-2xl font-black text-[#001233]">₱ {{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <p class="text-xs text-right text-gray-400 italic mb-4">Includes all passengers and trips</p>

                                <div class="flex gap-3">
                                    <a href="{{ route('home') }}" class="w-1/2 bg-red-50 text-red-700 py-3 rounded-lg font-bold hover:bg-red-100 transition text-center flex items-center justify-center">
                                        Cancel
                                    </a>
                                    <button type="submit" class="w-1/2 bg-[#001233] text-white py-3 rounded-lg font-bold hover:bg-blue-900 transition shadow-lg">
                                        Confirm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS: VALIDATION --}}
    <script>
        // 1. Mobile Number Validation
        document.getElementById('mobile_number').addEventListener('input', function (e) {
            let value = e.target.value;

            // Allow only digits and +
            value = value.replace(/[^0-9+]/g, '');

            // + only allowed at the start
            if (value.includes('+') && !value.startsWith('+')) {
                value = value.replace(/\+/g, '');
            }

            // Enforce prefixes logic
            // If user types '09', limit to 11 chars
            if (value.startsWith('09')) {
                if (value.length > 11) value = value.slice(0, 11);
            } 
            // If user types '+63', limit to 13 chars
            else if (value.startsWith('+63')) {
                if (value.length > 13) value = value.slice(0, 13);
            }
            // If user starts typing but it's not +63 or 09 yet, allow them to type (don't force delete immediately or they can't type +63)
            // But if they type something invalid like '1', '2', remove it
            else if (value.length > 0 && !value.startsWith('+') && !value.startsWith('0')) {
                 value = ''; // Invalid start char
            }

            e.target.value = value;
        });

        // 2. Name Validation (Letters & Spaces only, Uppercase)
        function validateName(input) {
                    input.value = input.value
                        .toUpperCase()
                        .replace(/[^A-Z\s]/g, ''); // Remove anything that is NOT A-Z or space
                }
                function formatDiscountId(input) {
            // Remove everything except numbers
            let value = input.value.replace(/\D/g, '');

            // Limit to 8 digits
            value = value.slice(0, 8);

            // Insert dash after 4 digits
            if (value.length > 4) {
                value = value.slice(0, 4) + '-' + value.slice(4);
            }

            input.value = value;
        }
    </script>
</x-app-layout>