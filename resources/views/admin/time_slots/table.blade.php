<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($timeSlots as $timeSlot)
        <tr>
            <td>{{ $timeSlot->id }}</td>
            <td>{{ $timeSlot->start_time }}</td>
            <td>{{ $timeSlot->end_time }}</td>
            <td>
                <a href="{{ route('time-slots.edit', $timeSlot->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('time-slots.destroy', $timeSlot->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $timeSlots->links() }}
