<table class="table table-hover table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Email</th>
            <th style="width: 20%">Actions</th>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
        @foreach ($instrukturs as $instruktur)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $instruktur->nama }}</td>
                <td>{{ $instruktur->alamat }}</td>
                <td>{{ $instruktur->no_hp }}</td>
                <td>{{ $instruktur->email }}</td>
                <td>
                    <a href="{{ route('instrukturs.edit', $instruktur->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Update">
                        <i class="fas fa-edit"> </i> Update
                    </a>
                    <form action="{{ route('instrukturs.destroy', $instruktur->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this item?');">
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

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $instrukturs->links('pagination::bootstrap-4') }}
</div>
