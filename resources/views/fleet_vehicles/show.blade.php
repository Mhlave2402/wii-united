@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Fleet Vehicle Details</h2>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th>ID</th>
                    <td>{{ $fleetVehicle->id }}</td>
                </tr>
                <tr>
                    <th>Vehicle Number</th>
                    <td>{{ $fleetVehicle->vehicle_number }}</td>
                </tr>
                <tr>
                    <th>Vehicle Model</th>
                    <td>{{ $fleetVehicle->vehicle_model }}</td>
                </tr>
                <tr>
                    <th>Vehicle Type</th>
                    <td>{{ $fleetVehicle->vehicle_type }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $fleetVehicle->status }}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $fleetVehicle->created_at }}</td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td>{{ $fleetVehicle->updated_at }}</td>
                </tr>
            </table>

            <div class="mt-4">
                <h3>Assigned Drivers:</h3>
                @if ($fleetVehicle->drivers->count() > 0)
                    <ul>
                        @foreach ($fleetVehicle->drivers as $driver)
                            <li>{{ $driver->name }}
                                <form action="{{ route('fleet_vehicles.removeDriver', $fleetVehicle->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Remove</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No drivers assigned.</p>
                @endif
            </div>

            <div class="mt-4">
                <h3>Assign Driver:</h3>
                <form action="{{ route('fleet_vehicles.assignDriver', $fleetVehicle->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select class="form-select" name="driver_id" required>
                            <option value="">Select Driver</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Assign Driver</button>
                </form>
            </div>

            <div class="mt-4">
                <a href="{{ route('fleet_vehicles.index') }}" class="btn btn-secondary mr-2">Back</a>
                <a href="{{ route('fleet_vehicles.edit', $fleetVehicle->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('fleet_vehicles.destroy', $fleetVehicle->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
