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
            <form action="{{ route('update_booking', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="user_id">User</label>
                    <input type="text" class="form-control" id="user_id" name="user_id" value="{{ $booking->user->name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="room_id">Room</label>
                    <input type="text" class="form-control" id="room_id" name="room_id" value="{{ $booking->room_id }}" readonly>
                </div>
                <div class="form-group">
                    <label for="promotion_id">Class</label>
                    <input type="text" class="form-control" id="promotion_id" name="promotion_id" value="{{ $booking->promotion_id }}" readonly>
                </div>
                <div class="form-group">
                    <label for="booking_type">booking_type</label>
                    <select class="form-control" id="booking_type" name="booking_type" >
                        <option value="room" {{ $booking->booking_type == 'room' ? 'selected' : '' }}>room</option>
                        <option value="class" {{ $booking->booking_type == 'class' ? 'selected' : '' }}>class</option> 
                    </select>
                </div>
                <div class="form-group">
                    <label for="tgl">Date</label>
                    <input type="date" class="form-control" id="tgl" name="tgl" value="{{ $booking->tgl }}">
                </div>
                <div class="form-group">
                    <label for="time_slot_id">Time</label>
                    <input type="text" class="form-control" id="time_slot_id" name="time_slot_id" value="{{ $booking->time_slot_id }}" readonly>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" >
                        <option value="Booked" {{ $booking->status == 'Booked' ? 'selected' : '' }}>Booked</option>
                        <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option> 
                        <option value="Rejected" {{ $booking->status == 'Rejected' ? 'selected' : '' }}>Rejected</option> 
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Booking</button>
            </form>
        </div>
    </div>

</div>
@endsection
