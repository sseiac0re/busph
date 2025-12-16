<?php

namespace App\Http\Controllers;

use App\Interfaces\ScheduleRepositoryInterface;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    private ScheduleRepositoryInterface $scheduleRepository;

    public function __construct(ScheduleRepositoryInterface $scheduleRepository)
    {
        // 1. Dependency Injection for Repository Pattern
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * Display a listing of the schedules.
     */
    public function index()
    {
        // 2. Use repository method for fetching data
        $schedules = $this->scheduleRepository->getAllSchedules(); 
        
        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        // 3. Keep static calls for simple model fetches if they don't belong to a repository
        $buses = Bus::where('status', 'active')->orderBy('bus_number')->get();
        $routes = Route::orderBy('origin')->get();

        return view('admin.schedules.create', compact('buses', 'routes'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'departure_time' => ['required', 'date_format:H:i'],
            'status' => ['nullable', 'in:active,cancelled'],
        ]);

        // 2. Combine date and time into a single datetime field
        $departureDatetime = Carbon::parse($request->departure_date . ' ' . $request->departure_time);
        
        // 3. Check for time conflicts (Ensures the bus isn't double-booked)
        // NOTE: This logic should ideally be inside the ScheduleRepository, but we'll leave it here for direct clarity.
        $conflictingSchedule = Schedule::where('bus_id', $request->bus_id)
            ->where('departure_time', $departureDatetime)
            ->first();

        if ($conflictingSchedule) {
            // Use ValidationException for clean error bag passing
            throw ValidationException::withMessages([
                'departure_time' => ['This bus is already scheduled for another trip at this exact time.'],
            ]);
        }

        // 4. Prepare data array
        $scheduleDetails = [
            'bus_id' => $request->bus_id,
            'route_id' => $request->route_id,
            'departure_time' => $departureDatetime,
            'status' => $request->status ?? 'active',
        ];

        // 5. Use repository method for creation
        $this->scheduleRepository->createSchedule($scheduleDetails);

        // 6. Redirect with success message
        return redirect()->route('admin.schedules.index')->with('success', 'Trip schedule has been successfully created!');
    }

    /**
     * Remove the specified schedule from storage.
     * * @param int $id
     */
    public function destroy($id)
    {
        $this->scheduleRepository->deleteSchedule($id);
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted!');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'departure_time' => ['required', 'date_format:H:i'],
            'status' => ['nullable', 'in:active,cancelled'],
        ]);

        $departureDatetime = Carbon::parse($request->departure_date . ' ' . $request->departure_time);

        $conflictingSchedule = Schedule::where('bus_id', $request->bus_id)
            ->where('departure_time', $departureDatetime)
            ->where('id', '!=', $schedule->id)
            ->first();

        if ($conflictingSchedule) {
            throw ValidationException::withMessages([
                'departure_time' => ['This bus is already scheduled for another trip at this exact time.'],
            ]);
        }

        $scheduleDetails = [
            'bus_id' => $request->bus_id,
            'route_id' => $request->route_id,
            'departure_time' => $departureDatetime,
            'status' => $request->status ?? 'active',
        ];

        $this->scheduleRepository->updateSchedule($schedule->id, $scheduleDetails);

        return redirect()->route('admin.schedules.index')->with('success', 'Trip schedule has been successfully updated!');
    }

    public function edit(Schedule $schedule)
    {
        $buses = Bus::where('status', 'active')->orderBy('bus_number')->get();
        $routes = Route::orderBy('origin')->get();

        return view('admin.schedules.edit', compact('schedule', 'buses', 'routes'));
    }

    public function deleteAll()
    {
        // 1. Count how many we are about to delete (for the success message)
        $count = \App\Models\Schedule::doesntHave('reservations')->count();

        if ($count === 0) {
            return back()->with('error', 'No empty schedules found to delete.');
        }

        // 2. Delete only schedules that have NO reservations
        // This protects real customer data while clearing your test data.
        \App\Models\Schedule::doesntHave('reservations')->delete();

        return back()->with('success', "Successfully cleared {$count} empty schedules.");
    }
}