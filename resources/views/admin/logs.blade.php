@extends('master')
@section('title', 'Logs')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Logs</h1>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form class="form-inline" method="GET" action="{{ route('logs.index') }}">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by action or user" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Logs Data</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if ($logs->count())
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Table Name</th>
                                <th>Table ID</th>
                                {{-- <th>Data</th> --}}
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        @php
                            $no = ($logs->currentPage() - 1) * $logs->perPage() + 1;
                        @endphp
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->table_name }}</td>
                                    <td>{{ $log->table_id }}</td>
                                    {{-- <td><pre>{{ json_encode($log->data, JSON_PRETTY_PRINT) }}</pre></td> --}}
                                    <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                        {{ $logs->links('pagination::bootstrap-4') }}
                    </div>
                @else
                    <p class="text-center">No logs found matching your search criteria.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
