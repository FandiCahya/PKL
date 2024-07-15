<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Aksi</th>
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
                    <a href="{{ route('instrukturs.edit', $instruktur->id) }}" class="btn btn-warning btn-sm">Update</a>
                    <form action="{{ route('instrukturs.destroy', $instruktur->id) }}" method="POST" style="display: inline-block;">
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
{{ $instrukturs->links('pagination::bootstrap-4') }}
