<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Reservation;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Get Basic Counts
        $totalBuses = Bus::count();
        $totalSchedules = Schedule::count();
        $totalBookings = Reservation::count();
        
        // 2. Calculate Revenue (SMART LOGIC)
        // We only want to count money from 'confirmed' (paid) bookings
        $confirmedReservations = Reservation::where('status', 'confirmed')
            ->with(['schedule.route'])
            ->get();

        $totalRevenue = 0;

        foreach ($confirmedReservations as $reservation) {
            // Safety check: Ensure the schedule/route hasn't been deleted
            if ($reservation->schedule && $reservation->schedule->route) {
                $basePrice = $reservation->schedule->route->price;

                // CHECK: Does this booking have a Discount ID?
                if (!empty($reservation->discount_id_number)) {
                    // Yes: Add 80% of price (20% OFF)
                    $totalRevenue += ($basePrice * 0.80);
                } else {
                    // No: Add full price
                    $totalRevenue += $basePrice;
                }
            }
        }

        // 3. Get Recent Reservations (Top 5 latest)
        $recentReservations = Reservation::with(['user', 'schedule.route'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalBuses', 'totalSchedules', 'totalBookings', 'totalRevenue', 'recentReservations'));
    }

    // Show the verification form
    public function verifyForm()
    {
        return view('admin.verify.verify');
    }

    // Process the verification
    public function checkTicket(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|string'
        ]);

        // Find the reservation by ID or Transaction ID
        $ticket = Reservation::with(['schedule.route', 'schedule.bus'])
                    ->where('id', $request->ticket_id)
                    ->orWhere('transaction_id', $request->ticket_id)
                    ->first();

        if (!$ticket) {
            return back()->with('error', 'Ticket ID not found in the system.');
        }

        return back()->with('success', 'Ticket Found!')->with('ticket', $ticket);
    }
}