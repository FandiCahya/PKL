<table class="table table-bordered">
    <thead>
        <tr>
            {{-- <th>ID</th> --}}
            <th>No</th>
            <th>Date</th>
            <th>Reason</th>
            <th>Actions</th>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
        @foreach ($blockedDates as $blockedDate)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $blockedDate->blocked_date }}</td>
                <td>{{ $blockedDate->reason }}</td>
                <td>
                    <a href="{{ route('blocked_dates.edit', $blockedDate) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('blocked_dates.destroy', $blockedDate) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
