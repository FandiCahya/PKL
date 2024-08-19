<table class="table table-hover table-striped table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-dark">
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">User</th>
            <th style="width: 10%;">Booking ID</th>
            <th style="width: 10%;">Amount</th>
            <th style="width: 15%;">Proof</th>
            <th style="width: 15%;">Uploaded At</th>
            <th style="width: 20%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $payment->user->name }}</td>
                {{-- <td>{{ $payment->booking_id }}</td> --}}
                <td>
                    <a href="{{ route('kelola_booking', ['id' => $payment->booking_id]) }}">
                        {{ $payment->booking_id }}
                    </a>
                </td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>
                    <img src="{{ asset($payment->payment_proof) }}" alt="Payment Image" class="img-thumbnail"
                        style="max-width: 150px; cursor: pointer;" data-toggle="modal" data-target="#paymentProofModal"
                        onclick="showImage('{{ asset($payment->payment_proof) }}')">
                </td>
                <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                <td>
                    {{-- <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                        data-target="#validationModal-{{ $payment->id }}">
                        <i class="fas fa-check-circle"></i>
                    </button>

                    {{-- <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this payment?')">Delete</button>
                    </form> --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $payments->links('pagination::bootstrap-4') }}
</div>

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

<!-- Validation Modal -->
{{-- <div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-labelledby="validationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validationModalLabel">Validate Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Booking details will be displayed here -->
                <p><strong>User:</strong> <span id="bookingUser"></span></p>
                <p><strong>Room:</strong> <span id="bookingRoom"></span></p>
                <p><strong>Date:</strong> <span id="bookingDate"></span></p>
                <p><strong>Time Slot:</strong> <span id="bookingTimeSlot"></span></p>
                <p><strong>Promotion:</strong> <span id="bookingPromotion"></span></p>
                <p><strong>Price:</strong> <span id="bookingPrice"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="confirmPayment()">Confirm</button>
                <button type="button" class="btn btn-danger" onclick="rejectPayment()">Reject</button>
            </div>
        </div>
    </div>
</div> --}}

@foreach ($payments as $payment)
    <!-- Validation Modal -->
    <div class="modal fade" id="validationModal-{{ $payment->id }}" tabindex="-1" role="dialog"
        aria-labelledby="validationModalLabel-{{ $payment->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validationModalLabel-{{ $payment->id }}">Validate Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Booking Details</h5>
                    <p><strong>User:</strong> {{ $payment->user->name }}</p>
                    <p><strong>Class:</strong>
                        {{ $payment->booking->promotion ? $payment->booking->promotion->name : 'No Promotion' }}</p>
                    <p><strong>Room:</strong> {{ $payment->booking->room->nama }}</p>
                    <p><strong>Date:</strong> {{ $payment->booking->tgl }}</p>
                    <p><strong>Time Slot:</strong> {{ $payment->booking->promotion_time }}</p>
                    <p><strong>Price:</strong> {{ $payment->booking->harga }}</p>
                    <p><strong>Status:</strong> {{ $payment->booking->status }}</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('payments.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </form>
                    <form action="{{ route('payments.reject') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
