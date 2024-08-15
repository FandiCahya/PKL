<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 5%;">User</th>
            <th style="width: 5%;">Room/Promotion</th>
            <th style="width: 10%;">Date</th>
            <th style="width: 10%;">Time</th>
            <th style="width: 10%;">Price</th>
            <th style="width: 5%;">Status</th>
            <th style="width: 10%;">QR Code</th>
            {{-- <th>Action</th> --}}
            {{-- <th style="width: 15%;">Validate</th> <!-- Added this column for the validation button --> --}}
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
                    @if ($booking->booking_type == 'room')
                        {{ isset($booking->room) ? $booking->room->nama : '-' }}
                    @else
                        {{ isset($booking->promotion) ? $booking->promotion->name : '-' }}
                    @endif
                </td>
                <td>
                    @if ($booking->booking_type == 'room')
                        {{ $booking->tgl }}
                    @else
                        {{ isset($booking->promotion) ? $booking->promotion->tgl : '-' }}
                    @endif
                </td>
                <td>
                    @if ($booking->booking_type == 'room')
                        {{ isset($booking->timeSlot) ? $booking->timeSlot->start_time . ' - ' . $booking->timeSlot->end_time : '-' }}
                    @else
                        {{ isset($booking->promotion) ? $booking->promotion->waktu : '-' }}
                    @endif
                </td>
                <td>
                    @if ($booking->booking_type == 'room')
                    {{ $booking->harga }}</td>
                    @else
                    {{ isset($booking->promotion) ? $booking->promotion->harga : '-' }}
                    @endif
                    
                <td>{{ $booking->status }}</td>
                <td>
                    @if ($booking->status === 'Booked' && $booking->qrcode)
                        <img src="{{ asset($booking->qrcode) }}" alt="QR Code" style="width: 100px;">
                    @else
                        <span>No QR Code</span>
                    @endif
                </td>
                {{-- <td>
                    <a href="{{ route('edit_booking', $booking->id) }}" class="btn btn-warning btn-sm">Update</a>
                    <form action="{{ route('hapus_booking', $booking->id) }}" method="POST"
                        style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td> --}}
                {{-- <td>
                    @if ($booking->status === 'Pending')
                        <!-- Display validation buttons if status is 'Pending' -->
                        <form action="{{ route('validate_booking', $booking->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('PUT')
                            <button type="submit" name="status" value="Booked" class="btn btn-primary btn-sm">Validasi</button>
                            <button type="submit" name="status" value="Rejected" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    @elseif ($booking->status === 'Booked')
                        <!-- Display validation buttons if status is 'Booked' -->
                        <form action="{{ route('validate_booking', $booking->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('PUT')
                            <button type="submit" name="status" value="Pending" class="btn btn-warning btn-sm">
                                Pending</button>
                            <button type="submit" name="status" value="Rejected" class="btn btn-danger btn-sm">
                                Reject</button>
                        </form>
                    @elseif ($booking->status === 'Rejected')
                        <!-- Display validation buttons if status is 'Rejected' -->
                        <form action="{{ route('validate_booking', $booking->id) }}" method="POST"
                            style="display: inline-block;">
                            @csrf
                            @method('PUT')
                            <button type="submit" name="status" value="Booked" class="btn btn-primary btn-sm">Validasi</button>
                            <button type="submit" name="status" value="Pending" class="btn btn-warning btn-sm">
                                Pending</button>
                        </form>
                    @endif

                </td> --}}
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination Links -->
{{ $bookings->links('pagination::bootstrap-4') }}
