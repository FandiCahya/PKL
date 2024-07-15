@extends('master')
@section('title', 'Schedules')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Schedules</h1>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <form class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by promotion, instruktur, or room" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6 text-right">
            <a href="{{ route('schedules.create') }}" class="btn btn-success">
                Create Schedule
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Schedules</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="schedules-table">
                @include('admin.schedule_table', ['schedules' => $schedules])
            </div>
        </div>
    </div>

</div>

<!-- jQuery and AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var query = $(this).val();
            $.ajax({
                url: "{{ route('schedules.index') }}",
                type: "GET",
                data: {'search': query},
                success: function(data) {
                    $('#schedules-table').html(data);
                }
            });
        });
    });
</script>
@endsection
