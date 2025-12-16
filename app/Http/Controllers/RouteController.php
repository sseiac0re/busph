<?php

namespace App\Http\Controllers;

use App\Interfaces\RouteRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\BusRoute;
use App\Models\Route; 

class RouteController extends Controller
{
    private RouteRepositoryInterface $routeRepository;

    // CONSTRUCTOR INJECTION (Design Pattern)
    public function __construct(RouteRepositoryInterface $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function index(Request $request)
    {
        $query = \App\Models\Route::query();
        
        // --- 1. General Search Filtering ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('origin', 'like', '%' . $search . '%')
                  ->orWhere('destination', 'like', '%' . $search . '%');
            });
        }
        
        // --- 2. Specific Origin/Destination Filters ---
        if ($request->filled('origin_filter')) {
            $query->where('origin', $request->origin_filter);
        }

        if ($request->filled('destination_filter')) {
            $query->where('destination', $request->destination_filter);
        }
        
        // --- 3. Sorting Logic (Default is Origin A-Z) ---
        $sortBy = $request->input('sort_by', 'origin_asc');
        
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'origin_desc':
                $query->orderBy('origin', 'desc');
                break;
            case 'origin_asc':
            default:
                $query->orderBy('origin', 'asc');
                break;
        }
        
        // --- 4. Execute Query and Get Unique Options ---

        // Get unique options for the filter dropdowns
        $origins = \App\Models\Route::select('origin')->distinct()->pluck('origin');
        $destinations = \App\Models\Route::select('destination')->distinct()->pluck('destination');

        // Execute query with pagination (maintains search/filter across pages)
        $routes = $query->paginate(4)->withQueryString(); 

        return view('admin.routes.index', compact('routes', 'origins', 'destinations'));
    }

    public function create()
    {
        return view('admin.routes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $this->routeRepository->createRoute($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Route added via Repository Pattern!');
    }

    public function destroy($id)
    {
        $this->routeRepository->deleteRoute($id);
        return redirect()->route('admin.routes.index')->with('success', 'Route deleted successfully!');
    }

    // Show the Edit Form
    public function edit(\App\Models\Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    // Update the Route
    public function update(Request $request, \App\Models\Route $route)
    {
        $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $route->update($request->all());

        return redirect()->route('admin.routes.index')->with('success', 'Route updated successfully!');
    }
}