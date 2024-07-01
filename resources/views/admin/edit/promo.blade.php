@extends('master')
@section('title', 'Edit Promo')
@section('isi')
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Edit Promo</h1>

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

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('update_promo', $promo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $promo->name }}" required>
                </div>
                <div class="form-group">
                    <label for="image">Gambar</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <img src="{{ asset('storage/' . $promo->image) }}" alt="Promotion Image" style="max-width: 150px; margin-top: 10px;">
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="{{ $promo->deskripsi }}" required>
                </div>
                <div class="form-group">
                    <label for="date">Tanggal</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $promo->date }}" required>
                </div>
                <div class="form-group">
                    <label for="time">Waktu</label>
                    <input type="time" class="form-control" id="time" name="time" value="{{ $promo->time }}">
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

</div>
@endsection
