<x-app-layout>
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            
            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-[#001233]">Booking Details</h2>
                <span class="text-gray-500 text-sm">Today is {{ now()->format('l, F d, Y') }}</span>
            </div>

            <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#001233] mb-6 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to List
            </a>

            {{-- MAIN CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                {{-- STATUS BANNER --}}
                <div class="px-8 py-6 bg-[#001233] text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold">Booking #{{ $reservation->id }}</h3>
                        <p class="text-blue-200 text-sm">Booked on {{ $reservation->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <span class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $reservation->status === 'confirmed' ? 'bg-green-500 text-white' : '' }}
                            {{ $reservation->status === 'cancelled' ? 'bg-red-500 text-white' : '' }}
                            {{ $reservation->status === 'pending' ? 'bg-yellow-500 text-white' : '' }}">
                            {{ $reservation->status }}
                        </span>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    
                    {{-- 1. PASSENGER INFO (Added Discount ID Here) --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Passenger Information</h4>
                        <div class="bg-gray-50 rounded-xl p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">Full Name</p>
                                <p class="text-gray-900 font-bold text-lg">{{ $reservation->passenger_name ?? $reservation->first_name . ' ' . $reservation->surname }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">Email Address</p>
                                <p class="text-gray-900 font-medium">{{ $reservation->email ?? 'N/A' }}</p>
                            </div>
                            
                            {{-- NEW: DISCOUNT ID DISPLAY --}}
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase">Discount ID / PWD</p>
                                @if($reservation->discount_id_number)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $reservation->discount_id_number }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">None</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- 2. TRIP INFO --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Trip Information</h4>
                        <div class="border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center gap-4 mb-6">
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Route</p>
                                    <div class="flex items-center gap-3 text-xl font-bold text-[#001233]">
                                        <span>{{ $reservation->schedule->route->origin }}</span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        <span>{{ $reservation->schedule->route->destination }}</span>
                                    </div>
                                </div>
                                <div class="h-10 w-px bg-gray-200 mx-4"></div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Departure</p>
                                    <p class="text-xl font-bold text-[#001233]">
                                        {{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('F d, Y') }} 
                                        <span class="text-gray-400 mx-1">|</span> 
                                        {{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase">Bus Number</p>
                                    <p class="font-bold text-gray-700">{{ $reservation->schedule->bus->bus_number }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <p class="text-[10px] text-gray-500 font-bold uppercase">Bus Type</p>
                                    <p class="font-bold text-gray-700">{{ $reservation->schedule->bus->type }}</p>
                                </div>
                                <div class="bg-[#001233] p-3 rounded-lg text-white">
                                    <p class="text-[10px] text-blue-200 font-bold uppercase">Seat Number</p>
                                    <p class="font-bold text-2xl">{{ $reservation->seat_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. PAYMENT LOGIC (Updated for Discounts) --}}
                    @php
                        $basePrice = $reservation->schedule->route->price;
                        $finalPrice = $basePrice;
                        $hasDiscount = !empty($reservation->discount_id_number);
                        
                        if ($hasDiscount) {
                            $finalPrice = $basePrice * 0.80; // 20% Discount
                        }
                    @endphp

                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Payment Details</h4>
                        <div class="bg-gray-50 rounded-xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            
                            {{-- Left: Transaction Info --}}
                            <div class="space-y-1">
                                <p class="text-xs text-gray-500 font-bold uppercase">Transaction Reference</p>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-gray-800">{{ $reservation->transaction_id ?? $reservation->id }}</span>
                                    <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded">VERIFIED</span>
                                </div>
                                <div class="pt-2">
                                    <p class="text-xs text-gray-500 font-bold uppercase">Payment Method</p>
                                    <p class="font-bold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        {{ $reservation->payment_method ?? 'SecurePay / Credit Card' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Right: Price Calculation --}}
                            <div class="w-full md:w-64 bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="flex justify-between mb-2 text-sm">
                                    <span class="text-gray-500">Base Fare</span>
                                    <span class="font-bold text-gray-900">₱ {{ number_format($basePrice, 2) }}</span>
                                </div>

                                {{-- SHOW DISCOUNT ROW IF APPLICABLE --}}
                                @if($hasDiscount)
                                    <div class="flex justify-between mb-2 text-sm text-green-600">
                                        <span class="font-bold">PWD/Senior (20%)</span>
                                        <span class="font-bold">- ₱ {{ number_format($basePrice * 0.20, 2) }}</span>
                                    </div>
                                @endif

                                <div class="border-t border-dashed border-gray-200 my-2"></div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold uppercase text-gray-400">Total Paid</span>
                                    <span class="text-2xl font-black text-[#001233]">₱ {{ number_format($finalPrice, 2) }}</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>