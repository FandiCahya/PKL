@extends('master')
@section('title', 'Edit Time Slot')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Time Slot</h1>
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
            <form action="{{ route('time-slots.update', $timeSlot->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="room_id">Room</label>
                    <input type="text" class="form-control" id="room_id" name="room_id" value="{{ $timeSlot->room_id }}" readonly>
                </div>
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $timeSlot->start_time }}" required>
                </div>
                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $timeSlot->end_time }}" required>
                </div>
                <div class="form-group">
                    <label for="availability">Availability</label>
                    <select class="form-control" id="availability" name="availability" required>
                        <option value="1" {{ $timeSlot->availability ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ !$timeSlot->availability ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

</div>
@endsection
