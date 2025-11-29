<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::orderBy('name')->get();
        return view('admin.settings.vehicles', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:company,bus',
            'fuel_rate_per_km' => 'required_if:type,company|nullable|numeric|min:0',
            'bus_ticket_amount' => 'required_if:type,bus|nullable|numeric|min:0',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:company,bus',
            'fuel_rate_per_km' => 'required_if:type,company|nullable|numeric|min:0',
            'bus_ticket_amount' => 'required_if:type,bus|nullable|numeric|min:0',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        
        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
