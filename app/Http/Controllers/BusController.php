<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    // 1. Show the list of buses (Updated with Search)
    public function index(Request $request)
    {
        $query = Bus::query();

        // ✅ SEARCH LOGIC: Filter by Bus Number, Plate, or Type
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bus_number', 'LIKE', "%$search%")
                  ->orWhere('plate_number', 'LIKE', "%$search%")
                  ->orWhere('type', 'LIKE', "%$search%");
            });
        }

        // Get results (ordered by newest)
        $buses = $query->orderBy('created_at', 'desc')->get();

        return view('admin.buses.index', compact('buses'));
    }

    // 2. Show the form to create a new bus
    public function create()
    {
        return view('admin.buses.create');
    }

    // 3. Store the new bus in the database (Updated with Validation)
    public function store(Request $request)
    {
        $request->validate([
            // ✅ Format: BUS-XXX
            'bus_number' => ['required', 'unique:buses', 'regex:/^BUS-\d+$/'],
            
            // ✅ Format: ABC-123 or ABC-1234 (3 letters, dash, 3-4 numbers)
            'plate_number' => ['required', 'unique:buses', 'regex:/^[A-Z]{3}-\d{3,4}$/'],
            
            'type' => 'required',
            
            // ✅ Logic: Must be integer and at least 10 (No 0 or negatives)
            'capacity' => 'required|integer|min:10|max:80',
            
            'status' => 'required',
        ], [
            // Custom Error Messages
            'bus_number.regex' => 'Bus Number must follow the format: BUS-102',
            'plate_number.regex' => 'Plate Number must follow the format: ABC-123',
            'capacity.min' => 'Capacity must be at least 10 seats.',
        ]);

        Bus::create($request->all());

        return redirect()->route('admin.buses.index')->with('success', 'Bus added successfully!');
    }

    public function edit(Bus $bus)
    {
        return view('admin.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            // ✅ Format: BUS-XXX (Ignore current ID for unique check)
            'bus_number' => ['required', 'unique:buses,bus_number,' . $bus->id, 'regex:/^BUS-\d+$/'],
            
            // ✅ Format: ABC-123
            'plate_number' => ['required', 'unique:buses,plate_number,' . $bus->id, 'regex:/^[A-Z]{3}-\d{3,4}$/'],
            
            'type' => 'required',
            
            // ✅ Logic: Must be integer and at least 10
            'capacity' => 'required|integer|min:10|max:80',
            
            'status' => 'required',
        ], [
            // Custom Error Messages
            'bus_number.regex' => 'Bus Number must follow the format: BUS-102',
            'plate_number.regex' => 'Plate Number must follow the format: ABC-123',
            'capacity.min' => 'Capacity must be at least 10 seats.',
        ]);

        $bus->update($request->all());

        return redirect()->route('admin.buses.index')->with('success', 'Bus updated successfully!');
    }
    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus deleted successfully!');
    }
}