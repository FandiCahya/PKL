<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Booking ID</th>
            <th>Amount</th>
            <th>Proof</th>
            <th>Uploaded At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $no++}}</td>
                <td>{{ $payment->user->name }}</td>
                {{-- <td>{{ $payment->booking_id }}</td> --}}
                <td>
                    <a href="{{ route('kelola_booking', ['id' => $payment->booking_id]) }}">
                        {{ $payment->booking_id }}
                    </a>
                </td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>
                    <img src="{{ asset($payment->payment_proof) }}" alt="Payment Image"
                        style="max-width: 150px; cursor: pointer;" data-toggle="modal" data-target="#paymentProofModal"
                        onclick="showImage('{{ asset($payment->payment_proof) }}')">
                </td>
                <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                <td>
                    <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this payment?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


<!-- Modal -->
<div class="modal fade" id="paymentProofModal" tabindex="-1" role="dialog" aria-labelledby="paymentProofModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentProofModalLabel">Payment Proof</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Payment Proof" class="img-fluid">
            </div>
        </div>
    </div>
</div>
