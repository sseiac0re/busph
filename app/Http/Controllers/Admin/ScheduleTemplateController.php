<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ScheduleTemplate;
use App\Models\Bus;
use App\Models\Route;

class ScheduleTemplateController extends Controller
{
    public function index()
    {
        $templates = ScheduleTemplate::with(['bus', 'route'])->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $buses = Bus::all();
        $routes = Route::all();
        return view('admin.templates.create', compact('buses', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'frequency_minutes' => 'required|integer|min:30',
            'active_days' => 'required|array', // Expecting an array like ['Mon', 'Tue']
        ]);

        ScheduleTemplate::create($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Schedule Template created successfully!');
    }

    // This function triggers the Generator manually from the UI
    public function generate()
    {
        \Artisan::call('schedule:generate', ['days' => 7]);
        return back()->with('success', 'Successfully generated schedules for the next 7 days!');
    }

    public function destroy(ScheduleTemplate $template)
    {
        $template->delete();
        return redirect()->route('admin.templates.index')
            ->with('success', 'Schedule Rule deleted successfully.');
    }
}