<x-app-layout>
    
    {{-- 1. LOAD LEAFLET ASSETS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    {{-- 2. MAP STYLE --}}
    <style>
        #map { height: 100%; min-height: 500px; width: 100%; border-radius: 0.75rem; z-index: 1; }
    </style>

    {{-- 3. ALPINE DATA --}}
    <div class="flex flex-col min-h-screen" 
         x-data="{ 
            selected: [], 
            adults: 1, 
            children: 0, 
            price: {{ $schedule->route->price }},
            
            toggleSeat(seat) {
                if (this.selected.includes(seat)) {
                    this.selected = this.selected.filter(s => s !== seat);
                    // Auto-adjust passengers if seats are removed
                    if (this.totalPassengers > this.selected.length) {
                        if (this.adults > 0) this.adults--;
                        else if (this.children > 0) this.children--;
                    }
                } else {
                    this.selected.push(seat);
                    // Auto-increment adults if we add a seat
                    if (this.totalPassengers < this.selected.length) {
                        this.adults++;
                    }
                }
            },

            adjustPassengers(type, change) {
                const currentTotal = this.totalPassengers;
                const maxSeats = this.selected.length;

                if (type === 'adult') {
                    const newAdults = this.adults + change;
                    if (newAdults < 0) return; // Cannot be negative
                    // Logic: Allow changing count, but warn if it doesn't match seats later
                    this.adults = newAdults;
                } else {
                    const newChildren = this.children + change;
                    if (newChildren < 0) return;
                    this.children = newChildren;
                }
            },

            get totalPassengers() { return this.adults + this.children; },
            get totalPrice() { 
                return ((this.adults * this.price) + (this.children * (this.price * 0.8))).toLocaleString('en-US', {minimumFractionDigits: 2});
            }
         }">

        {{-- TOP NAVIGATION --}}
        <div class="bg-white border-b border-gray-200 shadow-sm relative z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center justify-center space-x-6 text-sm font-medium">
                    <div class="flex items-center text-[#001233]">
                        <div class="p-1 bg-[#001233] rounded-full text-white mr-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        </div>
                        <span class="font-bold border-b-2 border-[#001233] pb-0.5">Select Seats</span>
                    </div>
                    <span class="text-gray-300">/</span>
                    <div class="flex items-center text-gray-400">
                        <span>Passenger Info</span>
                    </div>
                    <span class="text-gray-300">/</span>
                    <div class="flex items-center text-gray-400">
                        <span>Payment</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TRIP HEADER --}}
        <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-12">
                        <div class="text-center md:text-left">
                            <h3 class="text-3xl font-black text-[#001233]">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</h3>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Departure</p>
                        </div>
                        <div class="flex items-center gap-8">
                            <span class="text-xl font-bold text-[#001233]">{{ $schedule->route->origin }}</span>
                            <svg class="w-6 h-6 text-[#001233]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            <span class="text-xl font-bold text-[#001233]">{{ $schedule->route->destination }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-[#001233]">PHP {{ number_format($schedule->route->price, 2) }}</span>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Per Passenger</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-grow py-12">
            <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-6">
                    
                    {{-- COLUMN 1: SEATS --}}
                    <div class="w-full lg:w-1/3 bg-white p-8 rounded-xl shadow-sm border border-gray-200">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                            <h4 class="font-bold text-[#001233] text-lg">Select Seat(s)</h4>
                            <div class="flex gap-4 text-xs font-bold text-gray-500">
                                <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-white border border-gray-300"></div> Free</div>
                                <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-gray-400"></div> Taken</div>
                                <div class="flex items-center gap-1"><div class="w-3 h-3 rounded bg-[#10B981]"></div> Yours</div>
                            </div>
                        </div>
                        
                        <div class="bg-[#F3F4F6] p-6 rounded-2xl border border-gray-200 mx-auto relative">
                            <div class="absolute top-4 left-1/2 transform -translate-x-1/2">
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Driver</span>
                            </div>

                            <div class="grid grid-cols-5 gap-3 mt-8">
                                @for($i = 1; $i <= $schedule->bus->capacity; $i++)
                                    @php $isTaken = in_array($i, $takenSeats); @endphp
                                    @if($i % 4 == 3 && $i > 1) <div></div> @endif

                                    <button @click="toggleSeat({{ $i }})"
                                            {{ $isTaken ? 'disabled' : '' }}
                                            :class="selected.includes({{ $i }}) 
                                                ? 'bg-[#10B981] text-white border-[#10B981] shadow-md scale-105' 
                                                : '{{ $isTaken ? 'bg-gray-300 text-gray-100 cursor-not-allowed border-transparent' : 'bg-white text-gray-600 border-gray-300 hover:border-[#10B981]' }}'"
                                            class="h-10 w-10 rounded-lg font-bold text-sm transition border-2 flex items-center justify-center">
                                        {{ $isTaken ? 'X' : $i }}
                                    </button>
                                @endfor
                            </div>
                        </div>
                    </div>

                    {{-- COLUMN 2: MAP --}}
                    <div class="w-full lg:w-1/3 bg-white p-1 rounded-xl shadow-sm border border-gray-200 relative group h-[500px] lg:h-auto">
                        <div id="map"></div>
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg shadow-md border border-gray-200 z-[400]">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ">Live Route</p>
                            <p class="font-bold text-[#001233] text-sm">{{ $schedule->route->origin }} to {{ $schedule->route->destination }}</p>
                        </div>
                    </div>

                    {{-- COLUMN 3: SUMMARY & FORM --}}
                    <div class="w-full lg:w-1/3">
                        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200 sticky top-24">
                            
                            {{-- TRIP TYPE TOGGLE --}}
                            @if(!request('is_return'))
                                <div class="bg-gray-100 p-1 rounded-lg flex mb-6">
                                    <div class="flex-1 py-1.5 rounded-md text-xs font-bold text-center transition-all bg-white text-[#001233] shadow-sm">
                                        {{ request('trip_type') == 'round_trip' ? 'Round Trip' : 'One Way' }}
                                    </div>
                                </div>
                            @endif

                            <div class="mb-6">
                                <h3 class="text-sm font-bold text-[#001233] mb-3">Passenger Count</h3>
                                
                                {{-- ADULTS --}}
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Adults</span>
                                    <div class="flex items-center gap-3">
                                        <button @click="adjustPassengers('adult', -1)" class="w-6 h-6 rounded bg-gray-100 text-gray-600 hover:bg-gray-200 flex items-center justify-center font-bold">-</button>
                                        <span x-text="adults" class="font-bold text-[#001233] w-4 text-center"></span>
                                        <button @click="adjustPassengers('adult', 1)" class="w-6 h-6 rounded bg-[#001233] text-white hover:bg-blue-900 flex items-center justify-center font-bold">+</button>
                                    </div>
                                </div>

                                {{-- CHILDREN --}}
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Kids (20% Off)</span>
                                    <div class="flex items-center gap-3">
                                        <button @click="adjustPassengers('child', -1)" class="w-6 h-6 rounded bg-gray-100 text-gray-600 hover:bg-gray-200 flex items-center justify-center font-bold">-</button>
                                        <span x-text="children" class="font-bold text-[#001233] w-4 text-center"></span>
                                        <button @click="adjustPassengers('child', 1)" class="w-6 h-6 rounded bg-[#001233] text-white hover:bg-blue-900 flex items-center justify-center font-bold">+</button>
                                    </div>
                                </div>

                                {{-- Warning Messages --}}
                                <p x-show="selected.length > 0 && selected.length !== totalPassengers" class="text-red-500 text-xs font-bold mt-2 text-right">
                                    <span x-show="totalPassengers < selected.length">Add passengers to match seats.</span>
                                    <span x-show="totalPassengers > selected.length">Too many passengers for seats.</span>
                                </p>
                            </div>

                            <div class="space-y-2 mb-6 pt-6 border-t border-dashed border-gray-200">
                                <div class="flex justify-between items-start text-sm text-gray-600">
                                    <span class="shrink-0">Seat(s) Selected</span>
                                    <span class="font-bold text-[#001233] text-right max-w-[60%] leading-tight" x-text="selected.length > 0 ? selected.sort((a,b)=>a-b).join(', ') : '-'"></span>
                                </div>
                                <div class="flex justify-between items-end border-t border-gray-200 pt-4 mt-2">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total Fare</span>
                                    <span class="font-black text-2xl text-[#001233]">PHP <span x-text="totalPrice"></span></span>
                                </div>
                            </div>

                            {{-- FORM SUBMISSION --}}
                            <form action="{{ route('booking.details') }}" method="POST">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                <input type="hidden" name="seats" :value="selected.join(',')">
                                
                                {{-- Dynamic Inputs based on Alpine State --}}
                                <input type="hidden" name="passengers_adult" :value="adults">
                                <input type="hidden" name="passengers_child" :value="children">
                                <input type="hidden" name="trip_type" value="{{ request('trip_type', 'one_way') }}">
                                <input type="hidden" name="return_date" value="{{ request('return_date') }}">
                                @if(request('is_return'))
                                    <input type="hidden" name="is_return" value="1">
                                @endif
                                
                                <button type="submit" 
                                        :disabled="selected.length === 0 || selected.length !== totalPassengers" 
                                        :class="selected.length > 0 && selected.length === totalPassengers ? 'bg-[#001233] hover:bg-blue-900 text-white shadow-lg' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                        class="w-full py-3.5 font-bold rounded-lg transition uppercase text-xs tracking-widest">
                                    Proceed to Details
                                </button>
                            </form>

                            <div class="mt-4 text-center">
                                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-red-500 transition uppercase tracking-wide group">
                                    Cancel Booking
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <footer class="bg-[#001233] text-white py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-sm">© 2025 BusPH. All rights reserved.</p>
            </div>
        </footer>

    </div>

    {{-- DYNAMIC MAP LOGIC --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Coordinate Mapping
            const locations = {
                'Cubao': [14.6195, 121.0511], 
                'Pasay': [14.5378, 121.0014], 
                'Baguio': [16.4023, 120.5960],
                'Legazpi': [13.1391, 123.7438], 
                'Naga': [13.6218, 123.1948], 
                'Batangas': [13.7565, 121.0583], 
                'Manila': [14.5995, 120.9842]
            };

            const originName = '{{ $schedule->route->origin }}';
            const destName = '{{ $schedule->route->destination }}';
            
            // Fallback to Manila if coords not found
            const originCoords = locations[originName] || [14.5995, 120.9842];
            const destCoords = locations[destName] || [14.5995, 120.9842];

            var map = L.map('map', {
                center: originCoords,
                zoom: 6,
                zoomControl: false,
                attributionControl: false
            });

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);

            var busIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/3448/3448339.png', iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32] });
            var pinIcon = L.icon({ iconUrl: 'https://cdn-icons-png.flaticon.com/512/447/447031.png', iconSize: [32, 32], iconAnchor: [16, 32] });

            L.marker(originCoords, {icon: busIcon}).addTo(map).bindPopup(`<b>Start: ${originName}</b>`);
            L.marker(destCoords, {icon: pinIcon}).addTo(map).bindPopup(`<b>End: ${destName}</b>`);

            var routeLine = L.polyline([originCoords, destCoords], { color: '#001233', weight: 4, opacity: 0.8, dashArray: '10, 10', lineCap: 'round' }).addTo(map);

            setTimeout(() => {
                map.invalidateSize();
                map.fitBounds(routeLine.getBounds(), { padding: [40, 40] });
            }, 500);
        });
    </script>
</x-app-layout>