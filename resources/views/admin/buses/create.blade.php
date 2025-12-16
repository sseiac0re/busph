<x-admin-layout>
    <x-slot name="header">
        Add New Bus
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <a href="{{ route('admin.buses.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-[#001233] mb-6 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Fleet
        </a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <h2 class="text-xl font-bold text-[#001233] mb-6">Bus Information</h2>
                
                <form method="POST" action="{{ route('admin.buses.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        {{-- BUS NUMBER --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bus Number</label>
                            <input type="text" 
                                   name="bus_number" 
                                   value="{{ old('bus_number') }}" 
                                   placeholder="e.g. BUS-101" 
                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                            @error('bus_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PLATE NUMBER --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Plate Number</label>
                            <input type="text" 
                                   name="plate_number" 
                                   value="{{ old('plate_number') }}" 
                                   placeholder="e.g. ABC-1234" 
                                   style="text-transform: uppercase;"
                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                            @error('plate_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- BUS TYPE --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bus Type</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                <option value="Standard" {{ old('type') == 'Standard' ? 'selected' : '' }}>Standard (Non-AC)</option>
                                <option value="Deluxe" {{ old('type') == 'Deluxe' ? 'selected' : '' }}>Deluxe (AC)</option>
                                <option value="Luxury" {{ old('type') == 'Luxury' ? 'selected' : '' }}>Luxury (Sleeper)</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CAPACITY --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Seat Capacity</label>
                            <input type="number" 
                                   name="capacity" 
                                   value="{{ old('capacity') }}" 
                                   placeholder="e.g. 45" 
                                   min="10" max="80"
                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]" required>
                            @error('capacity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- OPERATOR --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Operator (Optional)</label>
                            <input type="text" 
                                   name="operator" 
                                   value="{{ old('operator') }}" 
                                   placeholder="BusPH Inc." 
                                   class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                            @error('operator')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- STATUS --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Current Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-[#001233] focus:ring-[#001233]">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Ready for trips)</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance (Unavailable)</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                        <button class="bg-[#001233] hover:bg-blue-900 text-white font-bold py-3 px-8 rounded-lg shadow-md transition">
                            Save Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>