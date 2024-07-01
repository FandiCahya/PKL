@extends('master')
@section('title', 'Users')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Users</h1>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('kelola_user') }}" method="GET" class="form-inline">
                <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-lg-6 text-right">
            <a href="{{ route('tambah_user') }}" class="btn btn-success">Tambah Room</a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        @if (auth()->user()->role == 'admin')
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Users</h6>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $users)
                        <tr>
                            <td>{{ $users->id }}</td>
                            <td>{{ $users->name }}</td>
                            <td>{{ $users->email }}</td>
                            <td>{{ $users->alamat }}</td>
                            <td>{{ $users->no_hp }}</td>
                            <td>{{ $users->role }}</td>
                            <td>
                                <a href="{{ route('edit_user', $users->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('hapus_user', $users->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
