<table class="table table-hover table-striped table-bordered">
    <thead class="thead-dark">
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
                <td>
                    @if($room->availability)
                        <span class="badge badge-success">Available</span>
                    @else
                        <span class="badge badge-danger">Not Available</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('edit_room', $room->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Update">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('hapus_room', $room->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this room?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $rooms->links('pagination::bootstrap-4') }}
</div>
