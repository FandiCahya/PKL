@extends('master')

@section('title', 'Upload Payment')

@section('isi')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Upload Payment </h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="booking_id">Booking</label>
                    <select class="form-control" id="booking_id" name="booking_id" required>
                        @foreach ($bookings as $booking)
                            <option value="{{ $booking->id }}">{{ $booking->id }} - {{ $booking->user->name }} - {{ $booking->room->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_proof">Payment</label>
                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload Payment</button>
            </form>
        </div>
    </div>
</div>
@endsection
