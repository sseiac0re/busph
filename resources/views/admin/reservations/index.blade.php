<x-admin-layout>
    <x-slot name="header">Booking History</x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Booking ID</th>
                        <th class="px-6 py-4">Passenger</th>
                        <th class="px-6 py-4">Journey</th>
                        <th class="px-6 py-4">Seat</th>
                        <th class="px-6 py-4">Date Booked</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reservations as $reservation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-[#001233]">#{{ $reservation->id }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $reservation->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $reservation->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium">{{ $reservation->schedule->route->origin }}</span>
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                <span class="font-medium">{{ $reservation->schedule->route->destination }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('M d, Y • h:i A') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold">Seat {{ $reservation->seat_number }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            {{ $reservation->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="text-[#001233] hover:text-blue-700 font-bold text-xs uppercase tracking-wide">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-400">No reservations found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{-- This renders the pagination links: --}}
            {{ $reservations->withQueryString()->links() }}
        </div>
    </div>
</x-admin-layout>