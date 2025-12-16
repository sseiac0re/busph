<x-app-layout>
    {{-- 1. LOAD LEAFLET ASSETS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    {{-- SETUP DATES --}}
    @php
        $minDate = \Carbon\Carbon::now()->subDay()->format('Y-m-d');
        $currentDate = $searchDate ?? date('Y-m-d');
        $reqTripType = request('trip_type', 'one_way');
        $reqReturnDate = request('return_date');
    @endphp

    {{-- HERO SECTION --}}
    <div class="relative w-full h-[500px] flex-shrink-0">
        <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-[#001233]/40"></div>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
            
            {{-- DYNAMIC HEADER LOGIC --}}
            @if(request('is_return'))
                <div class="bg-green-500 text-white px-6 py-2 rounded-full font-bold mb-6 animate-bounce shadow-lg border-2 border-white/20 backdrop-blur-sm">
                    ✓ Outbound Trip Selected!
                </div>
                <h1 class="text-3xl md:text-5xl font-extrabold text-white drop-shadow-lg mb-8">
                    Now Select Your <span class="text-yellow-400">Return Bus</span>
                </h1>
            @else
                <h1 class="text-3xl md:text-5xl font-extrabold text-white drop-shadow-lg mb-8">
                    @auth <span class="text-yellow-400">Welcome, {{ Auth::user()->name }}!</span><br>Where to next?
                    @else <span class="text-yellow-400">Hassle-Free.</span> Book Anytime. Anywhere.<br>with BusPH @endauth
                </h1>
            @endif
            
            {{-- SEARCH FORM --}}
            <form x-data="{ tripType: '{{ $reqTripType }}' }" id="searchForm" action="{{ route('home') }}" method="GET" 
                  class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col gap-6 w-full max-w-6xl">
                
                <input type="hidden" name="hide_full" id="input_hide_full" value="{{ request('hide_full') }}">
                <input type="hidden" name="bus_type" id="input_bus_type" value="{{ request('bus_type') }}">
                
                {{-- ✅ CRITICAL FIX: Pass 'is_return' so the next search knows we are still booking the return leg --}}
                @if(request('is_return'))
                    <input type="hidden" name="is_return" value="1">
                @endif
                
                {{-- ✅ CRITICAL FIX: Hide Trip Type Toggle if we are selecting the return leg --}}
                @if(!request('is_return'))
                    <div class="flex gap-8 border-b border-gray-100 pb-4">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="radio" name="trip_type" value="one_way" x-model="tripType" class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border-2 border-gray-300 transition-all checked:border-[#001233] checked:bg-[#001233]">
                                <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100">
                                    <svg class="h-3 w-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>
                            </div>
                            <span class="font-bold text-gray-600 group-hover:text-[#001233] transition">One Way</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="radio" name="trip_type" value="round_trip" x-model="tripType" class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border-2 border-gray-300 transition-all checked:border-[#001233] checked:bg-[#001233]">
                                <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100">
                                    <svg class="h-3 w-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>
                            </div>
                            <span class="font-bold text-gray-600 group-hover:text-[#001233] transition">Round Trip</span>
                        </label>
                    </div>
                @endif
                
                {{-- INPUTS ROW --}}
                <div class="flex flex-col md:flex-row items-center gap-2 w-full">
                    
                    {{-- ORIGIN --}}
                    <div class="flex items-center bg-gray-50 rounded-xl px-4 py-3 flex-1 w-full border border-gray-200 hover:border-[#001233] transition-colors group">
                        <div class="mr-3 text-gray-400 group-hover:text-[#001233] transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                        <div class="text-left w-full">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">From</label>
                            <select name="origin" class="w-full bg-transparent border-none p-0 text-[#001233] font-bold text-lg focus:ring-0 cursor-pointer outline-none placeholder-gray-300">
                                <option value="">Select Origin</option>
                                @foreach($origins as $origin) <option value="{{ $origin }}" {{ request('origin') == $origin ? 'selected' : '' }}>{{ $origin }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- SWAP ICON --}}
                    <div class="hidden md:flex bg-gray-100 rounded-full p-2 text-gray-400 hover:text-[#001233] hover:bg-gray-200 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>

                    {{-- DESTINATION --}}
                    <div class="flex items-center bg-gray-50 rounded-xl px-4 py-3 flex-1 w-full border border-gray-200 hover:border-[#001233] transition-colors group">
                        <div class="mr-3 text-gray-400 group-hover:text-[#001233] transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                        <div class="text-left w-full">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">To</label>
                            <select name="destination" class="w-full bg-transparent border-none p-0 text-[#001233] font-bold text-lg focus:ring-0 cursor-pointer outline-none">
                                <option value="">Select Destination</option>
                                @foreach($destinations as $destination) <option value="{{ $destination }}" {{ request('destination') == $destination ? 'selected' : '' }}>{{ $destination }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- DEPARTURE DATE --}}
                    <div class="flex items-center bg-gray-50 rounded-xl px-4 py-3 flex-1 w-full border border-gray-200 hover:border-[#001233] transition-colors group">
                        <div class="mr-3 text-gray-400 group-hover:text-[#001233] transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                        <div class="text-left w-full">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Departure</label>
                            <input type="date" name="date" id="input_date" class="w-full bg-transparent border-none p-0 text-[#001233] font-bold text-lg focus:ring-0 outline-none" value="{{ $currentDate }}" min="{{ $minDate }}">
                        </div>
                    </div>

                    {{-- ✅ HIDE RETURN DATE FIELD ON SEARCH (Because we search one leg at a time) --}}
                    {{-- Only show if NOT is_return AND tripType is round_trip --}}
                    <div x-show="tripType === 'round_trip' && '{{ request('is_return') }}' !== '1'" 
                         x-transition 
                         class="flex items-center bg-gray-50 rounded-xl px-4 py-3 flex-1 w-full border border-gray-200 hover:border-[#001233] transition-colors group">
                        <div class="mr-3 text-gray-400 group-hover:text-[#001233] transition-colors"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                        <div class="text-left w-full">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Return</label>
                            <input type="date" name="return_date" class="w-full bg-transparent border-none p-0 text-[#001233] font-bold text-lg focus:ring-0 outline-none" value="{{ $reqReturnDate }}" min="{{ $minDate }}">
                        </div>
                    </div>

                    <button type="submit" class="bg-[#001233] hover:bg-blue-900 text-white font-bold py-4 px-8 rounded-xl h-full w-full md:w-auto shadow-lg hover:shadow-xl transition-all tracking-wide text-lg flex items-center justify-center gap-2">
                        Find
                        <svg class="w-5 h-5 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- CONTENT AREA --}}
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-grow w-full">
        
        {{-- FILTERS (Unchanged) --}}
        <div class="mb-8 max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <button onclick="toggleFilterDrawer()" class="flex items-center gap-2 text-[#001233] font-bold hover:text-blue-700 mb-4 md:mb-0 transition focus:outline-none">
                    <svg id="filter-icon" class="w-6 h-6 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span id="filter-text">Add Filter</span>
                </button>
                <div class="flex items-center gap-3">
                    <div class="relative inline-block w-12 h-6 align-middle select-none transition duration-200 ease-in">
                        <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                        <input type="checkbox" id="toggle" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 border-gray-300 appearance-none cursor-pointer top-0 left-0" {{ request('hide_full') ? 'checked' : '' }} onchange="toggleFullBooked(this)"/>
                    </div>
                    <label for="toggle" class="text-gray-500 font-medium cursor-pointer">Hide Fully-Booked Trips</label>
                </div>
            </div>
            <div id="filter-drawer" class="{{ request('bus_type') ? '' : 'hidden' }} mt-4 bg-white p-6 rounded-lg shadow-sm border border-gray-200 animate-fade-in-down">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Filter by Bus Type</p>
                <div class="flex gap-3 flex-wrap">
                    <button onclick="setBusType('')" class="px-6 py-2 rounded-full text-sm font-bold border transition {{ !request('bus_type') ? 'bg-[#001233] text-white border-[#001233]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">All Types</button>
                    @if(isset($busTypes))
                        @foreach($busTypes as $type)
                            <button onclick="setBusType('{{ $type }}')" class="px-6 py-2 rounded-full text-sm font-bold border transition {{ request('bus_type') == $type ? 'bg-[#001233] text-white border-[#001233]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">{{ $type }}</button>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- DATE CAROUSEL --}}
        <div class="flex items-center justify-between gap-4 mb-10 max-w-7xl mx-auto">
            @if($currentDate <= $minDate)
                <button disabled class="bg-gray-100 p-3 rounded-xl text-gray-300 cursor-not-allowed"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
            @else
                <button onclick="changeDate(-1)" class="bg-white p-3 rounded-xl shadow-sm hover:shadow-md text-[#001233] transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
            @endif
            <div class="flex gap-2 overflow-x-auto no-scrollbar w-full">
                @if(isset($dates))
                    @foreach($dates as $date)
                        @php $dString = $date->format('Y-m-d'); $isActive = $dString === $currentDate; @endphp
                        <div onclick="setDate('{{ $dString }}')" class="flex-1 min-w-[140px] text-center py-4 bg-white border {{ $isActive ? 'border-[#001233] border-b-4' : 'border-gray-200' }} cursor-pointer hover:bg-gray-50 transition rounded-lg shadow-sm">
                            <p class="text-sm font-bold {{ $isActive ? 'text-[#001233]' : 'text-gray-500' }}">{{ $date->format('D, d M') }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <button onclick="changeDate(1)" class="bg-white p-3 rounded-xl shadow-sm hover:shadow-md text-[#001233] transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
        </div>

        {{-- RESULTS LIST --}}
        <div class="space-y-4 max-w-7xl mx-auto">
            @forelse($schedules as $schedule)
                @php
                    // 1. Existing Logic
                    $activeReservations = $schedule->reservations->reject(function($reservation) {
                        return $reservation->cancellation_status === 'approved';
                    });
                    $seatsTaken = $activeReservations->count();
                    $capacity = $schedule->bus->capacity ?? 45;
                    $seatsLeft = $capacity - $seatsTaken;
                    $isFull = $seatsLeft <= 0;
                    $isPast = \Carbon\Carbon::parse($schedule->departure_time)->isPast();
                    $seatsTakenArray = $activeReservations->pluck('seat_number')->toArray();

                    // 2. ✅ NEW: Time & Duration Logic
                    $start = \Carbon\Carbon::parse($schedule->departure_time);
                    // Fallback to +6 hours if arrival_time is missing in DB
                    $end = $schedule->arrival_time ? \Carbon\Carbon::parse($schedule->arrival_time) : $start->copy()->addHours(6);
                    $duration = $start->diff($end)->format('%hh %im');
                    $isNextDay = $end->isNextDay($start);
                @endphp

            {{-- X-DATA (Unchanged logic, just ensure it wraps the card) --}}
            <div x-data="{ 
                    expanded: false, 
                    showMap: false,
                    selected: [], 
                    adults: 0,
                    children: 0,
                    tripType: '{{ $reqTripType }}',
                    price: {{ $schedule->route->price }},
                    mapInitialized: false,
                    
                    toggleSeat(seat) {
                        if (this.selected.includes(seat)) {
                            this.selected = this.selected.filter(s => s !== seat);
                            if (this.children > 0) this.children--;
                            else if (this.adults > 0) this.adults--;
                        } else {
                            this.selected.push(seat);
                            this.adults++;
                        }
                    },
                    
                    adjustPassengers(type, change) {
                        const totalSeats = this.selected.length;
                        if (totalSeats === 0) return; 

                        if (type === 'adult') {
                            const newAdults = this.adults + change;
                            if (newAdults < 0) return;
                            if (change > 0) {
                                if (newAdults + this.children <= totalSeats) this.adults = newAdults;
                                else if (this.children > 0) { this.children--; this.adults = newAdults; }
                            } else { this.adults = newAdults; }
                        } else if (type === 'child') {
                            const newChildren = this.children + change;
                            if (newChildren < 0) return;
                            if (change > 0) {
                                if (this.adults + newChildren <= totalSeats) this.children = newChildren;
                                else if (this.adults > 0) { this.adults--; this.children = newChildren; }
                            } else { this.children = newChildren; }
                        }
                    },

                    get totalPassengers() { return this.adults + this.children; },
                    get total() { 
                        const adultTotal = this.adults * this.price;
                        const childTotal = this.children * (this.price * 0.8);
                        return (adultTotal + childTotal).toLocaleString('en-US', {minimumFractionDigits: 2});
                    },
                    initMap() {
                        if (this.mapInitialized) return;
                        this.mapInitialized = true;
                        setTimeout(() => {
                            const locations = {
                                'Cubao': [14.6195, 121.0511], 'Pasay': [14.5378, 121.0014], 'Baguio': [16.4023, 120.5960],
                                'Legazpi': [13.1391, 123.7438], 'Naga': [13.6218, 123.1948], 'Batangas': [13.7565, 121.0583], 'Manila': [14.5995, 120.9842]
                            };
                            const originName = '{{ $schedule->route->origin }}';
                            const destName = '{{ $schedule->route->destination }}';
                            const originCoords = locations[originName] || [14.5995, 120.9842];
                            const destCoords = locations[destName] || [14.5995, 120.9842];

                            var map = L.map('map-{{ $schedule->id }}', { center: originCoords, zoom: 6, zoomControl: false, attributionControl: false });
                            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
                            var busIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/3448/3448339.png', iconSize: [24, 24], iconAnchor: [12, 24] });
                            var pinIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/447/447031.png', iconSize: [24, 24], iconAnchor: [12, 24] });

                            L.marker(originCoords, {icon: busIcon}).addTo(map).bindPopup(originName);
                            L.marker(destCoords, {icon: pinIcon}).addTo(map).bindPopup(destName);
                            var routeLine = L.polyline([originCoords, destCoords], { color: '#001233', weight: 3, opacity: 0.8, dashArray: '5, 5' }).addTo(map);
                            map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });
                            map.invalidateSize();
                        }, 200);
                    }
                 }" 
                 class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center border-b border-gray-100 pb-6 mb-4">
                        
                        {{-- ✅ UPDATED: TIME & ROUTE SECTION --}}
                        <div class="flex items-center gap-8 w-full md:w-auto flex-1">
                            {{-- Departure --}}
                            <div class="text-center min-w-[80px]">
                                <h3 class="text-2xl font-black text-[#001233]">{{ $start->format('H:i') }}</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ $schedule->route->origin }}</p>
                            </div>

                            {{-- Duration Visual --}}
                            <div class="flex flex-col items-center flex-1 px-4">
                                <span class="text-[10px] font-bold text-gray-400 mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $duration }}
                                </span>
                                <div class="w-full h-0.5 bg-gray-200 relative flex items-center">
                                    <div class="w-full border-t border-dashed border-gray-400"></div>
                                    <div class="absolute right-0 -top-1">
                                        <svg class="w-3 h-3 text-gray-400 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            
                            {{-- Arrival --}}
                            <div class="text-center min-w-[80px]">
                                <h3 class="text-2xl font-black text-gray-600">{{ $end->format('H:i') }}</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">
                                    {{ $schedule->route->destination }}
                                    @if($isNextDay) <span class="text-red-400 text-[9px] ml-1">(+1 Day)</span> @endif
                                </p>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="mt-4 md:mt-0 md:pl-12 md:border-l md:border-gray-100 text-center md:text-right min-w-[120px]">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Per Person</p>
                            <span class="text-2xl font-black text-[#001233]">PHP {{ number_format($schedule->route->price, 2) }}</span>
                        </div>
                    </div>

                    {{-- BOTTOM SECTION (Bus Type, Code, Availability, Buttons) --}}
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex gap-8 text-sm text-gray-500 w-full md:w-auto">
                            <div><p class="font-bold text-[#001233] text-xs uppercase">Bus Type</p><p class="text-xs">{{ $schedule->bus->type }}</p></div>
                            <div><p class="font-bold text-[#001233] text-xs uppercase">Code</p><p class="text-xs">BC{{ $schedule->id }}</p></div>
                        </div>

                        <div class="flex items-center gap-4 w-full md:w-auto justify-end">
                            @if($isPast)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-500 border border-gray-300">DEPARTED</span>
                            @elseif($isFull)
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">FULLY BOOKED</span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-white text-green-700 border border-gray-300">
                                    <span class="w-2.5 h-2.5 mr-2 bg-green-500 rounded-full animate-pulse"></span>{{ $seatsLeft }} left
                                </span>

                                <button @click="expanded = true; showMap = !showMap; if(showMap) initMap()" 
                                        :class="showMap ? 'text-[#001233]' : 'text-gray-400'"
                                        class="hover:text-[#001233] font-bold text-xs flex items-center gap-1 transition mr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                    <span x-text="showMap ? 'Hide Map' : 'View Map'"></span>
                                </button>

                                <button @click="expanded = !expanded" 
                                        :class="expanded ? 'bg-gray-200 text-[#001233]' : 'bg-[#001233] text-white hover:bg-blue-900'"
                                        class="font-bold py-2 px-6 rounded-full shadow transition flex items-center gap-2 text-sm">
                                    <span x-text="expanded ? 'Hide Seats' : 'Select Seats'"></span>
                                    <svg x-show="!expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    <svg x-show="expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- EXPANDED SECTION (Contains Seats, Map, and Form) --}}
                <div x-show="expanded" x-collapse class="bg-white border-t border-gray-100 px-6 py-8">
                    <div class="flex flex-col lg:flex-row gap-6">
                        
                        {{-- 1. SEATS GRID --}}
                        <div :class="showMap ? 'lg:w-1/3' : 'lg:w-1/2'" class="w-full border-r border-gray-100 pr-4 transition-all duration-300">
                            <h4 class="font-bold text-[#001233] mb-4 text-sm">Select Seat(s)</h4>
                            <div class="bg-[#F3F4F6] p-4 rounded-xl border border-gray-200 max-w-sm mx-auto">
                                <div class="grid grid-cols-5 gap-2">
                                    @for($i = 1; $i <= $schedule->bus->capacity; $i++)
                                        @php $isTaken = in_array($i, $seatsTakenArray); @endphp
                                        @if($i % 4 == 3 && $i > 1) <div></div> @endif
                                        <button @if(!$isTaken) @click="toggleSeat({{ $i }})" @endif
                                                :class="selected.includes({{ $i }}) ? 'bg-[#10B981] text-white border-[#10B981] shadow-md scale-105' : '{{ $isTaken ? 'bg-gray-400 text-gray-200 cursor-not-allowed border-transparent' : 'bg-white text-gray-600 border-gray-200 hover:border-[#001233]' }}'"
                                                class="h-8 w-8 rounded-md font-bold text-xs transition border flex items-center justify-center transform duration-150">
                                            {{ $isTaken ? 'X' : $i }}
                                        </button>
                                    @endfor
                                </div>
                            </div>
                            <div class="flex gap-4 mt-4 text-xs text-gray-500 font-medium justify-center">
                                <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-white border border-gray-300"></div> Free</div>
                                <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-gray-400"></div> Taken</div>
                                <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-[#10B981]"></div> Yours</div>
                            </div>
                        </div>

                        {{-- 2. MAP --}}
                        <div x-show="showMap" x-transition 
                             class="w-full lg:w-1/3 bg-white p-1 rounded-xl shadow-sm border border-gray-200 relative group h-64 lg:h-auto">
                            <div id="map-{{ $schedule->id }}" style="height: 100%; width: 100%; border-radius: 0.5rem; z-index: 1;"></div>
                            <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded shadow text-[10px] font-bold text-gray-500 uppercase z-[400]">Route Map</div>
                        </div>

                        {{-- 3. SUMMARY & FORM --}}
                        <div :class="showMap ? 'lg:w-1/3' : 'lg:w-1/2'" class="w-full pl-4 flex flex-col justify-between py-2 transition-all duration-300">
                            <div class="space-y-4 mb-4">
                                
                                {{-- ✅ FIX 1: HIDE TOGGLE ON RETURN SELECTION --}}
                                @if(!request('is_return'))
                                    <div class="bg-gray-100 p-1 rounded-lg flex mb-4">
                                        <button type="button" @click="tripType = 'one_way'" 
                                                :class="tripType === 'one_way' ? 'bg-white text-[#001233] shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                                class="flex-1 py-1.5 rounded-md text-xs font-bold transition-all">
                                            One Way
                                        </button>
                                        <button type="button" @click="tripType = 'round_trip'" 
                                                :class="tripType === 'round_trip' ? 'bg-white text-[#001233] shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                                class="flex-1 py-1.5 rounded-md text-xs font-bold transition-all">
                                            Round Trip
                                        </button>
                                    </div>
                                @else
                                    {{-- Visual confirmation that this is the return leg --}}
                                    <div class="bg-blue-50 text-blue-800 text-xs font-bold px-3 py-2 rounded-lg mb-4 text-center border border-blue-100">
                                        Select Return Trip
                                    </div>
                                @endif

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 text-sm font-medium">Pick-up Time</span>
                                    <span class="font-black text-[#001233] text-lg">{{ $start->format('h:i A') }}</span>
                                </div>
                                <div>
                                    <label class="text-[10px] text-gray-400 font-bold uppercase mb-1 block tracking-wider">Route</label>
                                    <div class="w-full px-3 py-2 rounded bg-gray-50 text-[#001233] font-bold text-sm border border-gray-100 flex justify-between">
                                        <span>{{ $schedule->route->origin }}</span>
                                        <span>&rarr;</span>
                                        <span>{{ $schedule->route->destination }}</span>
                                    </div>
                                </div>

                                {{-- Passenger Count with Buttons --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-[#001233]">Passenger Count</label>
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-500 uppercase tracking-wide mb-1">Adults</label>
                                            <div class="flex items-center justify-between border border-gray-200 rounded-lg p-1 bg-gray-50">
                                                <button type="button" @click="adjustPassengers('adult', -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-gray-600 hover:bg-gray-100 hover:text-[#001233] font-bold">-</button>
                                                <span x-text="adults" class="font-bold text-[#001233]"></span>
                                                <button type="button" @click="adjustPassengers('adult', 1)" class="w-8 h-8 flex items-center justify-center bg-[#001233] rounded shadow-sm text-white hover:bg-blue-900 font-bold">+</button>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-500 uppercase tracking-wide mb-1">Kids (20% Off)</label>
                                            <div class="flex items-center justify-between border border-gray-200 rounded-lg p-1 bg-gray-50">
                                                <button type="button" @click="adjustPassengers('child', -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-gray-600 hover:bg-gray-100 hover:text-[#001233] font-bold">-</button>
                                                <span x-text="children" class="font-bold text-[#001233]"></span>
                                                <button type="button" @click="adjustPassengers('child', 1)" class="w-8 h-8 flex items-center justify-center bg-[#001233] rounded shadow-sm text-white hover:bg-blue-900 font-bold">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-1">
                                        <p x-show="selected.length > 0 && selected.length !== totalPassengers" class="text-red-500 text-xs font-bold">
                                            <span x-show="totalPassengers < selected.length">Add passengers to match seats.</span>
                                            <span x-show="totalPassengers > selected.length">Too many passengers for selected seats.</span>
                                        </p>
                                        <p x-show="children > 0 && adults === 0" class="text-red-500 text-xs font-bold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            Children cannot travel alone.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm text-gray-500">
                                        <span>Seat(s)</span>
                                        <span class="font-bold text-[#001233]" x-text="selected.length > 0 ? selected.join(', ') : '-'"></span>
                                    </div>
                                    <div class="flex justify-between items-end border-t border-gray-200 pt-2">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total</span>
                                        <span class="font-black text-xl text-[#001233]">PHP <span x-text="total"></span></span>
                                    </div>
                                </div>

                                <form id="reserveForm-{{ $schedule->id }}" action="{{ route('booking.details') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                    <input type="hidden" name="seats" :value="selected.join(',')">
                                    
                                    {{-- ✅ FIX 2: FORCE 'round_trip' IF BOOKING RETURN LEG --}}
                                    @if(request('is_return'))
                                        <input type="hidden" name="trip_type" value="round_trip">
                                    @else
                                        <input type="hidden" name="trip_type" :value="tripType">
                                    @endif
                                    
                                    <input type="hidden" name="passengers_adult" :value="adults">
                                    <input type="hidden" name="passengers_child" :value="children">
                                    <input type="hidden" name="return_date" value="{{ $reqReturnDate }}">

                                    <button type="submit"
                                            :disabled="selected.length === 0 || selected.length !== totalPassengers || (children > 0 && adults === 0)"
                                            :class="(selected.length > 0 && selected.length === totalPassengers && !(children > 0 && adults === 0)) ? 'bg-[#001233] hover:bg-blue-900 text-white shadow-lg' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                            class="w-full py-3 font-bold rounded-lg transition uppercase text-xs tracking-widest flex items-center justify-center gap-2">
                                        <span class="btn-text">Reserve Now</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No Trips" class="h-24 w-auto mb-4 opacity-30 grayscale">
                    <h3 class="text-xl font-medium text-gray-500">No Available Trips</h3>
                    <p class="text-gray-400 mt-2 text-sm">We couldn't find any buses for this date.<br>Try changing your filters or date.</p>
                    <a href="{{ route('home') }}" class="mt-6 px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-300 transition text-sm">Clear All Filters</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- SCRIPTS (Unchanged) --}}
    <script>
        function setDate(date) { document.getElementById('input_date').value = date; document.getElementById('searchForm').submit(); }
        function toggleFullBooked(checkbox) { document.getElementById('input_hide_full').value = checkbox.checked ? '1' : ''; document.getElementById('searchForm').submit(); }
        function setBusType(type) { document.getElementById('input_bus_type').value = type; document.getElementById('searchForm').submit(); }
        function toggleFilterDrawer() {
            const drawer = document.getElementById('filter-drawer'); const icon = document.getElementById('filter-icon'); const text = document.getElementById('filter-text');
            if (drawer.classList.contains('hidden')) { drawer.classList.remove('hidden'); icon.style.transform = 'rotate(45deg)'; text.innerText = "Close Filters"; } 
            else { drawer.classList.add('hidden'); icon.style.transform = 'rotate(0deg)'; text.innerText = "Add Filter"; }
        }
        function changeDate(days) {
            const current = new Date(document.getElementById('input_date').value); 
            current.setDate(current.getDate() + days);
            setDate(current.toISOString().split('T')[0]);
        }
    </script>
</x-app-layout>