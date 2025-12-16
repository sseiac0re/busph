<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Reservation; // Main model used for bookings
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
// use App\Models\Booking; // Unused if we are sticking to Reservation model

class BookingController extends Controller
{
    // Step 1: Show the Seat Selection Page
    public function selectSeats($schedule_id)
    {
        // FIX: Filter the reservations relation.
        // We only want to retrieve "Active" or "Pending" reservations.
        // "Approved" cancellations are ignored, so the seat appears FREE.
        $schedule = Schedule::with(['bus', 'route', 'reservations' => function($query) {
            $query->where(function($q) {
                $q->where('cancellation_status', '!=', 'approved') // If approved, ignore it (seat free)
                  ->orWhereNull('cancellation_status');            // If null, it's active (seat taken)
            });
        }])->findOrFail($schedule_id);

        // Pluck only the seat numbers found by the filter above
        $takenSeats = $schedule->reservations->pluck('seat_number')->toArray();

        return view('booking.seats', compact('schedule', 'takenSeats'));
    }

    // public function selectSeats(Schedule $schedule)
    // {
    //     // We don't need to pass tripType anymore, as it's decided on the page itself.
    //     $takenSeats = $schedule->reservations->pluck('seat_number')->toArray();
    //     return view('booking.seats', compact('schedule', 'takenSeats'));
    // }

