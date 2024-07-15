<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Room</th>
            <th>Schedule</th>
            <th>Date</th>
            <th>Time</th>
            <th>End Time</th>
            <th>Status</th>
            <th>QR Code</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ isset($booking->user) ? $booking->user->name : '-' }}</td>
            <td>{{ isset($booking->room) ? $booking->room->nama : '-' }}</td>
            <td>{{ isset($booking->schedule) ? $booking->schedule->id : '-' }}</td>
            <td>{{ $booking->tgl }}</td>
            <td>{{ $booking->start_time }}</td>
            <td>{{ $booking->end_time }}</td>
            <td>{{ $booking->status }}</td>
            <td>
                <img src="{{ asset('storage/' . $booking->qrcode) }}" alt="QR Code">
            </td>
            <td>
                <a href="{{ route('edit_booking', $booking->id) }}" class="btn btn-warning btn-sm">Update</a>
                <form action="{{ route('hapus_booking', $booking->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination Links -->
{{ $bookings->links('pagination::bootstrap-4') }}
