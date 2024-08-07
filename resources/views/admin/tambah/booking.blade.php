@extends('master')
@section('title', 'Create Booking')
@section('isi')
    <div class="container-fluid">

        <h1 class="h3 mb-2 text-gray-800">Create Booking</h1>

        <!-- Success and Error Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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
                <form action="{{ route('simpan_booking') }}" method="POST">
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
                        <label for="room_id">Room</label>
                        <select class="form-control" id="room_id" name="room_id">
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="promotion_id">Class</label>
                        <select class="form-control" id="promotion_id" name="promotion_id">
                            @foreach ($promotions as $promotion)
                                <option value="{{ $promotion->id }}">{{ $promotion->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="booking_type">booking_type</label>
                        <select class="form-control" id="booking_type" name="booking_type">
                            <option value="room">room</option>
                            <option value="class">class</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tgl">Date</label>
                        <input type="date" class="form-control" id="tgl" name="tgl" required>
                    </div>

                    <div class="form-group">
                        <label for="time_slot_id">Time</label>
                        <select class="form-control" id="time_slot_id" name="time_slot_id">
                            @foreach ($timeSlots as $time)
                                <option value="{{ $time->id }}">{{ $time->start_time }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Booked">Booked</option>
                            <option value="Pending">Pending</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>

    </div>
@endsection
