<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FleetVehicleController;
use App\Http\Controllers\DriverAssignmentController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\SafeZoneController;

Route::resource('fleet-vehicles', FleetVehicleController::class);

Route::post('/fleet-vehicles/{fleetVehicle}/assign-driver', [DriverAssignmentController::class, 'assignDriver'])->name('fleet_vehicles.assignDriver');
Route::delete('/fleet-vehicles/{fleetVehicle}/remove-driver', [DriverAssignmentController::class, 'removeDriver'])->name('fleet_vehicles.removeDriver');

Route::post('/gift-cards/generate', [GiftCardController::class, 'generate'])->name('gift_cards.generate');
Route::post('/gift-cards/redeem', [GiftCardController::class, 'redeem'])->name('gift_cards.redeem');

Route::resource('safe-zones', SafeZoneController::class); // Add SafeZone routes
