<table class="table table-hover table-striped table-bordered mt-3">
    <thead class="thead-dark">
        <tr>
            <th>No</th>
            <th style="width: 15%;">Blocked Date</th>
            <th>Reason</th>
            <th style="width: 20%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($blockedDates as $blockedDate)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($blockedDate->blocked_date)->format('d M Y') }}</td>
                <td>{{ $blockedDate->reason }}</td>
                <td>
                    <a href="{{ route('blocked_tgl.edit', $blockedDate->id) }}" class="btn btn-warning btn-sm"
                        data-toggle="tooltip" title="Edit">
                        <i class="fas fa-edit"></i> Update
                    </a>
                    <form action="{{ route('blocked_tgl.destroy', $blockedDate->id) }}" method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Are you sure you want to delete this blocked date?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
