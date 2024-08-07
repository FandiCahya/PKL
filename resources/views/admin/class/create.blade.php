@extends('master')
@section('title', 'Tambah Promo')
@section('isi')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Class</h1>

    <!-- Display Success and Error Messages -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
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
            <form action="{{ route('simpan_promo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="image">Gambar</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
                </div>
                <div class="form-group">
                    <label for="tgl">Tanggal</label>
                    <input type="date" class="form-control" id="tgl" name="tgl" required>
                </div>
                <div class="form-group">
                    <label for="waktu">Waktu</label>
                    <input type="time" class="form-control" id="waktu" name="waktu" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" required>
                </div>
                <div class="form-group">
                    <label for="room_id">Room</label>
                    <select class="form-control" id="room_id" name="room_id">
                        @foreach ($roomsq as $room)
                            <option value="{{ $room->id }}">{{ $room->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="instruktur_id">Instruktur</label>
                    <select class="form-control" id="instruktur_id" name="instruktur_id">
                        @foreach ($instruktursq as $instruktur)
                            <option value="{{ $instruktur->id }}">{{ $instruktur->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
