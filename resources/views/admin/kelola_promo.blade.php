@extends('master')
@section('title', 'Promo')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Promosi</h1>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('kelola_promo') }}" method="GET" class="form-inline">
                <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-lg-6 text-right">
            <a href="{{ route('tambah_promo') }}" class="btn btn-success">Create Promo</a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        @if (auth()->user()->role == 'admin')
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Promo</h6>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promo as $promotion)
                        <tr>
                            <td>{{ $promotion->id }}</td>
                            <td>{{ $promotion->name }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $promotion->image) }}" alt="Promotion Image" style="max-width: 150px;">
                            </td>
                            <td>{{ $promotion->deskripsi }}</td>
                            <td>{{ $promotion->tgl }}</td>
                            <td>{{ $promotion->waktu }}</td>
                            <td>
                                <a href="{{ route('edit_promo', $promotion->id) }}" class="btn btn-warning btn-sm">Update</a>
                                <form action="{{ route('hapus_promo', $promotion->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $promo->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

</div>
@endsection
