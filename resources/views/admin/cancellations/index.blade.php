<x-admin-layout>
    <div class="p-6">
        <div class="max-w-7xl mx-auto space-y-8">
            
            {{-- HEADER --}}
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-black text-[#001233]">Cancellation Management</h2>
                <span class="text-gray-500 text-sm">Overview of refund requests</span>
            </div>

            {{-- SECTION 1: PENDING REQUESTS (Action Required) --}}
            <div class="bg-white rounded-xl shadow-lg border border-red-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex items-center gap-3">
                    <div class="bg-red-100 text-red-600 p-2 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-red-800 text-lg">Pending Requests (Action Required)</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">Booking ID</th>
                                <th class="px-6 py-4 font-bold">Passenger</th>
                                <th class="px-6 py-4 font-bold">Route & Date</th>
                                <th class="px-6 py-4 font-bold">Reason</th>
                                <th class="px-6 py-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pendingCancellations as $reservation)
                                <tr class="hover:bg-red-50/50 transition">
                                    <td class="px-6 py-4 font-mono text-sm font-bold text-[#001233]">{{ $reservation->transaction_id ?? $reservation->id }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">{{ $reservation->passenger_name ?? $reservation->first_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $reservation->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $reservation->schedule->route->origin }} &rarr; {{ $reservation->schedule->route->destination }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('M d, Y h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 italic max-w-xs truncate">
                                        "{{ $reservation->cancellation_reason }}"
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <form action="{{ route('admin.cancellations.approve', $reservation->id) }}" method="POST" class="inline-block">
                                            @csrf @method('PUT')
                                            <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs font-bold hover:bg-green-700 transition shadow-sm">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.cancellations.reject', $reservation->id) }}" method="POST" class="inline-block">
                                            @csrf @method('PUT')
                                            <button type="submit" class="bg-gray-200 text-gray-700 px-3 py-1.5 rounded text-xs font-bold hover:bg-gray-300 transition">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        No pending cancellation requests.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SECTION 2: HISTORY (Approved/Rejected) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                    <div class="bg-gray-200 text-gray-600 p-2 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-700 text-lg">Cancellation History</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-4 font-bold">Booking ID</th>
                                <th class="px-6 py-4 font-bold">Passenger</th>
                                <th class="px-6 py-4 font-bold">Processed Date</th>
                                <th class="px-6 py-4 font-bold">Status</th>
                                <th class="px-6 py-4 font-bold text-right">Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($historyCancellations as $history)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $history->transaction_id ?? $history->id }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-[#001233]">
                                        {{ $history->passenger_name ?? $history->first_name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $history->updated_at->format('M d, Y') }}
                                        <span class="text-xs text-gray-400 block">{{ $history->updated_at->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($history->cancellation_status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase tracking-wide">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Approved
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 uppercase tracking-wide">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-bold text-gray-800">
                                        @if($history->cancellation_status === 'approved')
                                            @php 
                                                // Calculate refund (assuming 100% refund logic, adjust if you have penalties)
                                                $price = $history->schedule->route->price;
                                                if($history->discount_id_number) $price *= 0.80;
                                            @endphp
                                            ₱ {{ number_format($price, 2) }}
                                        @else
                                            <span class="text-gray-300">₱ 0.00</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                                        No history available.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>