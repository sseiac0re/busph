<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    // 1. List all reservations
    public function index(Request $request)
    {
        $query = \App\Models\Reservation::with(['user', 'schedule.route', 'schedule.bus'])
            ->latest();

        // Implement Search/Filter by Passenger Name or Email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
            // Also allow searching by Reservation ID
            $query->orWhere('id', $search);
        }

        // Use pagination
        $reservations = $query->paginate(15)->withQueryString(); 

        return view('admin.reservations.index', compact('reservations'));
    }

    // 2. View details of a specific reservation
    public function show($id)
    {
        $reservation = Reservation::with(['user', 'schedule.route', 'schedule.bus'])
            ->findOrFail($id);

        return view('admin.reservations.show', compact('reservation'));
    }
}