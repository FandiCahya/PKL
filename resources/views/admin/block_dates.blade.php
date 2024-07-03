@extends('master')
@section('title', 'Blocked Dates')
@section('isi')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Blocked Dates</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <form action="#" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-lg-6 text-right">
                <a href="{{ route('blocked_dates.create') }}" class="btn btn-success">Add Blocked Date</a>
            </div>
        </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($blockedDates as $blockedDate)
                        <tr>
                            <td>{{ $blockedDate->id }}</td>
                            <td>{{ $blockedDate->blocked_date }}</td>
                            <td>{{ $blockedDate->reason }}</td>
                            <td>
                                <a href="{{ route('blocked_dates.edit', $blockedDate) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('blocked_dates.destroy', $blockedDate) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
