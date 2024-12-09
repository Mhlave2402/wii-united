@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Generate Gift Cards</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('gift_cards.generate') }}">
        @csrf
        <div class="mb-3">
            <label for="value" class="form-label">Value:</label>
            <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" name="value" min="1" step="0.01" value="{{ old('value') }}" required>
            @error('value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity:</label>
            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" min="1" value="{{ old('quantity') }}" required>
            @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="expiry_days" class="form-label">Expiry Days:</label>
            <input type="number" class="form-control @error('expiry_days') is-invalid @enderror" id="expiry_days" name="expiry_days" min="1" value="{{ old('expiry_days') }}" required>
            @error('expiry_days')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Generate Gift Cards</button>
    </form>
</div>
@endsection
