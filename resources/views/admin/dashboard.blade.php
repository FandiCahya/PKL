@extends('master')
@section('title', 'Dashboard')
@section('isi')

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        @include('admin.card')

        <br>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <h1 class="h4 mb-4 text-gray-800">Schedule</h1> 
                <div id="calendar"></div>
            </div>
        </div>

        <br>

    </div>

@endsection

