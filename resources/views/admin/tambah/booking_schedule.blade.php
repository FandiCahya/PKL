@extends('master')
@section('title', 'Tambah Booking')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Booking</h1>

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
            <form action="{{ route('simpan_booking_schedule') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="schedule_id">Schedule ID</label>
                    <select class="form-control" id="schedule_id" name="schedule_id">
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->id }} - {{ $schedule->name }} <!-- Sesuaikan dengan kolom yang ingin ditampilkan -->
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="Booked" {{ old('status') == 'Booked' ? 'selected' : '' }}>Booked</option>
                        {{-- <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Finished" {{ old('status') == 'Finished' ? 'selected' : '' }}>Finished</option> --}}
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Booking</button>
            </form>
        </div>
    </div>

</div>
@endsection
