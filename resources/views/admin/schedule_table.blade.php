<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Kelas</th>
            <th>Instruktur</th>
            <th>Room</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
        @foreach ($schedules as $schedule)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $schedule->promotion->name }}</td>
                <td>{{ $schedule->instruktur->nama }}</td>
                <td>{{ $schedule->room->nama }}</td>
                <td>{{ $schedule->tgl }}</td>
                <td>
                    <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Update</a>
                    <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" style="display: inline-block;">
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
{{ $schedules->links('pagination::bootstrap-4') }}
