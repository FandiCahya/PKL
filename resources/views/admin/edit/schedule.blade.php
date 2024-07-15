@extends('master')
@section('title', 'Edit Schedule')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Schedule</h1>
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
            <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="promotions_id">Kelas</label>
                    <select class="form-control" id="promotions_id" name="promotions_id" required>
                        @foreach($promotions as $promotion)
                            <option value="{{ $promotion->id }}" {{ $promotion->id == $schedule->promotions_id ? 'selected' : '' }}>{{ $promotion->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="instrukturs_id">Instruktur</label>
                    <select class="form-control" id="instrukturs_id" name="instrukturs_id" required>
                        @foreach($instrukturs as $instruktur)
                            <option value="{{ $instruktur->id }}" {{ $instruktur->id == $schedule->instrukturs_id ? 'selected' : '' }}>{{ $instruktur->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="rooms_id">Room</label>
                    <select class="form-control" id="rooms_id" name="rooms_id" required>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ $room->id == $schedule->rooms_id ? 'selected' : '' }}>{{ $room->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tgl">Tanggal</label>
                    <input type="datetime-local" class="form-control" id="tgl" name="tgl" value="{{ \Carbon\Carbon::parse($schedule->tgl)->format('Y-m-d\TH:i') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

</div>
@endsection
