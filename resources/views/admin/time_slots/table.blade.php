<!-- Tabel Time Slots -->
<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-dark">
        <tr>
            <th>No</th>
            <th>Room</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($timeSlots as $timeSlot)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $timeSlot->room->nama }}</td>
                <td>{{ $timeSlot->start_time }}</td>
                <td>{{ $timeSlot->end_time }}</td>
                <td>
                    <span class="badge {{ $timeSlot->availability ? 'badge-success' : 'badge-danger' }}">
                        {{ $timeSlot->availability ? 'Available' : 'Not Available' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('time-slots.edit', $timeSlot->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('time-slots.destroy', $timeSlot->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination Links -->
{{ $timeSlots->links('pagination::bootstrap-4') }}
