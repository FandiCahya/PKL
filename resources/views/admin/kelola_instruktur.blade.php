@extends('master')
@section('title', 'Instrukturs')
@section('isi')
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Data Instrukturs</h1>

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
                    <input type="text" name="search" id="search" class="form-control" placeholder="Search by nama" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6 text-right">
            <a href="{{ route('instrukturs.create') }}" class="btn btn-success">
                Create Instruktur
            </a>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Instrukturs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="instrukturs-table">
                @include('admin.instruktur_table', ['instrukturs' => $instrukturs])
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
                url: "{{ route('instrukturs.index') }}",
                type: "GET",
                data: {'search': query},
                success: function(data) {
                    $('#instruktur-table').html(data);
                }
            });
        });
    });
</script>
@endsection
