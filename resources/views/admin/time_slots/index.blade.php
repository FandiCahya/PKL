@extends('master')
@section('title', 'Time Slots')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Time Slots</h1>
        <a href="{{ route('time-slots.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Create Time Slot
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <form class="form-inline">
                <label for="search" class="sr-only">Search Time Slots</label>
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by start time or end time" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">List of Time Slots</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="time-slots-table">
                @include('admin.time_slots.table', ['timeSlots' => $timeSlots])
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
                url: "{{ route('time-slots.index') }}",
                type: "GET",
                data: {'search': query},
                success: function(data) {
                    $('#time-slots-table').html(data);
                }
            });
        });
    });
</script>
@endsection
