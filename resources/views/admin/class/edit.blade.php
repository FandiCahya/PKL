@extends('master')
@section('title', 'Edit Promo')
@section('isi')
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Edit Class</h1>

    <!-- Display Success and Error Messages -->
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
            <form action="{{ route('update_promo', $promo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $promo->name }}">
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                    <img src="{{ asset('storage/' . $promo->image) }}" alt="Promotion Image" style="max-width: 150px; margin-top: 10px;">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ $promo->deskripsi }}</textarea>
                </div>
                <div class="form-group">
                    <label for="tgl">Tanggal</label>
                    <input type="date" class="form-control" id="tgl" name="tgl" value="{{ $promo->tgl }}">
                </div>
                <div class="form-group">
                    <label for="waktu">Waktu</label>
                    <input type="time" class="form-control" id="waktu" name="waktu" value="{{ $promo->waktu }}">
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="{{ $promo->harga }}">
                </div>
                <div class="form-group">
                    <label for="room_id">Room</label>
                    <input type="text" class="form-control" id="room_id" name="room_id" value="{{ $promo->room_id }}" readonly>
                </div>
                <div class="form-group">
                    <label for="instruktur_id">Instruktur</label>
                    <input type="text" class="form-control" id="instruktur_id" name="instruktur_id" value="{{ $promo->instruktur_id }}" readonly>
                </div>
                <button type="submit" class="btn btn-primary">Update Class</button>
            </form>
        </div>
    </div>

</div>
@endsection
