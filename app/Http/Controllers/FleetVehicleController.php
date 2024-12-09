<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FleetVehicle;
use App\Models\Driver;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class FleetVehicleController extends Controller
{
    // ... (Existing methods: index, create, store, show, edit, update, destroy, assignDriver, removeDriver) ...

    public function assignDriver(Request $request, FleetVehicle $fleetVehicle)
    {
        $validatedData = $request->validate([
            'driver_id' => ['required', 'exists:drivers,id', Rule::unique('driver_fleet_vehicle')->where(function ($query) use ($fleetVehicle) {
                return $query->where('fleet_vehicle_id', $fleetVehicle->id);
            })],
        ], [
            'driver_id.required' => 'Please select a driver.',
            'driver_id.exists' => 'The selected driver does not exist.',
            'driver_id.unique' => 'This driver is already assigned to this vehicle.',
        ]);

        try {
            $fleetVehicle->drivers()->attach($validatedData['driver_id']);
            return redirect()->route('fleet_vehicles.show', $fleetVehicle->id)->with('success', 'Driver assigned successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Driver assignment failed: ' . $e->getMessage());
        }
    }

    public function removeDriver(Request $request, FleetVehicle $fleetVehicle)
    {
        $validatedData = $request->validate([
            'driver_id' => ['required', 'exists:drivers,id'],
        ], [
            'driver_id.required' => 'Please select a driver.',
            'driver_id.exists' => 'The selected driver does not exist.',
        ]);

        try {
            $fleetVehicle->drivers()->detach($validatedData['driver_id']);
            return redirect()->route('fleet_vehicles.show', $fleetVehicle->id)->with('success', 'Driver removed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Driver removal failed: ' . $e->getMessage());
        }
    }
}
