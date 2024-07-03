@extends('master')

@section('title', 'Tambah Block Date')

@section('isi')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Block Date</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('blocked_dates.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="date">Tanggal</label>
                    <input type="date" class="form-control" id="blocked_date" name="blocked_date" required>
                </div>
                <div class="form-group">
                    <label for="reason">Alasan</label>
                    <input type="text" class="form-control" id="reason" name="reason">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
