<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FleetVehicle;
use App\Models\Driver;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DriverAssignmentController extends Controller
{
    public function assignDriver(Request $request, FleetVehicle $fleetVehicle)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => ['required', 'exists:drivers,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fleetVehicle->drivers()->attach($request->input('driver_id'));
        return redirect()->route('fleet_vehicles.show', $fleetVehicle->id)->with('success', 'Driver assigned successfully.');
    }

    public function removeDriver(Request $request, FleetVehicle $fleetVehicle)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => ['required', 'exists:drivers,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $fleetVehicle->drivers()->detach($request->input('driver_id'));
        return redirect()->route('fleet_vehicles.show', $fleetVehicle->id)->with('success', 'Driver removed successfully.');
    }
}
