<x-admin-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div class="bg-gray-100 min-h-screen py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-[#001233]">Ticket Verification</h1>
                <p class="text-gray-500">Enter Transaction ID or Scan QR Code</p>
            </div>

            {{-- 1. CAMERA CONTAINER --}}
            <div id="reader-container" class="hidden mb-6 bg-black rounded-xl overflow-hidden shadow-2xl relative">
                <div id="reader" class="w-full"></div>
                <button onclick="stopCamera()" class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg z-10">
                    Close Camera
                </button>
            </div>

            {{-- 2. BUTTONS OUTSIDE FORM (The Fix) --}}
            <div class="mb-6 flex justify-center">
                <button type="button" onclick="startCamera()" class="bg-gray-800 text-white font-bold py-4 px-8 rounded-full shadow-lg hover:bg-gray-900 transition flex items-center justify-center gap-2 transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Scan QR Code (Camera)
                </button>
            </div>

            {{-- 3. THE FORM (Separate) --}}
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200 mb-8">
                <form id="verify-form" action="{{ route('admin.verify.check') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <input type="text" name="ticket_id" id="ticket_id" required 
                               class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-[#001233] focus:border-[#001233] sm:text-lg font-bold tracking-widest uppercase" 
                               placeholder="OR TYPE ID HERE">
                    </div>

                    <button type="submit" class="bg-[#001233] text-white font-bold py-4 px-6 rounded-lg hover:bg-blue-900 transition flex items-center justify-center gap-2">
                        Verify Ticket
                    </button>
                </form>
            </div>

            {{-- Results Section --}}
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg shadow-sm flex items-center gap-4">
                    <div class="bg-red-100 p-3 rounded-full text-red-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-red-800">Invalid Ticket</h3>
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if(session('success') && session('ticket'))
                @php $ticket = session('ticket'); @endphp
                <div class="bg-green-50 border-l-4 border-green-500 p-8 rounded-lg shadow-md relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-green-100 p-3 rounded-full text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-green-800">Ticket Valid</h3>
                            <p class="text-green-700 font-medium">Ready for boarding</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-green-200 pt-6">
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Passenger</p>
                            <p class="text-xl font-bold text-gray-800">{{ $ticket->passenger_name ?? $ticket->first_name . ' ' . $ticket->surname }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Seat</p>
                            <p class="text-xl font-bold text-gray-800">{{ $ticket->seat_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Route</p>
                            <p class="text-lg font-bold text-gray-800">{{ $ticket->schedule->route->origin }} &rarr; {{ $ticket->schedule->route->destination }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Date</p>
                            <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($ticket->schedule->departure_time)->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <audio id="scan-sound" preload="auto">
        <source src="https://actions.google.com/sounds/v1/transportation/bus_honk.ogg" type="audio/ogg">
        <source src="https://actions.google.com/sounds/v1/transportation/bus_honk.mp3" type="audio/mpeg">
    </audio>
    <script>
        let html5QrcodeScanner;
        let isSubmitting = false;

        function startCamera() {
            isSubmitting = false;
            document.getElementById('reader-container').classList.remove('hidden');

            html5QrcodeScanner = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                () => {}
            );
        }

        function onScanSuccess(decodedText) {
            if (isSubmitting) return;
            isSubmitting = true;

            if (navigator.vibrate) navigator.vibrate(200);

            const sound = document.getElementById('scan-sound');
            if (sound) {
                sound.volume = 1.0; // MAX volume
                sound.currentTime = 0;

                sound.play().then(() => {
                    // second honk after a short delay
                    setTimeout(() => {
                        sound.currentTime = 0;
                        sound.play().catch(() => {});
                    }, 300);
                }).catch(() => {});
            }

            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                document.getElementById('reader-container').classList.add('hidden');
            });

            let ticketID = decodedText;
            if (decodedText.includes("Ticket ID:")) {
                const match = decodedText.match(/Ticket ID:\s*([^\s]+)/);
                if (match) ticketID = match[1];
            }

            document.getElementById('ticket_id').value = ticketID;

            const btn = document.querySelector('button[type="submit"]');
            btn.innerHTML = 'Verifying...';
            btn.classList.add('opacity-50', 'cursor-not-allowed');

            document.getElementById('verify-form').submit();
        }


        function stopCamera() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('reader-container').classList.add('hidden');
                    html5QrcodeScanner.clear();
                }).catch(err => {
                    console.log("Failed to stop camera", err);
                    document.getElementById('reader-container').classList.add('hidden');
                });
            } else {
                document.getElementById('reader-container').classList.add('hidden');
            }
        }
    </script>
</x-admin-layout>