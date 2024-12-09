<?php

namespace Modules\VehicleManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FleetVehicle;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class FleetVehicleController extends Controller
{
    public function create()
    {
        return view('fleet_vehicles.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_number' => ['required', 'string', 'max:255', Rule::unique('fleet_vehicles')],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'vehicle_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        FleetVehicle::create($request->all());
        return redirect()->route('fleet_vehicles.index')->with('success', 'Fleet vehicle added successfully.');
    }

    // ... other methods (index, show, edit, update, destroy) ...
}
