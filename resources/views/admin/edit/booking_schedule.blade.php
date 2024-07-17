@extends('master')
@section('title', 'Edit Booking')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Booking</h1>

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
            <form action="{{ route('update_booking_schedule', $booking_schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="schedule_id">Schedule ID</label>
                    <select class="form-control" id="schedule_id" name="schedule_id">
                        <option value="">Select Schedule ID</option>
                            <option value="{{ $booking_schedule->schedule_id }}" {{ $booking_schedule->schedule_id == $booking_schedule->schedule_id ? 'selected' : '' }}>
                                {{ $booking_schedule->schedule_id }}
                            </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" readonly>
                        <option value="Booked" {{ $booking_schedule->status == 'Booked' ? 'selected' : '' }}>Booked</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Booking</button>
            </form>
        </div>
    </div>

</div>
@endsection
