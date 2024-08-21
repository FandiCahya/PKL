<table class="table table-hover table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            {{-- <th>ID</th> --}}
            <th style="width: 5%;">No</th>
            <th style="width: 20%;">Date</th>
            <th style="width: 15%;">Time</th>
            <th style="width: 30%;">Reason</th>
            <th style="width: 20%;">Actions</th>
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
                <td>{{ $blockedDate->timeSlot->start_time . '-' . $blockedDate->timeSlot->end_time }}</td>
                <td>{{ $blockedDate->reason }}</td>
                <td>
                    <a href="{{ route('blocked_dates.edit', $blockedDate) }}" class="btn btn-warning"><i
                            class="fas fa-edit"></i> Update</a>
                    <form action="{{ route('blocked_dates.destroy', $blockedDate) }}" method="POST"
                        style="display:inline;"
                        onsubmit="return confirm('Are you sure you want to delete this blocked date?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"data-toggle="tooltip" title="Delete"><i
                                class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $blockedDates->links('pagination::bootstrap-4') }}
</div>
