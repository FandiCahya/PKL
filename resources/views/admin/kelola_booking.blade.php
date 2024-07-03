@extends('master')
@section('title', 'Bookings')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Bookings</h1>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form action="{{ route('kelola_booking') }}" method="GET" class="form-inline">
                <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-lg-6 text-right">
            <a href="{{ route('tambah_booking') }}" class="btn btn-success">Create Booking</a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        @if (auth()->user()->role == 'admin')
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Bookings</h6>
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Room</th>
                            <th>Promo</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>QR Code</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ isset($booking->user) ? $booking->user->name : '-' }}</td>
                            <td>{{ isset($booking->room) ? $booking->room->nama : '-' }}</td>
                            <td>{{ isset($booking->promotion) ? $booking->promotion->name : '-' }}</td>
                            <td>{{ $booking->tgl }}</td>
                            <td>{{ $booking->start_time }}</td>
                            <td>{{ $booking->end_time }}</td>
                            <td>{{ $booking->status }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $booking->qrcode) }}" alt="QR Code">
                            </td>
                            <td>
                                <a href="{{ route('edit_booking',$booking->id) }}" class="btn btn-warning btn-sm">Update</a>
                                <form action="{{ route('hapus_booking', $booking->id) }}" method="POST" style="display: inline-block;">
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
                {{ $bookings->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

</div>
@endsection
