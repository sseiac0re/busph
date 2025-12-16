<x-admin-layout>
    <x-slot name="header">Edit Bus: {{ $bus->bus_number }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <a href="{{ route('admin.buses.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#001233] mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Fleet
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('admin.buses.update', $bus->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bus Number</label>
                            <input type="text" name="bus_number" value="{{ $bus->bus_number }}" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Plate Number</label>
                            <input type="text" name="plate_number" value="{{ $bus->plate_number }}" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bus Type</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                <option value="Standard" {{ $bus->type == 'Standard' ? 'selected' : '' }}>Standard (Non-AC)</option>
                                <option value="Deluxe" {{ $bus->type == 'Deluxe' ? 'selected' : '' }}>Deluxe (AC)</option>
                                <option value="Luxury" {{ $bus->type == 'Luxury' ? 'selected' : '' }}>Luxury (Sleeper)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Capacity</label>
                            <input type="number" name="capacity" value="{{ $bus->capacity }}" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Operator</label>
                            <input type="text" name="operator" value="{{ $bus->operator }}" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                <option value="active" {{ $bus->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="maintenance" {{ $bus->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        
                        <button type="button" 
                                onclick="if(confirm('Are you sure you want to delete this bus? This action cannot be undone.')) { document.getElementById('delete-bus-form').submit(); }"
                                class="text-red-500 hover:text-red-700 font-bold text-sm transition flex items-center gap-2 px-4 py-2 rounded hover:bg-red-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Delete Bus
                        </button>

                        <button type="submit" class="bg-[#001233] hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-lg shadow-md transition">
                            Update Bus
                        </button>
                    </div>
                </form>

                <form id="delete-bus-form" action="{{ route('admin.buses.destroy', $bus->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>
</x-admin-layout>