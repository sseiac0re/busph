<x-admin-layout>
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm relative" role="alert">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="font-bold">Success</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif
    <x-slot name="header">
        Route Network
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <div class="flex flex-col gap-4 mb-6">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            
            <form method="GET" action="{{ route('admin.routes.index') }}" class="flex-grow w-full md:w-auto min-w-[250px]">
                <div class="relative">
                    <input type="text" name="search" placeholder="Search routes by terminal name..." 
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#001233] focus:border-[#001233] transition text-sm">
                    <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </form>

            <a href="{{ route('admin.routes.create') }}" class="bg-[#001233] hover:bg-blue-900 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition flex items-center gap-2 flex-shrink-0 w-full md:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add New Route
            </a>
        </div>
        
        <form method="GET" action="{{ route('admin.routes.index') }}" class="w-full">
            <div class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <select name="origin_filter" onchange="this.form.submit()" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-[#001233] focus:border-[#001233] bg-white w-full sm:w-auto min-w-[140px]">
                    <option value="">Filter by Origin</option>
                    @foreach($origins as $origin)
                        <option value="{{ $origin }}" {{ request('origin_filter') == $origin ? 'selected' : '' }}>{{ $origin }}</option>
                    @endforeach
                </select>

                <select name="destination_filter" onchange="this.form.submit()" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-[#001233] focus:border-[#001233] bg-white w-full sm:w-auto min-w-[170px]">
                    <option value="">Filter by Destination</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination }}" {{ request('destination_filter') == $destination ? 'selected' : '' }}>{{ $destination }}</option>
                    @endforeach
                </select>

                <select name="sort_by" onchange="this.form.submit()" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-[#001233] focus:border-[#001233] bg-white w-full sm:w-auto min-w-[170px]">
                    <option value="origin_asc" {{ request('sort_by') == 'origin_asc' ? 'selected' : '' }}>Sort By Origin (A-Z)</option>
                    <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                </select>

                @if(request('search') || request('origin_filter') || request('destination_filter') || (request('sort_by') && request('sort_by') != 'origin_asc'))
                    <a href="{{ route('admin.routes.index') }}" class="py-2 px-3 text-red-600 hover:text-red-800 transition text-sm font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Reset Filters
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-100 mb-8 relative group">
        <div id="map" class="h-96 w-full rounded-lg z-0"></div>
        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-md shadow text-xs font-bold text-gray-500 z-10 pointer-events-none">
            Hover over a route to visualize
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                <tr>
                    <th class="px-6 py-4">Origin Terminal</th>
                    <th class="px-6 py-4">Destination Terminal</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($routes as $route)
                <tr onmouseenter="highlightRoute({{ $route->id }})" 
                    onmouseleave="resetMap()"
                    class="hover:bg-blue-50 transition group cursor-pointer border-l-4 border-transparent hover:border-[#001233]">
                    
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-50 rounded-full text-[#001233] group-hover:bg-[#001233] group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $route->origin }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-red-50 rounded-full text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $route->destination }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @if($route->origin_lat && $route->destination_lat)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span> Map Ready
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-500 rounded-full"></span> No Coords
                            </span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-lg font-bold text-[#001233]">₱ {{ number_format($route->price, 2) }}</span>
                    </td>

                    <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.routes.edit', $route->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Route">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.routes.destroy', $route->id) }}" method="POST" onsubmit="return confirm('Delete this route?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">No routes found matching your search.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $routes->withQueryString()->links() }}
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // 1. Initialize Map
        var map = L.map('map', {zoomControl: false}).setView([12.8797, 121.7740], 6); // Default PH View

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        // Load Route Data from PHP
        var routesData = @json($routes->items()); 
        
        // Storage for map objects
        var currentPolyline = null;
        var currentMarkers = [];

        // 2. Highlight Function (Triggered on Mouse Enter)
        function highlightRoute(routeId) {
            // Find the route data
            const route = routesData.find(r => r.id === routeId);

            if (route && route.origin_lat && route.origin_lng && route.destination_lat && route.destination_lng) {
                // Clear previous
                resetMap();

                // Draw Markers
                var origin = L.marker([route.origin_lat, route.origin_lng]).addTo(map)
                    .bindPopup(`<b>${route.origin}</b>`).openPopup();
                
                var dest = L.marker([route.destination_lat, route.destination_lng]).addTo(map)
                    .bindPopup(`<b>${route.destination}</b>`);

                currentMarkers.push(origin, dest);

                // Draw Line
                var latlngs = [
                    [route.origin_lat, route.origin_lng],
                    [route.destination_lat, route.destination_lng]
                ];

                currentPolyline = L.polyline(latlngs, {
                    color: '#001233', // Navy Blue
                    weight: 4,
                    opacity: 0.9,
                    dashArray: '10, 10', // Dashed line effect
                    lineCap: 'round'
                }).addTo(map);

                // Zoom to fit the line
                map.flyToBounds(latlngs, {
                    padding: [50, 50],
                    duration: 1.5 // Smooth fly animation
                });
            }
        }

        // 3. Reset Function (Triggered on Mouse Leave)
        function resetMap() {
            if (currentPolyline) {
                map.removeLayer(currentPolyline);
                currentPolyline = null;
            }
            currentMarkers.forEach(marker => map.removeLayer(marker));
            currentMarkers = [];
        }

        // 4. FIX: Ensure map renders correctly after DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => { map.invalidateSize(); }, 300);
        });
    </script>
</x-admin-layout>