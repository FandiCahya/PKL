@extends('master')
@section('title', 'Tambah Time Slot')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Tambah Time Slot</h1>

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
            <form action="{{ route('time-slots.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

</div>
@endsection
