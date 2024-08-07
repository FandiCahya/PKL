@extends('master')
@section('title', 'Time Slots')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Time Slots</h1>

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
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by start time or end time" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6 text-right">
            <a href="{{ route('time-slots.create') }}" class="btn btn-success">
                Create Time Slot
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Time Slots</h6>
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
