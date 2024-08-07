@extends('master')
@section('title', 'Edit Room')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Edit Room</h1>
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
            <form action="{{ route('update_room', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $room->nama }}" required>
                </div>
                <div class="form-group">
                    <label for="kapasitas">Kapasitas</label>
                    <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="{{ $room->kapasitas }}" required>
                </div>
                <div class="form-group">
                    <label for="availability">Availability</label>
                    <select class="form-control" id="availability" name="availability" required>
                        <option value="1" {{ $room->availability ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ !$room->availability ? 'selected' : '' }}>Not Available</option>
                    </select>
                </div>
                {{-- <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="{{ $room->harga }}" required>
                </div> --}}
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

</div>
@endsection
