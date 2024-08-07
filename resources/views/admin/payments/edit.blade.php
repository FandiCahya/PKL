@extends('master')

@section('title', 'Edit Payment')

@section('isi')
<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Edit Payment</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="booking_id">Booking ID</label>
            <input type="text" name="booking_id" class="form-control @error('booking_id') is-invalid @enderror" value="{{ old('booking_id', $payment->booking_id) }}" required>
            @error('booking_id')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="user_id">User ID</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $payment->user_id) }}" required>
            @error('user_id')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $payment->amount) }}" step="0.01" required>
            @error('amount')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="proof">Payment Proof</label>
            <input type="file" name="proof" class="form-control-file @error('proof') is-invalid @enderror">
            @if($payment->proof)
                <img src="{{ asset('storage/' . $payment->proof) }}" alt="Payment Proof" class="img-thumbnail mt-2" width="150">
            @endif
            @error('proof')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Payment</button>
    </form>
</div>
@endsection
