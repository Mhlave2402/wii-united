<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SafeZone;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DB;

class SafeZoneController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $safeZones = SafeZone::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->paginate(10);
        return view('safe_zones.index', compact('safeZones'));
    }

    public function create()
    {
        return view('safe_zones.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:safe_zones',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ], [
            'name.required' => 'Name is required.',
            'name.unique' => 'Safe zone name already exists.',
            'latitude.required' => 'Latitude is required.',
            'latitude.numeric' => 'Latitude must be a number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.required' => 'Longitude is required.',
            'longitude.numeric' => 'Longitude must be a number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
        ]);

        try {
            SafeZone::create($validatedData);
            return redirect()->route('safe-zones.index')->with('success', 'Safe zone added successfully.');
        } catch (\Exception $e) {
            Log::error('Safe zone creation failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Safe zone creation failed. Please try again later.');
        }
    }

    public function show(SafeZone $safeZone)
    {
        return view('safe_zones.show', compact('safeZone'));
    }

    public function edit(SafeZone $safeZone)
    {
        return view('safe_zones.edit', compact('safeZone'));
    }

    public function update(Request $request, SafeZone $safeZone)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('safe_zones')->ignore($safeZone->id)],
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ], [
            'name.required' => 'Name is required.',
            'name.unique' => 'Safe zone name already exists.',
            'latitude.required' => 'Latitude is required.',
            'latitude.numeric' => 'Latitude must be a number.',
            'latitude.between' => 'Latitude must be between -90 and 90.',
            'longitude.required' => 'Longitude is required.',
            'longitude.numeric' => 'Longitude must be a number.',
            'longitude.between' => 'Longitude must be between -180 and 180.',
        ]);

        try {
            $safeZone->update($validatedData);
            return redirect()->route('safe-zones.index')->with('success', 'Safe zone updated successfully.');
        } catch (\Exception $e) {
            Log::error('Safe zone update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Safe zone update failed. Please try again later.');
        }
    }

    public function destroy(SafeZone $safeZone)
    {
        try {
            $safeZone->delete();
            return redirect()->route('safe-zones.index')->with('success', 'Safe zone deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Safe zone deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Safe zone deletion failed. Please try again later.');
        }
    }
}
