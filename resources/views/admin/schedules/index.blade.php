<x-admin-layout>
    <x-slot name="header">
        Trip Schedules
    </x-slot>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm relative" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        
        {{-- 1. SEARCH BAR (Kept as is) --}}
        <div class="relative w-full md:w-96">
            <input type="text" placeholder="Search bus or route..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#001233] focus:border-[#001233] transition text-sm">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

{{-- 2. ACTION BUTTONS --}}
        <div class="flex gap-3 flex-shrink-0" x-data="{ showDeleteModal: false }">
            
            {{-- A. TRIGGER BUTTON --}}
            <button @click="showDeleteModal = true" type="button" class="bg-white border border-red-200 text-red-600 font-bold py-2.5 px-4 rounded-lg hover:bg-red-50 hover:border-red-300 transition shadow-sm flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Clear Empty
            </button>

            {{-- B. THE CUSTOM CONFIRMATION MODAL --}}
            {{-- We use x-show to hide/show it. x-cloak prevents flickering on load. --}}
            <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
                
                {{-- 1. Backdrop (Dark Overlay) --}}
                <div x-show="showDeleteModal" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showDeleteModal = false" 
                     class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm"></div>

                {{-- 2. Modal Panel --}}
                <div x-show="showDeleteModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                     class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative z-10 border border-gray-100">
                    
                    {{-- Warning Icon --}}
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 mb-6">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>

                    {{-- Text Content --}}
                    <div class="text-center">
                        <h3 class="text-xl font-black text-[#001233] mb-2">Delete Empty Schedules?</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            This will permanently remove <strong class="text-gray-800">ALL trips</strong> that have zero bookings. 
                            <br><br>
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold uppercase">Safe Note</span> 
                            Trips with active passengers will NOT be deleted.
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                            Cancel
                        </button>
                        
                        {{-- The Actual Form --}}
                        <form action="{{ route('admin.schedules.deleteAll') }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 shadow-lg hover:shadow-red-500/30 transition flex justify-center items-center gap-2">
                                Yes, Delete All
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Create New Schedule Button --}}
            <a href="{{ route('admin.schedules.create') }}" class="bg-[#001233] hover:bg-blue-900 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Schedule New Trip
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Trip Details</th>
                        <th class="px-6 py-4">Bus / Plate</th>
                        <th class="px-6 py-4">Route / Price</th>
                        <th class="px-6 py-4">Departure Time</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $schedule)
                    <tr class="hover:bg-gray-50 transition group">
                        
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800 text-base">Trip #{{ $schedule->id }}</p>
                            <p class="text-xs text-gray-400">Code: BC{{ $schedule->id }}</p>
                            <p class="text-xs text-gray-400 mt-1">Created: {{ $schedule->created_at->format('M d, Y') }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-[#001233]">{{ $schedule->bus->bus_number }} ({{ $schedule->bus->type }})</p>
                            <p class="text-xs text-gray-500">Plate: {{ $schedule->bus->plate_number ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Capacity: {{ $schedule->bus->capacity }} seats</p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">
                                {{ $schedule->route->origin }} → {{ $schedule->route->destination }}
                            </p>
                            <p class="text-xs text-gray-500">Fare: ₱ {{ number_format($schedule->route->price, 2) }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('h:i A') }}</p>
                        </td>

                        <td class="px-6 py-4">
                            @if(\Carbon\Carbon::parse($schedule->departure_time)->isFuture())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                    Scheduled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                    Completed
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit Schedule">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
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
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-lg font-medium text-gray-500">No trips scheduled yet.</p>
                            <p class="text-sm text-gray-400 mt-1">Click the button above to schedule your first trip.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>