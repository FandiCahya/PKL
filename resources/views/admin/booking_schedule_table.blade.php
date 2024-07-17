<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Schedule id</th>
            <th>Kelas</th>
            <th>Room</th>
            <th>Instruktur</th>
            <th>Harga</th>
            <th>Status</th>
            <th>QR Code</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no=1;
        @endphp
        @foreach($booking_schedule as $booking)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $booking->schedule_id }}</td>
            <td>{{ $booking->schedule->promotion->name }}</td>
            <td>{{ $booking->schedule->room->nama }}</td>
            <td>{{ $booking->schedule->instruktur->nama }}</td>
            <td>{{ $booking->schedule->promotion->harga }}</td>
            <td>{{ $booking->status }}</td>
            <td>
                <img src="{{ asset('storage/' . $booking->qrcode) }}" alt="QR Code">
            </td>
            <td>
                {{-- <a href="{{ route('edit_booking_schedule', $booking->id) }}" class="btn btn-warning btn-sm">Update</a> --}}
                <form action="{{ route('hapus_booking_schedule', $booking->id) }}" method="POST" style="display: inline-block;">
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
{{ $booking_schedule->links('pagination::bootstrap-4') }}
