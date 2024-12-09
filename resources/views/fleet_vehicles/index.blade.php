@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Fleet Vehicles</h1>
    <a href="{{ route('fleet_vehicles.create') }}" class="btn btn-primary mb-3">Add Vehicle</a>
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" action="{{ route('fleet_vehicles.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search by vehicle number, model, or type" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form method="GET" action="{{ route('fleet_vehicles.index') }}">
                <div class="input-group">
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>In Maintenance</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                    <button class="btn btn-outline-secondary" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </div>
    @if($fleetVehicles->isEmpty())
        <p>No vehicles found.</p>
    @else
    <table class="table table-responsive">
        <thead>
            <tr>
                <th>ID</th>
                <th><a href="{{ route('fleet_vehicles.index', array_merge(request()->query(), ['sort' => 'vehicle_number', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">Vehicle Number</a></th>
                <th><a href="{{ route('fleet_vehicles.index', array_merge(request()->query(), ['sort' => 'vehicle_model', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">Vehicle Model</a></th>
                <th><a href="{{ route('fleet_vehicles.index', array_merge(request()->query(), ['sort' => 'vehicle_type', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">Vehicle Type</a></th>
                <th><a href="{{ route('fleet_vehicles.index', array_merge(request()->query(), ['sort' => 'status', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">Status</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fleetVehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->id }}</td>
                    <td>{{ $vehicle->vehicle_number }}</td>
                    <td>{{ $vehicle->vehicle_model }}</td>
                    <td>{{ $vehicle->vehicle_type }}</td>
                    <td>{{ $vehicle->status }}</td>
                    <td>
                        <a href="{{ route('fleet_vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-info">Show</a>
                        <a href="{{ route('fleet_vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('fleet_vehicles.destroy', $vehicle->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $fleetVehicles->appends(request()->query())->links() }}
    @endif
</div>
@endsection
