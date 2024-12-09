@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Ride Request</h1>
    <div id="map" style="height: 400px;"></div>
    <form method="POST" action="{{ route('ride_requests.store') }}">
        @csrf
        <div class="mb-3">
            <label for="start_latitude" class="form-label">Start Latitude:</label>
            <input type="number" class="form-control @error('start_latitude') is-invalid @enderror" id="start_latitude" name="start_latitude" step="0.00000001" value="{{ old('start_latitude') }}" required>
            @error('start_latitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="start_longitude" class="form-label">Start Longitude:</label>
            <input type="number" class="form-control @error('start_longitude') is-invalid @enderror" id="start_longitude" name="start_longitude" step="0.00000001" value="{{ old('start_longitude') }}" required>
            @error('start_longitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="end_latitude" class="form-label">End Latitude:</label>
            <input type="number" class="form-control @error('end_latitude') is-invalid @enderror" id="end_latitude" name="end_latitude" step="0.00000001" value="{{ old('end_latitude') }}" required>
            @error('end_latitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="end_longitude" class="form-label">End Longitude:</label>
            <input type="number" class="form-control @error('end_longitude') is-invalid @enderror" id="end_longitude" name="end_longitude" step="0.00000001" value="{{ old('end_longitude') }}" required>
            @error('end_longitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div id="mapError" class="alert alert-danger mt-3" style="display: none;"></div>
        <button type="submit" class="btn btn-primary">Create Ride Request</button>
    </form>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
<script>
    let map;
    let startMarker, endMarker;
    let safeZonePolygons = [];

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 0, lng: 0 },
            zoom: 2,
        });

        startMarker = new google.maps.Marker({
            map: map,
            position: { lat: 0, lng: 0 },
            draggable: true,
        });
        endMarker = new google.maps.Marker({
            map: map,
            position: { lat: 0, lng: 0 },
            draggable: true,
        });

        startMarker.addListener("dragend", updateCoordinates);
        endMarker.addListener("dragend", updateCoordinates);

        // Fetch and display safe zones
        fetch('/safe-zones')
            .then(response => response.json())
            .then(data => {
                data.data.forEach(safeZone => {
                    let polygonCoords = [
                        { lat: parseFloat(safeZone.latitude), lng: parseFloat(safeZone.longitude) },
                        // Add more coordinates here to define the polygon if needed.  This example assumes a single point.
                    ];
                    let safeZonePolygon = new google.maps.Polygon({
                        paths: polygonCoords,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: "#FF0000",
                        fillOpacity: 0.35,
                    });
                    safeZonePolygon.setMap(map);
                    safeZonePolygons.push(safeZonePolygon);
                });
            });

        // Add autocomplete for start and end locations
        let startAutocomplete = new google.maps.places.Autocomplete(document.getElementById('start_location'));
        let endAutocomplete = new google.maps.places.Autocomplete(document.getElementById('end_location'));

        startAutocomplete.addListener('place_changed', function() {
            let place = startAutocomplete.getPlace();
            startMarker.setPosition({lat: place.geometry.location.lat(), lng: place.geometry.location.lng()});
            updateCoordinates();
        });

        endAutocomplete.addListener('place_changed', function() {
            let place = endAutocomplete.getPlace();
            endMarker.setPosition({lat: place.geometry.location.lat(), lng: place.geometry.location.lng()});
            updateCoordinates();
        });
    }

    function updateCoordinates() {
        document.getElementById('start_latitude').value = startMarker.getPosition().lat();
        document.getElementById('start_longitude').value = startMarker.getPosition().lng();
        document.getElementById('end_latitude').value = endMarker.getPosition().lat();
        document.getElementById('end_longitude').value = endMarker.getPosition().lng();
        validateRideRequest();
    }

    function validateRideRequest() {
        let startLat = parseFloat(document.getElementById('start_latitude').value);
        let startLng = parseFloat(document.getElementById('start_longitude').value);
        let endLat = parseFloat(document.getElementById('end_latitude').value);
        let endLng = parseFloat(document.getElementById('end_longitude').value);

        let isValid = true;
        if (isNaN(startLat) || isNaN(startLng) || isNaN(endLat) || isNaN(endLng)) {
            isValid = false;
        }

        if (isValid) {
            let startPoint = new google.maps.LatLng(startLat, startLng);
            let endPoint = new google.maps.LatLng(endLat, endLng);
            if (!isWithinSafeZone(startPoint) || !isWithinSafeZone(endPoint)) {
                isValid = false;
            }
        }

        if (isValid) {
            $('#mapError').hide();
        } else {
            $('#mapError').show().text('Start and end points must be within a safe zone.');
        }
    }

    function isWithinSafeZone(point) {
        for (let i = 0; i < safeZonePolygons.length; i++) {
            if (google.maps.geometry.poly.containsLocation(point, safeZonePolygons[i])) {
                return true;
            }
        }
        return false;
    }

    initMap();
</script>

@endsection
