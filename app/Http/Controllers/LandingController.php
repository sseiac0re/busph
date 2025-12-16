<?php

namespace App\Http\Controllers;

use App\Models\Route as BusRoute; 
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        return $this->commonSearch($request, 'welcome');
    }

    public function dashboard(Request $request)
    {
        // Admin Redirect
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return $this->commonSearch($request, 'dashboard');
    }

    // Shared Logic to avoid duplicate code
    private function commonSearch(Request $request, $viewName)
    {
        $origins = BusRoute::select('origin')->distinct()->orderBy('origin')->pluck('origin');
        $destinations = BusRoute::select('destination')->distinct()->orderBy('destination')->pluck('destination');
        $busTypes = \App\Models\Bus::select('type')->distinct()->orderBy('type')->pluck('type');

        // 1. Define Minimum Date (Yesterday)
        $minDate = Carbon::now()->subDay()->startOfDay();

        // 2. Get Requested Date
        $requestDate = $request->input('date', date('Y-m-d'));
        $searchDate = Carbon::parse($requestDate)->startOfDay();

        // Security: If user tries to go before Min Date, force them back
        if ($searchDate->lt($minDate)) {
            $searchDate = $minDate->copy();
        }

        // 3. GENERATE DYNAMIC CAROUSEL
        // Try to start 3 days before selected date (to center it), but never go below minDate
        $startCarousel = $searchDate->copy()->subDays(3);
        
        // If centering pushes us into the past (before minDate), reset start to minDate
        if ($startCarousel->lt($minDate)) {
            $startCarousel = $minDate->copy();
        }

        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startCarousel->copy()->addDays($i);
        }

        // 4. Query Schedules
        $query = Schedule::with(['bus', 'route', 'reservations'])
            ->withCount('reservations')
            ->whereDate('departure_time', $searchDate->format('Y-m-d'));

        if ($request->filled('origin')) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('origin', $request->origin);
            });
        }

        if ($request->filled('destination')) {
            $query->whereHas('route', function($q) use ($request) {
                $q->where('destination', $request->destination);
            });
        }

        if ($request->filled('bus_type')) {
            $query->whereHas('bus', function($q) use ($request) {
                $q->where('type', $request->bus_type);
            });
        }

        $schedules = $query->orderBy('departure_time')->get();

        if ($request->input('hide_full') == '1') {
            $schedules = $schedules->filter(function ($schedule) {
                return ($schedule->bus->capacity - $schedule->reservations_count) > 0;
            });
        }

        // ✅ NEW: Check if this is the "Return Leg" of a round trip
        $isReturn = $request->has('is_return');

        // Pass properly formatted date string back to view
        $formattedSearchDate = $searchDate->format('Y-m-d');

        return view($viewName, compact(
            'origins', 
            'destinations', 
            'schedules', 
            'dates', 
            'busTypes',
            'isReturn' // <--- Added this to the view data
        ))->with('searchDate', $formattedSearchDate);
    }
}