    // Step 2: Show Details Form
    public function showReservationDetails(Request $request)
    {
        // 1. CLEANUP: If user changed their mind and switched to One Way, clear any old session data
        if ($request->trip_type === 'one_way') {
            $request->session()->forget('outbound_trip');
        }

        // 2. VALIDATE INPUTS
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seats'       => 'required|string',
            'trip_type'   => 'required|in:one_way,round_trip',
            'passengers_adult' => 'required|integer|min:1',
            'passengers_child' => 'required|integer|min:0',
            'return_date'      => 'nullable|date',
        ]);

        $schedule = \App\Models\Schedule::with(['bus', 'route'])->findOrFail($request->schedule_id);
        
        // 3. ROUND TRIP LOGIC: HANDLE "LEG 1" (Outbound)
        // If it's a Round Trip AND we haven't stored the first leg yet...
        if ($request->trip_type === 'round_trip' && !$request->session()->has('outbound_trip')) {
            
            // A. Store the Outbound Details in Session
            $request->session()->put('outbound_trip', [
                'schedule_id' => $schedule->id,
                'seats'       => $request->seats,
                'adults'      => $request->passengers_adult,
                'children'    => $request->passengers_child,
                'formatted_date' => \Carbon\Carbon::parse($schedule->departure_time)->format('M d, Y h:i A'),
                'route'       => $schedule->route->origin . ' -> ' . $schedule->route->destination,
                'price'       => $schedule->route->price // Store base price for reference
            ]);

            // B. Redirect back to Search for the RETURN Leg
            // We swap Origin/Destination and set the date to the Return Date
            return redirect()->route('home', [
                'origin'      => $schedule->route->destination, // SWAP
                'destination' => $schedule->route->origin,      // SWAP
                'date'        => $request->return_date,
                'trip_type'   => 'round_trip',     // Keep context
                'passengers_adult' => $request->passengers_adult, // Keep counts
                'passengers_child' => $request->passengers_child,
                'is_return'   => 1                 // FLAG: Tell the view this is the return trip
            ])->with('info', "Great! You selected the trip to {$schedule->route->destination}. Now select your return bus.");
        }

        // 4. ROUND TRIP LOGIC: HANDLE "LEG 2" (Return)
        // If we have an outbound trip in session, this request is for the RETURN leg.
        $outboundSchedule = null;
        $outboundSeats = [];
        
        if ($request->session()->has('outbound_trip')) {
            $sessionData = $request->session()->get('outbound_trip');
            $outboundSchedule = \App\Models\Schedule::with(['bus', 'route'])->find($sessionData['schedule_id']);
            $outboundSeats = explode(',', $sessionData['seats']);
        }

        // 5. CALCULATE TOTAL PRICE (For 1 or 2 Legs)
        $selectedSeats = explode(',', $request->seats);
        
        // Current Leg Price (Return or One-Way)
        $currentBasePrice = $schedule->route->price;
        $currentTotal = ($request->passengers_adult * $currentBasePrice) + ($request->passengers_child * ($currentBasePrice * 0.8));

        // Add Outbound Price if exists
        $outboundTotal = 0;
        if ($outboundSchedule) {
            $outBasePrice = $outboundSchedule->route->price;
            $outboundTotal = ($request->passengers_adult * $outBasePrice) + ($request->passengers_child * ($outBasePrice * 0.8));
        }

        $totalPrice = $currentTotal + $outboundTotal;

        // 6. VALIDATE SEAT COUNTS
        // (Ensure user didn't change passenger count between legs)
        $totalPassengers = $request->passengers_adult + $request->passengers_child;
        if (count($selectedSeats) !== $totalPassengers) {
             return redirect()->back()->withErrors(['seats' => 'Please select exactly ' . $totalPassengers . ' seats.']);
        }

        return view('booking.reservation', compact(
            'schedule',         // The Current Leg (Return or One-Way)
            'outboundSchedule', // The Previous Leg (if exists)
            'selectedSeats',    // Current Leg Seats
            'outboundSeats',    // Previous Leg Seats
            'totalPrice',
            'request' 
        ));
    }

    // Step 3: Show Confirmation Page
    public function showConfirmation(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'schedule_id'   => 'required|exists:schedules,id',
            'seats'         => 'required|string',
            'contact_phone' => 'required|string',
            'contact_email' => 'required|email',
            'trip_type'     => 'required|in:one_way,round_trip',
            'return_date'   => 'nullable|date',
            'passengers'    => 'required|array',
            // ... add deep validation if needed ...
        ]);

        $schedule = \App\Models\Schedule::with(['bus', 'route'])->findOrFail($request->schedule_id);
        
        $totalPrice = 0;
        $breakdown = [];
        
        // --- CALCULATION 1: CURRENT TRIP (Return or Single) ---
        $basePrice = $schedule->route->price;
        foreach ($request->passengers as $p) {
            $isChild = ($p['type'] === 'child');
            $price = $isChild ? ($basePrice * 0.80) : $basePrice; 
            $totalPrice += $price;

            $breakdown[] = [
                'label' => 'Current Trip', //
                'name' => $p['first_name'] . ' ' . $p['surname'],
                'type' => ucfirst($p['type']),
                'seat' => $p['seat'],
                'price' => $price,
                'discount_id' => $p['discount_id'] ?? null
            ];
        }

        // --- CALCULATION 2: OUTBOUND TRIP (From Session) ---
        if ($request->session()->has('outbound_trip')) {
            $outbound = $request->session()->get('outbound_trip');
            $outSchedule = \App\Models\Schedule::with('route')->find($outbound['schedule_id']);
            $outPrice = $outSchedule->route->price;
            $outSeats = explode(',', $outbound['seats']); // Get seats from session
            
            // We map the same passengers to the outbound seats
            $passengerList = array_values($request->passengers); 
            
            foreach ($passengerList as $index => $p) {
                if (isset($outSeats[$index])) {
                    $isChild = ($p['type'] === 'child');
                    $price = $isChild ? ($outPrice * 0.80) : $outPrice; 
                    $totalPrice += $price;

                    $breakdown[] = [
                        'label' => 'Outbound Trip',
                        'name' => $p['first_name'] . ' ' . $p['surname'],
                        'type' => ucfirst($p['type']),
                        'seat' => $outSeats[$index], // Use Outbound Seat
                        'price' => $price,
                        'discount_id' => $p['discount_id'] ?? null
                    ];
                }
            }
        }

        return view('booking.confirm', compact('schedule', 'validated', 'totalPrice', 'breakdown'));
    }

    // Step 4: Show Payment Page
    public function showPayment(Request $request)
    {
        // 1. Prepare Data for the View
        $data = $request->all();
        $schedule = \App\Models\Schedule::with('route')->findOrFail($request->schedule_id);
        $basePrice = $schedule->route->price;
        
        // Parse seats (ensure it's an array)
        $seats = is_array($request->seats) ? $request->seats : explode(',', $request->seats);
        
        $priceBreakdown = [];
        $totalPrice = 0;

        // ---------------------------------------------------------
        // A. CALCULATE CURRENT TRIP
        // ---------------------------------------------------------
        if (isset($data['passengers']) && is_array($data['passengers'])) {
            foreach ($data['passengers'] as $index => $passenger) {
                $ticketPrice = $basePrice;
                $isDiscounted = false;

                // Validation: Child OR Valid Discount ID
                $isChild = ($passenger['type'] ?? '') === 'child';
                $discountId = $passenger['discount_id'] ?? '';
                $hasValidId = !empty($discountId) && preg_match('/^\d{4}-\d{4}$/', $discountId);

                if ($isChild || $hasValidId) {
                    $ticketPrice = $basePrice * 0.80; // 20% Off
                    $isDiscounted = true;
                }

                $totalPrice += $ticketPrice;

                // Add to Price Breakdown
                $priceBreakdown[] = [
                    'name' => ($passenger['first_name'] ?? 'Passenger') . ' ' . ($passenger['surname'] ?? ''),
                    'seat' => $passenger['seat'] ?? ($seats[$index] ?? 'N/A'),
                    'original_price' => $basePrice,
                    'final_price' => $ticketPrice,
                    'is_discounted' => $isDiscounted
                ];
            }
        }

        // ---------------------------------------------------------
        // B. CALCULATE OUTBOUND TRIP (If Round Trip)
        // ---------------------------------------------------------
        if ($request->input('trip_type') === 'round_trip' && $request->session()->has('outbound_trip')) {
            $outbound = $request->session()->get('outbound_trip');
            $outSchedule = \App\Models\Schedule::with('route')->find($outbound['schedule_id']);
            
            if ($outSchedule) {
                $outBasePrice = $outSchedule->route->price;
                $outSeats = explode(',', $outbound['seats']); 

                if (isset($data['passengers']) && is_array($data['passengers'])) {
                    foreach ($data['passengers'] as $index => $passenger) {
                        $ticketPrice = $outBasePrice;
                        $isDiscounted = false;
                        
                        $isChild = ($passenger['type'] ?? '') === 'child';
                        $discountId = $passenger['discount_id'] ?? '';
                        $hasValidId = !empty($discountId) && preg_match('/^\d{4}-\d{4}$/', $discountId);

                        if ($isChild || $hasValidId) {
                            $ticketPrice = $outBasePrice * 0.80;
                            $isDiscounted = true;
                        }

                        $totalPrice += $ticketPrice;

                        // Add Return Leg to Breakdown (Visual only)
                        $priceBreakdown[] = [
                            'name' => ($passenger['first_name'] ?? '') . ' (Return Trip)',
                            'seat' => $outSeats[$index] ?? 'N/A',
                            'original_price' => $outBasePrice,
                            'final_price' => $ticketPrice,
                            'is_discounted' => $isDiscounted
                        ];
                    }
                }
            }
        }

        return view('booking.payment', compact('schedule', 'totalPrice', 'data', 'priceBreakdown'));
    }

    // Step 5: Process Booking (Save to DB)
    public function processBooking(Request $request)
    {
        $transactionId = 'TRX-' . strtoupper(uniqid()); 
        
        // 1. SAVE CURRENT TRIP
        foreach ($request->passengers as $passenger) {
            \App\Models\Reservation::create([
                'user_id' => auth()->id(),
                'schedule_id' => $request->schedule_id,
                'seat_number' => $passenger['seat'],
                'status' => 'confirmed',
                'transaction_id' => $transactionId,
                'payment_method' => 'SecurePay', 
                'passenger_name' => $passenger['first_name'] . ' ' . $passenger['surname'],
                'passenger_type' => $passenger['type'],
                'trip_type'      => $request->trip_type,
                'discount_id_number' => $passenger['discount_id'] ?? null,
            ]);
        }

        // 2. SAVE OUTBOUND TRIP (If exists in session)
        if ($request->session()->has('outbound_trip')) {
            $outbound = $request->session()->get('outbound_trip');
            $outSeats = explode(',', $outbound['seats']);
            $passengerList = array_values($request->passengers);

            foreach ($passengerList as $index => $passenger) {
                if (isset($outSeats[$index])) {
                    \App\Models\Reservation::create([
                        'user_id' => auth()->id(),
                        'schedule_id' => $outbound['schedule_id'],
                        'seat_number' => $outSeats[$index], // Map to outbound seat
                        'status' => 'confirmed',
                        'transaction_id' => $transactionId, // Same TRX ID
                        'payment_method' => 'SecurePay',
                        'passenger_name' => $passenger['first_name'] . ' ' . $passenger['surname'],
                        'passenger_type' => $passenger['type'],
                        'trip_type'      => 'round_trip',
                        'round_trip_group_id' => 1, // Optional: You can link IDs here
                        'discount_id_number' => $passenger['discount_id'] ?? null,
                    ]);
                }
            }
            // Clear session after booking
            $request->session()->forget('outbound_trip');
        }

        return redirect()->route('booking.success', ['id' => $transactionId]);
    }

    // Step 6: Show Success / Receipt Page
    public function showSuccess($id)
    {
        // Fetch all reservations with this Transaction ID
        // (This will include both Outbound and Return tickets for all passengers)
        $reservations = \App\Models\Reservation::where('transaction_id', $id)
            ->with(['schedule.route', 'schedule.bus'])
            ->get();

        if ($reservations->isEmpty()) {
            return redirect()->route('home')->with('error', 'Transaction not found.');
        }

        return view('booking.success', compact('reservations', 'id'));
    }
    
    // User My Bookings Page
    public function myBookings()
    {
        $userId = auth()->id();
        $now = \Carbon\Carbon::now();

        // 1. Get UPCOMING Trips
        $upcomingBookings = \App\Models\Reservation::where('user_id', $userId)
            ->whereHas('schedule', function($q) use ($now) {
                $q->where('departure_time', '>=', $now);
            })
            ->with(['schedule.route', 'schedule.bus'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Get PAST Trips
        $pastBookings = \App\Models\Reservation::where('user_id', $userId)
            ->whereHas('schedule', function($q) use ($now) {
                $q->where('departure_time', '<', $now);
            })
            ->with(['schedule.route', 'schedule.bus'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.my-bookings', compact('upcomingBookings', 'pastBookings'));
    }

    public function showReceipt($id)
    {
        $reservation = \App\Models\Reservation::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['schedule.route', 'schedule.bus'])
            ->firstOrFail();

        return view('booking.receipt', compact('reservation'));
    }

    // ACTION: User Requests Cancellation
    public function cancelBooking(Request $request, $id)
    {
        // 1. Find the Reservation
        $reservation = \App\Models\Reservation::findOrFail($id);

        // 2. Security: Owner check
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // 3. Mark as PENDING cancellation
        // The record stays, and the seat is STILL TAKEN until admin approves.
        $reservation->update([
            'cancellation_status' => 'pending',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->back()->with('success', 'Cancellation requested. Waiting for admin approval.');
    }

    // ACTION: Admin Approves Cancellation (Releases Seat)
    public function approveCancellation($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $reservation->update([
            'cancellation_status' => 'approved' 
        ]);

        return redirect()->back()->with('success', 'Booking cancelled and seat released.');
    }
}