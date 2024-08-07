<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Blocked Date</th>
            <th>Reason</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($blockedDates as $blockedDate)
            <tr>
                <td>{{ $blockedDate->id }}</td>
                <td>{{ $blockedDate->blocked_date }}</td>
                <td>{{ $blockedDate->reason }}</td>
                <td>
                    <a href="{{ route('blocked_tgl.edit', $blockedDate->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('blocked_tgl.destroy', $blockedDate->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
