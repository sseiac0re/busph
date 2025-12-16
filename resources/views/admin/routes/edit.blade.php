<x-admin-layout>
    <x-slot name="header">Edit Route: {{ $route->origin }} ➝ {{ $route->destination }}</x-slot>

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
                    
                    <form method="POST" action="{{ route('admin.routes.update', $route->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Origin Terminal</label>
                            <div class="flex items-center">
                                <span class="bg-green-100 text-green-700 p-2 rounded-l-lg border border-r-0 border-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                <input type="text" name="origin" value="{{ $route->origin }}" class="w-full rounded-r-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Destination Terminal</label>
                            <div class="flex items-center">
                                <span class="bg-red-100 text-red-700 p-2 rounded-l-lg border border-r-0 border-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                <input type="text" name="destination" value="{{ $route->destination }}" class="w-full rounded-r-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ticket Price (PHP)</label>
                            <input type="number" step="0.01" name="price" value="{{ $route->price }}" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                        </div>

                        <input type="hidden" name="origin_lat" id="origin_lat" value="{{ $route->origin_lat }}">
                        <input type="hidden" name="origin_lng" id="origin_lng" value="{{ $route->origin_lng }}">
                        <input type="hidden" name="destination_lat" id="destination_lat" value="{{ $route->destination_lat }}">
                        <input type="hidden" name="destination_lng" id="destination_lng" value="{{ $route->destination_lng }}">

                        <div class="flex flex-col gap-3 pt-4 border-t border-gray-100">
                            <button type="submit" class="w-full bg-[#001233] hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-lg shadow-md transition">
                                Update Route
                            </button>
                            
                            <button type="button" 
                                    onclick="if(confirm('Delete this route?')) { document.getElementById('delete-route-form').submit(); }"
                                    class="w-full text-red-500 hover:text-red-700 font-bold py-2 rounded-lg hover:bg-red-50 transition text-sm">
                                Delete Route
                            </button>
                        </div>
                    </form>

                    <form id="delete-route-form" action="{{ route('admin.routes.destroy', $route->id) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>

                </div>
            </div>

            <div class="w-full lg:w-2/3">
                <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col">
                    <div class="p-4 border-b border-gray-100 mb-2">
                        <h3 class="font-bold text-gray-800">Map Selector</h3>
                        <p class="text-xs text-gray-500">Drag the markers to adjust locations.</p>
                    </div>
                    
                    <div id="map" class="w-full h-96 lg:h-full rounded-lg min-h-[400px] z-0"></div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. Get Existing Data or Defaults
        var originLat = {{ $route->origin_lat ?? 14.6178 }};
        var originLng = {{ $route->origin_lng ?? 121.0572 }};
        var destLat = {{ $route->destination_lat ?? 16.4023 }};
        var destLng = {{ $route->destination_lng ?? 120.5960 }};

        // 2. Initialize Map
        var map = L.map('map');
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Fit map to show both points
        var bounds = L.latLngBounds([[originLat, originLng], [destLat, destLng]]);
        map.fitBounds(bounds, {padding: [50, 50]});

        // 3. Custom Icons
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

        // 4. Create Markers
        var originMarker = L.marker([originLat, originLng], {draggable: true, icon: greenIcon}).addTo(map);
        originMarker.bindPopup("<b>Origin</b>").openPopup();

        var destMarker = L.marker([destLat, destLng], {draggable: true, icon: redIcon}).addTo(map);
        destMarker.bindPopup("<b>Destination</b>");

        // 5. Draw Initial Line
        var polyline = L.polyline([[originLat, originLng], [destLat, destLng]], {color: '#001233', weight: 3, dashArray: '10, 10'}).addTo(map);

        // 6. Update Function
        function updateMap() {
            var originPos = originMarker.getLatLng();
            var destPos = destMarker.getLatLng();

            // Update Inputs
            document.getElementById('origin_lat').value = originPos.lat;
            document.getElementById('origin_lng').value = originPos.lng;
            document.getElementById('destination_lat').value = destPos.lat;
            document.getElementById('destination_lng').value = destPos.lng;

            // Redraw Line
            if(polyline) map.removeLayer(polyline);
            polyline = L.polyline([originPos, destPos], {color: '#001233', weight: 3, dashArray: '10, 10'}).addTo(map);
        }

        // Listeners
        originMarker.on('drag', updateMap);
        destMarker.on('drag', updateMap);

    </script>
</x-admin-layout>