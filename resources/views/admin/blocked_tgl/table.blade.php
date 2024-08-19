<table class="table table-hover table-striped table-bordered mt-3">
    <thead class="thead-dark">
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
                <td>{{ \Carbon\Carbon::parse($blockedDate->blocked_date)->format('d M Y') }}</td>
                <td>{{ $blockedDate->reason }}</td>
                <td>
                    <a href="{{ route('blocked_tgl.edit', $blockedDate->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('blocked_tgl.destroy', $blockedDate->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this blocked date?');">
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
