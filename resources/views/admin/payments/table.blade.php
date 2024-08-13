<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Booking ID</th>
            <th>Amount</th>
            <th>Proof</th>
            <th>Uploaded At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->user->name }}</td>
            <td>{{ $payment->booking_id }}</td>
            <td>{{ number_format($payment->amount, 2) }}</td>
            <td>
                <img src="{{ asset('' . $payment->payment_proof) }}" alt="Payment Image" style="max-width: 150px;">
            </td>
            <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
            <td>
                <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this payment?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
