<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-[#001233]">My Bookings</h2>
            </div>

            {{-- LOGIC: Sort bookings into Categories (Kept your logic) --}}
            @php
                // 1. Cancelled Trips
                $cancelledTrips = $upcomingBookings->where('cancellation_status', 'approved')
                    ->merge($pastBookings->where('cancellation_status', 'approved'));

                // 2. Active Upcoming
                $activeUpcoming = $upcomingBookings->where('cancellation_status', '!=', 'approved');

                // 3. Completed History
                $activePast = $pastBookings->where('cancellation_status', '!=', 'approved');
            @endphp

            {{-- NAVIGATION TABS (Kept your design) --}}
            <div class="flex space-x-1 bg-gray-200 p-1 rounded-xl mb-8 w-fit">
                <button onclick="switchTab('upcoming')" id="btn-upcoming" class="px-6 py-2 rounded-lg text-sm font-bold transition-all bg-white text-[#001233] shadow-sm">
                    Upcoming
                    @if($activeUpcoming->count() > 0)
                        <span class="ml-2 bg-blue-100 text-blue-800 text-[10px] px-2 py-0.5 rounded-full">{{ $activeUpcoming->count() }}</span>
                    @endif
                </button>
                <button onclick="switchTab('completed')" id="btn-completed" class="px-6 py-2 rounded-lg text-sm font-bold text-gray-500 hover:text-[#001233] transition-all">
                    Completed
                    @if($activePast->count() > 0)
                        <span class="ml-2 bg-gray-300 text-gray-700 text-[10px] px-2 py-0.5 rounded-full">{{ $activePast->count() }}</span>
                    @endif
                </button>
                <button onclick="switchTab('cancelled')" id="btn-cancelled" class="px-6 py-2 rounded-lg text-sm font-bold text-gray-500 hover:text-[#001233] transition-all">
                    Cancelled
                    @if($cancelledTrips->count() > 0)
                        <span class="ml-2 bg-red-100 text-red-800 text-[10px] px-2 py-0.5 rounded-full">{{ $cancelledTrips->count() }}</span>
                    @endif
                </button>
            </div>

            {{-- CONTENT AREA --}}
            
            {{-- 1. UPCOMING TAB --}}
            <div id="tab-upcoming" class="tab-content block animate-fade-in-up">
                @if($activeUpcoming->count() > 0)
                    <div class="space-y-6">
                        @foreach($activeUpcoming as $booking)
                            @php
                                $basePrice = $booking->schedule->route->price;
                                // ✅ NEW LOGIC: Calculate price based on Child Type
                                $isChild = $booking->passenger_type === 'child';
                                $finalPrice = $isChild ? $basePrice * 0.80 : $basePrice;
                            @endphp
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-[#001233]">
                                                {{ $booking->schedule->route->origin }} <span class="text-gray-400 mx-2">→</span> {{ $booking->schedule->route->destination }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Departure: {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('F d, Y \a\t h:i A') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            {{-- Status Badges --}}
                                            <div class="flex justify-end gap-2 mb-2">
                                                @if($booking->trip_type === 'round_trip')
                                                    <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-1 rounded border border-blue-100 uppercase tracking-wide">Round Trip</span>
                                                @endif
                                                
                                                @if($booking->status === 'pending')
                                                    <span class="bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">Pending</span>
                                                @elseif($booking->cancellation_status === 'pending')
                                                    <span class="bg-orange-100 text-orange-800 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">Cancel Pending</span>
                                                @else
                                                    <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">Confirmed</span>
                                                @endif
                                            </div>
                                            
                                            {{-- Price Display --}}
                                            <div>
                                                @if($isChild)
                                                    <p class="text-xs text-gray-400 line-through">₱ {{ number_format($basePrice, 2) }}</p>
                                                    <p class="font-bold text-xl text-green-600">₱ {{ number_format($finalPrice, 2) }}</p>
                                                @else
                                                    <p class="font-bold text-xl text-[#001233]">₱ {{ number_format($basePrice, 2) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-dashed border-gray-200 my-4"></div>
                                    
                                    {{-- Passenger Details Grid --}}
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <p class="text-xs text-gray-400 font-bold uppercase">Passenger</p>
                                            <p class="font-medium text-gray-900">{{ $booking->passenger_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 font-bold uppercase">Type</p>
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $isChild ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $booking->passenger_type }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 font-bold uppercase">Seat</p>
                                            <span class="bg-[#001233] text-white px-2 py-0.5 rounded text-xs font-bold">{{ $booking->seat_number }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 font-bold uppercase">Bus No.</p>
                                            <p class="font-medium text-gray-900">{{ $booking->schedule->bus->bus_number }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-6 text-right">
                                        <a href="{{ route('user.bookings.receipt', $booking->id) }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 flex items-center justify-end gap-1">
                                            View Receipt & Details <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl p-10 text-center border border-gray-200 mb-12">
                        <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">No Upcoming Trips</h3>
                        <p class="text-gray-500 text-sm">You don't have any active bookings at the moment.</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-6 py-2 bg-[#001233] text-white rounded-lg font-bold text-sm hover:bg-blue-900">Book a Trip</a>
                    </div>
                @endif
            </div>

            {{-- 2. COMPLETED TAB --}}
            <div id="tab-completed" class="tab-content hidden animate-fade-in-up">
                @if($activePast->count() > 0)
                    <div class="space-y-4 opacity-75 hover:opacity-100 transition duration-300">
                        @foreach($activePast as $booking)
                            @php
                                $basePrice = $booking->schedule->route->price;
                                $isChild = $booking->passenger_type === 'child';
                                $finalPrice = $isChild ? $basePrice * 0.80 : $basePrice;
                            @endphp
                            <div class="bg-gray-100 rounded-lg border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-center grayscale hover:grayscale-0 transition">
                                <div class="mb-4 md:mb-0">
                                    <div class="flex items-center gap-3">
                                        <span class="bg-gray-300 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Completed</span>
                                        @if($booking->trip_type === 'round_trip')
                                            <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Round Trip</span>
                                        @endif
                                        <h4 class="font-bold text-gray-700">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</h4>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('M d, Y') }} • 
                                        {{ $booking->passenger_name }} ({{ ucfirst($booking->passenger_type) }})
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="block font-bold text-gray-600">₱ {{ number_format($finalPrice, 2) }}</span>
                                    <a href="{{ route('user.bookings.receipt', $booking->id) }}" class="text-xs text-blue-500 font-bold hover:underline">View Receipt</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-gray-200">
                        <p>No travel history found.</p>
                    </div>
                @endif
            </div>

            {{-- 3. CANCELLED TAB --}}
            <div id="tab-cancelled" class="tab-content hidden animate-fade-in-up">
                @if($cancelledTrips->count() > 0)
                    <div class="space-y-6">
                        @foreach($cancelledTrips as $booking)
                            @php
                                $basePrice = $booking->schedule->route->price;
                                $isChild = $booking->passenger_type === 'child';
                                $finalPrice = $isChild ? $basePrice * 0.80 : $basePrice;
                            @endphp
                            <div class="bg-red-50 rounded-xl shadow-sm border border-red-100 overflow-hidden relative">
                                <div class="p-6 opacity-75">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-800">
                                                {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Scheduled: {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('F d, Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">Cancelled</span>
                                            <p class="font-bold text-xl text-gray-600 mt-2 line-through decoration-red-500">₱ {{ number_format($finalPrice, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="border-t border-red-100 my-4"></div>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-xs text-gray-500 font-bold uppercase">Passenger</p>
                                            <p class="font-medium text-gray-900">{{ $booking->passenger_name }}</p>
                                        </div>
                                        <a href="{{ route('user.bookings.receipt', $booking->id) }}" class="text-sm text-gray-500 hover:text-red-700 underline">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-gray-400 bg-white rounded-xl border border-gray-200">
                        <p>No cancelled trips.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
    
    {{-- JAVASCRIPT FOR TABS (Unchanged) --}}
    <script>
        function switchTab(tabName) {
            // 1. Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            
            // 2. Show selected tab content
            document.getElementById('tab-' + tabName).classList.remove('hidden');

            // 3. Reset all buttons
            document.querySelectorAll('button[id^="btn-"]').forEach(btn => {
                btn.classList.remove('bg-white', 'text-[#001233]', 'shadow-sm');
                btn.classList.add('text-gray-500');
            });

            // 4. Highlight selected button
            const activeBtn = document.getElementById('btn-' + tabName);
            activeBtn.classList.remove('text-gray-500');
            activeBtn.classList.add('bg-white', 'text-[#001233]', 'shadow-sm');
        }
    </script>
</x-app-layout>