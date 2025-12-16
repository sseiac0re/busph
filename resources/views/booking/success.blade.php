<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- RECEIPT CARD --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 print:shadow-none print:border-none">
                
                {{-- HEADER SECTION --}}
                <div class="bg-[#001233] px-8 py-8 text-center text-white relative overflow-hidden">
                    {{-- Decorative Circle --}}
                    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500 rounded-full blur-3xl"></div>
                    </div>

                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg animate-bounce">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black mb-2">Booking Successful!</h2>
                        <p class="text-blue-200">Your transaction has been completed.</p>
                        
                        <div class="mt-6 inline-block bg-white/10 px-6 py-3 rounded-xl border border-white/20 backdrop-blur-sm">
                            <span class="text-xs uppercase tracking-widest opacity-70 block mb-1">Transaction ID</span>
                            <span class="font-mono text-xl font-bold tracking-wider">{{ $id }}</span>
                        </div>
                    </div>
                </div>

                {{-- RECEIPT BODY --}}
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-gray-800 text-lg">Ticket Summary</h3>
                        <button onclick="window.print()" class="text-sm text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 font-bold print:hidden transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6a2 2 0 012-2zm9-2V4a2 2 0 00-2-2H5a2 2 0 00-2 2v6"></path></svg>
                            Print Receipt
                        </button>
                    </div>

                    {{-- 
                        LOGIC: Group reservations by Schedule ID 
                        This separates "Outbound Trip" tickets from "Return Trip" tickets visually.
                    --}}
                    @if(isset($reservations) && $reservations->isNotEmpty())
                        @foreach($reservations->groupBy('schedule_id') as $scheduleId => $tickets)
                            @php $sched = $tickets->first()->schedule; @endphp
                            
                            <div class="mb-8 border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                
                                {{-- Trip Header --}}
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between md:items-center gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 font-black text-[#001233] text-lg">
                                            <span>{{ $sched->route->origin }}</span>
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                            <span>{{ $sched->route->destination }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1 text-sm font-medium text-gray-500">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ \Carbon\Carbon::parse($sched->departure_time)->format('F d, Y') }}
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ \Carbon\Carbon::parse($sched->departure_time)->format('h:i A') }}
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                Bus {{ $sched->bus->code }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="bg-[#001233] text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                            {{ $tickets->count() }} Seat(s)
                                        </span>
                                    </div>
                                </div>

                                {{-- Passenger List --}}
                                <div class="divide-y divide-gray-100">
                                    @foreach($tickets as $ticket)
                                        <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50 transition">
                                            <div class="flex items-center gap-4">
                                                {{-- Icon based on Passenger Type --}}
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $ticket->passenger_type == 'child' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                    {{ ucfirst(substr($ticket->passenger_type, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800">{{ $ticket->passenger_name }}</p>
                                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">
                                                        {{ $ticket->passenger_type }}
                                                        @if($ticket->discount_id_number)
                                                            <span class="text-amber-500 ml-1">• ID: {{ $ticket->discount_id_number }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-bold text-[#001233]">Seat {{ $ticket->seat_number }}</p>
                                                <div class="flex items-center justify-end gap-1 text-green-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider">Confirmed</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No ticket details available. Please contact support.</p>
                        </div>
                    @endif

                    {{-- FOOTER ACTIONS --}}
                    <div class="mt-8 text-center space-y-3 print:hidden">
                        <a href="{{ route('dashboard') }}" class="block w-full bg-[#001233] text-white py-4 rounded-xl font-bold hover:bg-blue-900 transition shadow-lg transform hover:-translate-y-0.5">
                            Go to My Dashboard
                        </a>
                        <a href="{{ route('home') }}" class="block w-full bg-white border-2 border-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-50 hover:text-[#001233] hover:border-gray-200 transition">
                            Book Another Trip
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>