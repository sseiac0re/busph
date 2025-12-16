<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Carbon\Carbon;

class CancellationController extends Controller
{
    /**
     * Show all pending cancellation requests.
     */
    public function index()
    {
        // 1. Get Pending Requests (Actionable items)
        $pendingCancellations = \App\Models\Reservation::where('cancellation_status', 'pending')
            ->with(['schedule.route', 'schedule.bus']) // Optimize query
            ->latest()
            ->get();

        // 2. Get History (Approved & Rejected items)
        $historyCancellations = \App\Models\Reservation::whereIn('cancellation_status', ['approved', 'rejected'])
            ->with(['schedule.route'])
            ->latest() // Show newest first
            ->get();

        return view('admin.cancellations.index', compact('pendingCancellations', 'historyCancellations'));
    }

    /**
     * Approve a pending cancellation request.
     */
    public function approve(Reservation $reservation)
    {
        if ($reservation->cancellation_status !== 'pending') {
            return back()->with('error', 'Only pending requests can be approved.');
        }

        // 1. Update cancellation status
        $reservation->cancellation_status = 'approved';
        
        // 2. Update booking status (Crucial: marks the trip as cancelled/refunded)
        $reservation->status = 'cancelled'; 
        
        $reservation->save();

        return redirect()->route('admin.cancellations.index')->with('success', "Booking #{$reservation->id} has been APPROVED and cancelled.");
    }

    /**
     * Reject a pending cancellation request.
     */
    public function reject(Reservation $reservation)
    {
        if ($reservation->cancellation_status !== 'pending') {
            return back()->with('error', 'Only pending requests can be rejected.');
        }

        // Revert status to 'none' and keep the original booking status (e.g., 'booked')
        $reservation->cancellation_status = 'rejected';
        $reservation->save();

        return redirect()->route('admin.cancellations.index')->with('warning', "Booking #{$reservation->id} cancellation request has been REJECTED.");
    }
}