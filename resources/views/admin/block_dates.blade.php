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
                <form id="searchForm" class="form-inline">
                    <div class="input-group"> <input type="text" name="search" id="search" class="form-control"
                            placeholder="Search" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-lg-6 text-right">
                <a href="{{ route('blocked_dates.create') }}" class="btn btn-success">Add Blocked Date</a>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body" id="blockedDatesTable">
                @include('admin.blocked_dates_table', ['blockedDates' => $blockedDates])
            </div>
        </div>
    </div>

    <!-- jQuery and AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchForm').on('submit', function(event) {
                event.preventDefault();
                searchBlockedDates($('#search').val());
            });

            $('#search').on('keyup', function() {
                searchBlockedDates($(this).val());
            });

            function searchBlockedDates(query) {
                $.ajax({
                    url: "{{ route('blocked_dates.index') }}",
                    type: "GET",
                    data: {
                        'search': query
                    },
                    success: function(data) {
                        $('#blockedDatesTable').html(data);
                    }
                });
            }
        });
    </script>
@endsection
