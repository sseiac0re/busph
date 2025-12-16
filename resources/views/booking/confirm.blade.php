<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- PROGRESS BAR --}}
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center text-[#001233]">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#001233] text-white font-bold text-sm">1</span>
                        <span class="ml-2 font-medium text-sm hidden sm:block">Trip Details</span>
                    </div>
                    <div class="w-16 h-0.5 bg-[#001233]"></div>
                    <div class="flex items-center text-[#001233]">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#001233] text-white font-bold text-sm">2</span>
                        <span class="ml-2 font-medium text-sm hidden sm:block">Passenger Info</span>
                    </div>
                    <div class="w-16 h-0.5 bg-[#001233]"></div>
                    <div class="flex items-center text-[#001233]">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#001233] text-white font-bold text-sm">3</span>
                        <span class="ml-2 font-bold text-sm hidden sm:block">Confirm</span>
                    </div>
                </div>
            </div>

            {{-- FORM ACTION: Points to the final processing step --}}
            <form action="{{ route('booking.payment') }}" method="POST" id="confirm">
                @csrf
                
                {{-- HIDDEN INPUTS: PASS DATA TO NEXT STEP --}}
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                <input type="hidden" name="seats" value="{{ $validated['seats'] }}">
                <input type="hidden" name="contact_phone" value="{{ $validated['contact_phone'] }}">
                <input type="hidden" name="contact_email" value="{{ $validated['contact_email'] }}">
                
                {{-- ✅ NEW: Pass Trip Type & Return Date --}}
                <input type="hidden" name="trip_type" value="{{ $validated['trip_type'] ?? 'one_way' }}">
                <input type="hidden" name="return_date" value="{{ $validated['return_date'] ?? '' }}">

                {{-- Loop to pass passenger array data --}}
                @foreach($validated['passengers'] as $index => $p)
                    <input type="hidden" name="passengers[{{ $index }}][first_name]" value="{{ $p['first_name'] }}">
                    <input type="hidden" name="passengers[{{ $index }}][surname]" value="{{ $p['surname'] }}">
                    <input type="hidden" name="passengers[{{ $index }}][seat]" value="{{ $p['seat'] }}">
                    {{-- ✅ NEW: Pass the Passenger Type (Adult/Child) --}}
                    <input type="hidden" name="passengers[{{ $index }}][type]" value="{{ $p['type'] }}">
                    <input type="hidden" name="passengers[{{ $index }}][discount_id]" value="{{ $p['discount_id'] ?? '' }}">
                @endforeach

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- LEFT COLUMN: REVIEW DETAILS --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-[#001233] mb-4 border-b pb-2">Review Your Booking</h2>
                            
                            {{-- Contact Info --}}
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">Contact Email</p>
                                    <p class="font-medium text-gray-900">{{ $validated['contact_email'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">Contact Number</p>
                                    <p class="font-medium text-gray-900">{{ $validated['contact_phone'] }}</p>
                                </div>
                            </div>

                            {{-- Passenger List --}}
                            <h3 class="font-bold text-gray-700 mb-3">Passenger List</h3>
                            <div class="space-y-3">
                                {{-- We use $breakdown here because it has the calculated price --}}
                                @foreach($breakdown as $p)
                                    <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            {{-- Icon based on Type --}}
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ ($p['type'] ?? 'Adult') == 'Child' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ substr($p['type'] ?? 'A', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-[#001233]">
                                                    {{ $p['name'] }}
                                                </p>
                                                <p class="text-xs text-gray-500 flex items-center gap-2">
                                                    {{ $p['type'] ?? 'Adult' }} • Seat {{ $p['seat'] }}
                                                    @if(!empty($p['discount_id']))
                                                        <span class="text-amber-600 font-semibold">(ID: {{ $p['discount_id'] }})</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-900">₱ {{ number_format($p['price'], 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: TRIP SUMMARY --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 sticky top-8">
                            <h3 class="text-xl font-bold text-[#001233] mb-6 border-b pb-4">Trip Summary</h3>

                            {{-- 1. OUTBOUND TRIP (Only shows if this is the Return leg of a Round Trip) --}}
                            @if(session()->has('outbound_trip'))
                                @php
                                    $outboundData = session('outbound_trip');
                                    $outboundSchedule = \App\Models\Schedule::with(['route', 'bus'])->find($outboundData['schedule_id']);
                                @endphp
                                
                                @if($outboundSchedule)
                                    <div class="mb-6 border-b border-dashed border-gray-200 pb-6">
                                        <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide mb-2 inline-block">
                                            Trip 1: Outbound
                                        </span>
                                        <div class="mt-1 space-y-1">
                                            <p class="font-bold text-[#001233] text-lg leading-tight">
                                                {{ $outboundSchedule->route->origin }} 
                                                <span class="text-gray-400 text-sm">→</span> 
                                                {{ $outboundSchedule->route->destination }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($outboundSchedule->departure_time)->format('M d, Y • h:i A') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                Bus {{ $outboundSchedule->bus->code ?? 'N/A' }} • {{ $outboundSchedule->bus->type ?? 'Standard' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- 2. CURRENT TRIP (Return or One Way) --}}
                            <div class="mb-6">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="bg-[#001233] text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide inline-block">
                                        {{ session()->has('outbound_trip') ? 'Trip 2: Return' : 'Trip Details' }}
                                    </span>
                                    @if(request('trip_type') === 'round_trip' || session()->has('outbound_trip'))
                                        <span class="text-[10px] font-bold text-[#001233] bg-blue-50 px-2 py-1 rounded border border-blue-100">ROUND TRIP</span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded border border-gray-200">ONE WAY</span>
                                    @endif
                                </div>
                                
                                <div class="mt-1 space-y-1">
                                    <p class="font-bold text-[#001233] text-lg leading-tight">
                                        {{ $schedule->route->origin }} 
                                        <span class="text-gray-400 text-sm">→</span> 
                                        {{ $schedule->route->destination }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($schedule->departure_time)->format('M d, Y • h:i A') }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Bus {{ $schedule->bus->code ?? 'N/A' }} • {{ $schedule->bus->type ?? 'Standard' }}
                                    </p>
                                </div>
                            </div>

                            {{-- TOTAL AMOUNT --}}
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-gray-600 font-bold">Total Amount</span>
                                    {{-- The Total Price is usually passed from the controller as $totalPrice --}}
                                    <span class="text-3xl font-black text-[#001233]">₱ {{ number_format($totalPrice, 2) }}</span>
                                </div>

                                {{-- SUBMIT BUTTON --}}
                                <button type="submit" class="w-full bg-[#001233] text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-900 transition shadow-lg flex justify-center items-center gap-2">
                                    <span>Proceed to Payment</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                                
                                <div class="mt-4 text-center">
                                    <a href="{{ route('booking.seats', $schedule->id) }}" class="text-sm text-gray-500 hover:text-[#001233] underline">Go Back & Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


{{-- IMPORTANT!!! --}}
{{-- Remove this part when integrating a real payment gateway --}}
<script>
    document.getElementById('confirm').addEventListener('submit', function(e) {
        // Simple loading state
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = `Processing...`;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        setTimeout(() => {
        this.submit();
        }, 2000);
    });
</script>