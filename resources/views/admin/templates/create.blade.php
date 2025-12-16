<x-admin-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                <div class="mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-[#001233]">Create Schedule Rule</h2>
                    <p class="text-gray-500 text-sm">Define how the system should auto-generate trips.</p>
                </div>

                <form action="{{ route('admin.templates.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- 1. Bus Selection --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Assign Bus</label>
                            <select name="bus_id" class="w-full rounded-lg border-gray-300 focus:ring-[#001233]">
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}">{{ $bus->code }} - {{ $bus->type }} ({{ $bus->capacity }} seats)</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Route Selection --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Assign Route</label>
                            <select name="route_id" class="w-full rounded-lg border-gray-300 focus:ring-[#001233]">
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->origin }} &rarr; {{ $route->destination }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        {{-- 3. Time Window --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">First Trip</label>
                            <input type="time" name="start_time" value="06:00" class="w-full rounded-lg border-gray-300 focus:ring-[#001233]">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Last Trip</label>
                            <input type="time" name="end_time" value="20:00" class="w-full rounded-lg border-gray-300 focus:ring-[#001233]">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Frequency (Minutes)</label>
                            <input type="number" name="frequency_minutes" value="60" min="30" class="w-full rounded-lg border-gray-300 focus:ring-[#001233]">
                            <p class="text-xs text-gray-400 mt-1">e.g., 60 = Every 1 hour</p>
                        </div>
                    </div>

                    {{-- 4. Active Days (Checkboxes) --}}
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-4">Active Days</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 px-4 py-2 rounded-lg border hover:border-[#001233] transition">
                                    <input type="checkbox" name="active_days[]" value="{{ $day }}" checked class="rounded text-[#001233] focus:ring-[#001233]">
                                    <span class="text-sm font-bold text-gray-700">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.templates.index') }}" class="px-6 py-3 rounded-lg text-gray-500 font-bold hover:bg-gray-100">Cancel</a>
                        <button type="submit" class="bg-[#001233] text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-900 shadow-lg transition">
                            Save & Create Rule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>