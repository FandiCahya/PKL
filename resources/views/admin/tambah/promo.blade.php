@extends('master')
@section('title', 'Tambah Promo')
@section('isi')
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Tambah Promo</h1>
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
                    <label for="datetime">Tanggal dan Waktu:</label>
                    <input type="datetime-local" class="form-control" id="datetime" name="datetime">
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

</div>
@endsection
