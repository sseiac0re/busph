<x-admin-layout>
    <x-slot name="header">Add New Route</x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="max-w-6xl mx-auto">
        
        <a href="{{ route('admin.routes.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#001233] mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Routes
        </a>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="w-full lg:w-1/3">
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-[#001233] mb-6">Route Details</h2>
                    
                    <form method="POST" action="{{ route('admin.routes.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Origin Terminal</label>
                            <div class="flex items-center">
                                <span class="bg-green-100 text-green-700 p-2 rounded-l-lg border border-r-0 border-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                <input type="text" name="origin" placeholder="e.g. Cubao" class="w-full rounded-r-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Destination Terminal</label>
                            <div class="flex items-center">
                                <span class="bg-red-100 text-red-700 p-2 rounded-l-lg border border-r-0 border-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                <input type="text" name="destination" placeholder="e.g. Baguio" class="w-full rounded-r-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ticket Price (PHP)</label>
                            <input type="number" step="0.01" name="price" placeholder="500.00" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                        </div>

                        <input type="hidden" name="origin_lat" id="origin_lat">
                        <input type="hidden" name="origin_lng" id="origin_lng">
                        <input type="hidden" name="destination_lat" id="destination_lat">
                        <input type="hidden" name="destination_lng" id="destination_lng">

                        <button class="w-full bg-[#001233] hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-lg shadow-md transition">
                            Save Route
                        </button>
                    </form>
                </div>
            </div>

            <div class="w-full lg:w-2/3">
                <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col">
                    <div class="p-4 border-b border-gray-100 mb-2">
                        <h3 class="font-bold text-gray-800">Map Selector</h3>
                        <p class="text-xs text-gray-500">Drag the markers to pinpoint the terminal locations.</p>
                        <div class="flex gap-4 mt-2 text-xs font-bold">
                            <span class="flex items-center gap-1 text-green-600"><span class="w-3 h-3 bg-green-500 rounded-full"></span> Origin (Start)</span>
                            <span class="flex items-center gap-1 text-red-600"><span class="w-3 h-3 bg-red-500 rounded-full"></span> Destination (End)</span>
                        </div>
                    </div>
                    
                    <div id="map" class="w-full h-96 lg:h-full rounded-lg min-h-[400px] z-0"></div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. Initialize Map (Center on Philippines)
        var map = L.map('map').setView([14.5995, 120.9842], 7); // Default to Manila

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // 2. Custom Icons
        var greenIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });

        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
        });

        // 3. Create Draggable Markers
        // Origin (Green) - Default: Cubao Area
        var originMarker = L.marker([14.6178, 121.0572], {draggable: true, icon: greenIcon}).addTo(map);
        originMarker.bindPopup("<b>Origin</b><br>Drag me!").openPopup();

        // Destination (Red) - Default: Baguio Area
        var destMarker = L.marker([16.4023, 120.5960], {draggable: true, icon: redIcon}).addTo(map);
        destMarker.bindPopup("<b>Destination</b><br>Drag me!");

        // 4. Function to Update Hidden Inputs
        function updateInputs() {
            var originPos = originMarker.getLatLng();
            var destPos = destMarker.getLatLng();

            document.getElementById('origin_lat').value = originPos.lat;
            document.getElementById('origin_lng').value = originPos.lng;
            document.getElementById('destination_lat').value = destPos.lat;
            document.getElementById('destination_lng').value = destPos.lng;
        }

        // 5. Listen for Drag Events
        originMarker.on('dragend', updateInputs);
        destMarker.on('dragend', updateInputs);

        // Run once on load to set defaults
        updateInputs();
    </script>

</x-admin-layout>