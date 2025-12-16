<x-app-layout>
    {{-- PRINT STYLES --}}
    <style>
        @media print {
            @page { size: A4 portrait; margin: 0 !important; }
            body * { visibility: hidden !important; }
            #print-area, #print-area * { visibility: visible !important; }
            #print-area {
                position: absolute; top: 0; left: 0; right: 0; margin: 0 auto; width: 100%;
                padding: 10mm; background-color: white; 
                display: flex; justify-content: center; align-items: flex-start;
            }
            .print\:hidden { display: none !important; }
        }
    </style>

    {{-- SCRIPTS FOR QR CODE & DOWNLOAD --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Back Button --}}
            <a href="{{ route('user.bookings.index') }}" class="print:hidden inline-flex items-center text-sm text-gray-500 hover:text-[#001233] mb-6 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to My Bookings
            </a>

            {{-- RECEIPT SECTION --}}
            <div id="print-area" class="flex flex-col items-center justify-center gap-6">
                
                {{-- VISUAL TICKET CARD (Added ID 'ticket-card' for Image Download) --}}
                <div id="ticket-card" class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden relative border border-gray-200">
                    
                    {{-- TOP DECORATION --}}
                    <div class="h-2 bg-[#001233] w-full"></div>

                    {{-- HEADER --}}
                    <div class="px-8 py-6 text-center border-b border-dashed border-gray-200">
                        <div class="flex items-center justify-center gap-3 mb-2">
                            <img src="{{ asset('images/logo.png') }}" alt="BusPH" class="h-8 w-auto bg-blue-900 p-1 rounded-md"/>
                            <span class="font-bold text-2xl text-[#001233] leading-none mt-1">BusPH</span>
                        </div>
                        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Electronic Ticket</h2>
                    </div>

                    {{-- TICKET BODY --}}
                    <div class="px-8 py-8 space-y-6">
                        
                        {{-- ROUTE --}}
                        <div class="text-center">
                            <div class="flex items-center justify-center gap-4 text-2xl font-black text-[#001233]">
                                <span>{{ $reservation->schedule->route->origin }}</span>
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                <span>{{ $reservation->schedule->route->destination }}</span>
                            </div>
                            <p class="text-sm text-gray-500 font-medium mt-1">One-Way Trip</p>
                        </div>

                        {{-- DETAILS GRID --}}
                        <div class="grid grid-cols-2 gap-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400">Date</p>
                                <p class="font-bold text-[#001233] text-sm">{{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400">Time</p>
                                <p class="font-bold text-[#001233] text-sm">{{ \Carbon\Carbon::parse($reservation->schedule->departure_time)->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400">Bus No.</p>
                                <p class="font-bold text-[#001233] text-sm">{{ $reservation->schedule->bus->bus_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400">Seat</p>
                                <p class="font-bold text-[#001233] text-lg">{{ $reservation->seat_number }}</p>
                            </div>
                        </div>

                        {{-- PASSENGER INFO --}}
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Passenger Name</p>
                            <p class="font-bold text-lg text-[#001233] border-b border-gray-100 pb-2">
                                {{ $reservation->passenger_name ?? $reservation->first_name . ' ' . $reservation->surname }}
                            </p>
                        </div>

                        {{-- LOGIC: Calculate Discount --}}
                        @php
                            $basePrice = $reservation->schedule->route->price;
                            $finalPrice = $basePrice;
                            $hasDiscount = !empty($reservation->discount_id_number);
                            
                            if($hasDiscount) {
                                $finalPrice = $basePrice * 0.80; // 20% Off
                            }
                        @endphp

                        {{-- PAYMENT INFO --}}
                        <div class="flex justify-between items-center pt-2">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400">Payment Status</p>
                                <span class="text-green-600 font-bold text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Paid
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] uppercase font-bold text-gray-400">Total Price</p>
                                
                                @if($hasDiscount)
                                    <p class="text-xs text-gray-400 line-through">₱ {{ number_format($basePrice, 2) }}</p>
                                    <p class="text-2xl font-black text-green-600">₱ {{ number_format($finalPrice, 2) }}</p>
                                    <span class="text-[10px] text-green-600 font-bold">20% OFF</span>
                                @else
                                    <p class="text-2xl font-black text-[#001233]">₱ {{ number_format($basePrice, 2) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- CUTOUT CIRCLES --}}
                    <div class="relative flex items-center justify-between px-4">
                        <div class="w-6 h-6 bg-gray-100 rounded-full -ml-3"></div>
                        <div class="w-full border-b-2 border-dashed border-gray-200"></div>
                        <div class="w-6 h-6 bg-gray-100 rounded-full -mr-3"></div>
                    </div>

                    {{-- QR FOOTER (Updated for Real QR) --}}
                    <div class="bg-white px-8 py-6 text-center">
                        <div class="mb-4 flex justify-center opacity-90">
                            {{-- REAL QR CONTAINER --}}
                            <div id="qrcode" class="p-2 bg-white rounded border border-gray-100"></div>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Scan at the terminal for boarding.</p>
                        <p class="text-[10px] text-gray-300">Transaction ID: {{ $reservation->transaction_id ?? $reservation->id }}</p>
                    </div>
                </div>

                {{-- TRANSACTION DETAILS --}}
                <div class="print:hidden w-full max-w-md bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-[#001233] uppercase mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Transaction Details
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500">Payment Method</span>
                            <span class="font-bold text-[#001233] capitalize">{{ $reservation->payment_method ?? 'GCash' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500">Transaction Date</span>
                            <span class="font-bold text-[#001233]">{{ $reservation->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span class="text-gray-500">Status</span>
                            <span class="font-bold text-green-600 uppercase text-xs bg-green-50 px-2 py-1 rounded">Confirmed</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CANCELLATION / ACTIONS --}}
            @php
                $departureDate = \Carbon\Carbon::parse($reservation->schedule->departure_time);
    
                // FIX: Allow cancellation if status is NULL (empty) OR 'none'
                $hasNoCancellation = is_null($reservation->cancellation_status) || $reservation->cancellation_status === 'none';
                
                // Only allow if trip is in future AND no active cancellation
                $canCancel = $departureDate->isFuture() && $hasNoCancellation;

                $isPending = $reservation->cancellation_status === 'pending';
                $isRejected = $reservation->cancellation_status === 'rejected'
            @endphp

            <div class="print:hidden mt-8 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="flex justify-end gap-3 mb-6 border-b border-gray-100 pb-6">
                    {{-- NEW: DOWNLOAD BUTTON --}}
                    <button onclick="downloadTicket()" class="bg-[#001233] text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-900 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Ticket
                    </button>

                    <button onclick="window.print()" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-lg hover:bg-gray-300 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print
                    </button>
                </div>

                <h3 class="text-xl font-bold text-red-600 mb-4">Cancellation Request</h3>

                @if ($isPending)
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg">
                        <p class="font-bold">Request Pending</p>
                        <p>Currently under review.</p>
                    </div>
                @elseif ($isRejected)
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                        <p class="font-bold">Request Rejected</p>
                    </div>
                @elseif (!$canCancel)
                     <div class="bg-gray-50 border-l-4 border-gray-500 text-gray-700 p-4 rounded-lg">
                        Booking complete or past cancellation window.
                    </div>
                @elseif ($canCancel)
                    <form method="POST" action="{{ route('user.bookings.cancel', $reservation->id) }}" class="space-y-4">
                        @csrf
                        <p class="text-gray-700">Are you sure you want to cancel this booking?</p>
                        
                        <div>
                            <label for="cancellation_reason" class="block text-sm font-bold text-gray-700 mb-2">
                                Reason for Cancellation (Required)
                            </label>
                            <textarea name="cancellation_reason" id="cancellation_reason" rows="4" 
                                      class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" 
                                      required></textarea>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700 transition">
                                Request Cancellation
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    {{-- JS Logic for QR & Download --}}
    <script>
    // 1. Get Data from Blade
    const passengerName = "{{ $reservation->passenger_name ?? $reservation->first_name . ' ' . $reservation->surname }}";
    const ticketID = "{{ $reservation->transaction_id ?? $reservation->id }}";
    
    // 2. Create Simple Text String (Easy to Scan)
    // This format is readable by humans when scanned
    const qrText = `BusPH TICKET\n----------------\nName: ${passengerName}\nTicket ID: ${ticketID}\n----------------\nVALID`;

    // 3. Generate Clean QR Code
    const qrcode = new QRCode(document.getElementById("qrcode"), {
        text: qrText,
        width: 150,
        height: 150,
        colorDark : "#001233",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.M // Medium correction is safe for text
    });

    // 4. Download Function (Saves the beautiful card visuals)
    function downloadTicket() {
        const ticketElement = document.getElementById('ticket-card');
        
        html2canvas(ticketElement, { 
            scale: 3, // Higher quality image
            useCORS: true 
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'BusPH-Ticket-' + ticketID + '.png';
            link.href = canvas.toDataURL("image/png");
            link.click();
        });
    }
</script>
</x-app-layout>