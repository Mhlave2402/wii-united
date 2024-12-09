<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetVehicle extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_number', 'vehicle_model', 'vehicle_type', 'status'];

    public function drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_fleet_vehicle');
    }
}
