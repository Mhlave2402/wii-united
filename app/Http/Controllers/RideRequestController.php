<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SafeZone;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DB;

class RideRequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_latitude' => 'required|numeric|between:-90,90',
            'start_longitude' => 'required|numeric|between:-180,180',
            'end_latitude' => 'required|numeric|between:-90,90',
            'end_longitude' => 'required|numeric|between:-180,180',
        ], [
            'start_latitude.required' => 'Start latitude is required.',
            'start_latitude.numeric' => 'Start latitude must be a number.',
            'start_latitude.between' => 'Start latitude must be between -90 and 90.',
            'start_longitude.required' => 'Start longitude is required.',
            'start_longitude.numeric' => 'Start longitude must be a number.',
            'start_longitude.between' => 'Start longitude must be between -180 and 180.',
            'end_latitude.required' => 'End latitude is required.',
            'end_latitude.numeric' => 'End latitude must be a number.',
            'end_latitude.between' => 'End latitude must be between -90 and 90.',
            'end_longitude.required' => 'End longitude is required.',
            'end_longitude.numeric' => 'End longitude must be a number.',
            'end_longitude.between' => 'End longitude must be between -180 and 180.',

        ]);


        $startPoint = [$validatedData['start_latitude'], $validatedData['start_longitude']];
        $endPoint = [$validatedData['end_latitude'], $validatedData['end_longitude']];

        $startSafeZone = $this->isWithinSafeZone($startPoint);
        $endSafeZone = $this->isWithinSafeZone($endPoint);

        if (!$startSafeZone) {
            return redirect()->back()->withInput()->with('error', 'Start point must be within a safe zone.');
        }
        if (!$endSafeZone) {
            return redirect()->back()->withInput()->with('error', 'End point must be within a safe zone.');
        }

        // ... rest of your ride request logic ...
    }

    private function isWithinSafeZone($point)
    {
        $safeZones = SafeZone::all();
        foreach ($safeZones as $safeZone) {
            // Implement your logic to check if the point is within the safe zone
            // This might involve using a library for geospatial calculations
            // or defining a simple distance check if safe zones are represented as points.
            if ($this->isPointInCircle($point, $safeZone->latitude, $safeZone->longitude, 100)) { //Example 100m radius
                return true;
            }
        }
        return false;
    }

    //Helper function - replace with your actual logic
    private function isPointInCircle($point, $centerLat, $centerLng, $radius) {
        $earthRadius = 6371000; // meters

        $lat1 = deg2rad($point[0]);
        $lon1 = deg2rad($point[1]);
        $lat2 = deg2rad($centerLat);
        $lon2 = deg2rad($centerLng);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance <= $radius;
    }
}
