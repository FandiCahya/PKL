<table class="table table-hover table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 10%;">User</th>
            <th style="width: 10%;">Class</th>
            <th style="width: 10%;">Room</th>
            <th style="width: 15%;">Date</th>
            <th style="width: 15%;">Time</th>
            <th style="width: 10%;">Price</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 20%;">QR Code</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($bookings as $booking)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ isset($booking->user) ? $booking->user->name : '-' }}</td>
                <td>
                    {{ isset($booking->promotion) ? $booking->promotion->name : '-' }}
                </td>
                <td>
                    {{ isset($booking->room) ? $booking->room->nama : '-' }}
                </td>
                <td>
                    @if ($booking->booking_type == 'room')
                        {{ \Carbon\Carbon::parse($booking->tgl)->format('d M Y') }}
                    @else
                        {{ isset($booking->promotion) ? \Carbon\Carbon::parse($booking->promotion->tgl)->format('d M Y') : '-' }}
                    @endif
                </td>
                <td>
                    @if ($booking->booking_type == 'room')
                        {{ isset($booking->timeSlot) ? \Carbon\Carbon::parse($booking->timeSlot->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($booking->timeSlot->end_time)->format('H:i') : '-' }}
                    @else
                        {{ isset($booking->promotion) ? \Carbon\Carbon::parse($booking->promotion->waktu)->format('H:i') : '-' }}
                    @endif
                </td>
                <td>{{ number_format($booking->harga, 2) }}</td>
                <td>
                    {{-- {{ ucfirst($booking->status) }} --}}
                    @php
                        $badgeClass = '';
                        $statusText = '';

                        if ($booking->status === 'Booked') {
                            $badgeClass = 'badge-success';
                            $statusText = 'Booked';
                        } elseif ($booking->status === 'Rejected') {
                            $badgeClass = 'badge-danger';
                            $statusText = 'Rejected';
                        } else {
                            // Assuming 'pending' is the default or fallback status
                            $badgeClass = 'badge-warning';
                            $statusText = 'Pending';
                        }
                    @endphp
                    {{-- {{ $payment->status }} --}}
                    <span class="badge {{ $badgeClass }}">
                        {{ $statusText }}
                    </span>
                </td>
                <td>
                    @if ($booking->status === 'Booked' && $booking->qrcode)
                        <img src="{{ asset($booking->qrcode) }}" alt="QR Code" class="img-thumbnail"
                            style="width: 100px;" data-toggle="tooltip" title="Click to view QR code">
                    @else
                        <span>No QR Code</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $bookings->links('pagination::bootstrap-4') }}
</div>
