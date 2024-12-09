<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverFleetVehicleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_fleet_vehicle', function (Blueprint $table) {
            $table->id();  // Primary key for the pivot table
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');  // Foreign key reference to drivers table
            $table->foreignId('fleet_vehicle_id')->constrained('fleet_vehicles')->onDelete('cascade');  // Foreign key reference to fleet_vehicles table
            $table->timestamps();  // Automatically adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_fleet_vehicle');
    }
}
