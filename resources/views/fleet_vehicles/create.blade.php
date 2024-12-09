@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Fleet Vehicle</h1>
    <form method="POST" action="{{ route('fleet_vehicles.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="vehicle_number" class="form-label">Vehicle Number <span data-bs-toggle="tooltip" data-bs-placement="top" title="Enter the vehicle's license plate number or unique identifier.">?</span></label>
                    <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}" required>
                    @error('vehicle_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="vehicle_model" class="form-label">Vehicle Model <span data-bs-toggle="tooltip" data-bs-placement="top" title="Enter the make and model of the vehicle.">?</span></label>
                    <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror" id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model') }}" required>
                    @error('vehicle_model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="vehicle_type" class="form-label">Vehicle Type <span data-bs-toggle="tooltip" data-bs-placement="top" title="Select the type of vehicle.">?</span></label>
                    <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" required>
                        @foreach($vehicleTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('vehicle_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span data-bs-toggle="tooltip" data-bs-placement="top" title="Select the current status of the vehicle.">?</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="available">Available</option>
                        <option value="maintenance">In Maintenance</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="vehicle_image" class="form-label">Vehicle Image <span data-bs-toggle="tooltip" data-bs-placement="top" title="Upload an image of the vehicle.">?</span></label>
            <input type="file" class="form-control @error('vehicle_image') is-invalid @enderror" id="vehicle_image" name="vehicle_image">
            @error('vehicle_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <img id="imagePreview" src="#" alt="Vehicle Image Preview" style="max-width: 200px; margin-top: 10px; display: none;">
        </div>
        <div class="mb-3">
            <label for="lease_start_date" class="form-label">Lease Start Date <span data-bs-toggle="tooltip" data-bs-placement="top" title="Enter the start date of the vehicle lease (optional).">?</span></label>
            <input type="date" class="form-control" id="lease_start_date" name="lease_start_date">
        </div>
        <button type="submit" class="btn btn-primary">Add Vehicle</button>
    </form>
    <div id="successMessage" class="alert alert-success mt-3" style="display: none;">Vehicle added successfully!</div>
</div>

<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('#vehicle_image').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreview').show();
            }
            reader.readAsDataURL(this.files[0]);
        });
        $('form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#successMessage').show();
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 2000);
                },
                error: function(xhr) {
                    // Handle errors (display error messages)
                    console.error(xhr.responseJSON.errors);
                    // Add logic to display errors next to fields
                }
            });
        });
    });
</script>

@endsection
