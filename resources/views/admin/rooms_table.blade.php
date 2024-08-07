<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kapasitas</th>
            <th>Availability</th>
            <th>Aksi</th>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
        @foreach ($rooms as $room)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $room->nama }}</td>
                <td>{{ $room->kapasitas }}</td>
                <td>{{ $room->availability ? 'Available' : 'Not Available' }}</td>
                <td>
                    <a href="{{ route('edit_room', $room->id) }}" class="btn btn-warning btn-sm">Update</a>
                    <form action="{{ route('hapus_room', $room->id) }}" method="POST" style="display: inline-block;">
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
{{ $rooms->links('pagination::bootstrap-4') }}
