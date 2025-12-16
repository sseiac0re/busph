<x-admin-layout>
    <x-slot name="header">
        Edit Schedule: #{{ $schedule->id }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#001233] mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Schedules
        </a>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-8">
                <h2 class="text-xl font-bold text-[#001233] mb-6">Modify Trip Assignment</h2>
                
                <form method="POST" action="{{ route('admin.schedules.update', $schedule->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Assign Bus</label>
                            <select name="bus_id" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}" {{ $schedule->bus_id == $bus->id ? 'selected' : '' }}>
                                        {{ $bus->bus_number }} ({{ $bus->type }} - {{ $bus->capacity }} seats)
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-2">Current: {{ $schedule->bus->bus_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Select Route</label>
                            <select name="route_id" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}" {{ $schedule->route_id == $route->id ? 'selected' : '' }}>
                                        {{ $route->origin }} → {{ $route->destination }} (₱ {{ number_format($route->price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-2">Current: {{ $schedule->route->origin }} → {{ $schedule->route->destination }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Departure Date</label>
                            <input type="date" name="departure_date" value="{{ $schedule->departure_date }}" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Departure Time</label>
                            <input type="time" name="departure_time" value="{{ $schedule->departure_time_only }}" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                <option value="active" {{ $schedule->status == 'active' ? 'selected' : '' }}>Active (Available for booking)</option>
                                <option value="cancelled" {{ $schedule->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <button type="button" 
                                onclick="if(confirm('Are you sure you want to delete this trip? This action cannot be undone.')) { document.getElementById('delete-schedule-form').submit(); }"
                                class="text-red-500 hover:text-red-700 font-bold text-sm transition flex items-center gap-2 px-4 py-2 rounded hover:bg-red-50">
                            Delete Trip
                        </button>
                        
                        <button class="bg-[#001233] hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-lg shadow-md transition">
                            Update Schedule
                        </button>
                    </div>
                </form>

                <form id="delete-schedule-form" action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>
</x-admin-layout>