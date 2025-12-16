<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="p-8">
        
        <div class="bg-[#001233] print-dark -m-8 mb-8 px-8 py-6 flex justify-between items-center text-white">
            <div>
                <h2 class="text-2xl font-bold">Booking #{{ $reservation->id }}</h2>
                <p class="text-blue-200 text-sm">Booked on {{ $reservation->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="bg-green-600 text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                    {{ $reservation->status }}
                </span>
            </div>
        </div>

        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 mt-8">Passenger Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Full Name</p>
                <p class="text-lg font-bold text-gray-800">{{ $reservation->user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Email Address</p>
                <p class="text-base font-medium text-gray-700">{{ $reservation->user->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Account Role</p>
                <p class="text-base font-medium text-gray-700">{{ ucfirst($reservation->user->role) }}</p>
            </div>
        </div>

        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Trip Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="border-l-4 border-[#001233] pl-4">
                <p class="text-sm text-gray-500">Route</p>
                <div class="flex items-center gap-2 text-xl font-bold text-[#001233]">
                    <span>{{ $reservation->schedule->route->origin }}</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    <span>{{ $reservation->schedule->route->destination }}</span>
                </div>
            </div>
            
            <div class="border-l-4 border-blue-500 pl-4">
                <p class="text-sm text-gray-500">Departure</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('F d, Y') }}
                    <span class="text-gray-400 mx-1">|</span>
                    {{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('h:i A') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="p-4 border border-gray-200 rounded-lg">
                <p class="text-xs text-gray-400 uppercase font-bold">Bus Number</p>
                <p class="font-bold text-gray-800">{{ $reservation->schedule->bus->bus_number }}</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <p class="text-xs text-gray-400 uppercase font-bold">Plate Number</p>
                <p class="font-bold text-gray-800">{{ $reservation->schedule->bus->plate_number ?? 'N/A' }}</p>
            </div>
            <div class="p-4 border border-gray-200 rounded-lg">
                <p class="text-xs text-gray-400 uppercase font-bold">Bus Type</p>
                <p class="font-bold text-gray-800">{{ $reservation->schedule->bus->type }}</p>
            </div>
            <div class="p-4 bg-[#001233] rounded-lg text-white">
                <p class="text-xs text-blue-200 uppercase font-bold">Seat Number</p>
                <p class="text-2xl font-black">{{ $reservation->seat_number }}</p>
            </div>
        </div>

        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 mt-8">Payment Details</h3>
        <div class="bg-[#F8F9FA] rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Transaction Reference</p>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-lg font-bold text-[#001233]">{{ $reservation->transaction_id ?? 'N/A' }}</span>
                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200">VERIFIED</span>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-xs text-gray-400 uppercase font-bold mb-1">Payment Method</p>
                        <p class="font-bold text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            {{ $reservation->payment_method ?? 'Credit Card' }}
                        </p>
                    </div>
                </div>

                <div class="border-l border-gray-200 pl-8">
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="text-gray-500">Base Fare</span>
                        <span class="font-bold text-gray-800">₱ {{ number_format($reservation->schedule->route->price, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2 text-sm">
                        <span class="text-gray-500">Online Fees</span>
                        <span class="font-bold text-gray-800">₱ 0.00</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-300 pt-3 mt-2">
                        <span class="text-base font-bold text-[#001233]">Total Paid</span>
                        <span class="text-2xl font-black text-[#001233]">₱ {{ number_format($reservation->schedule->route->price, 2) }}</span>
                    </div>
                    
                    <p class="text-[10px] text-gray-400 mt-2 italic">
                        * Card details are tokenized and not stored on this server for security compliance.
                    </p>
                </div>

            </div>
        </div>
        
    </div>
</div>