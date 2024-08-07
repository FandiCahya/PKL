@extends('master')
@section('title', 'Edit Blocked Date')
@section('isi')
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Edit Blocked Date</h1>
    
    <!-- Success and Error Messages -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('blocked_tgl.update', $blockedDate->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="blocked_date">Tanggal</label>
                    <input type="date" class="form-control" id="blocked_date" name="blocked_date" value="{{ $blockedDate->blocked_date }}" required>
                </div>
                <div class="form-group">
                    <label for="reason">Alasan</label>
                    <input type="text" class="form-control" id="reason" name="reason" value="{{ $blockedDate->reason }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

</div>
@endsection
