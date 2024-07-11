<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Image</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->alamat }}</td>
                <td>{{ $user->no_hp }}</td>
                <td>
                    <img src="{{ asset('storage/' . $user->image) }}" alt="Photo Profile" style="max-width: 150px;">
                </td>
                <td>{{ $user->role }}</td>
                <td>
                    <a href="{{ route('edit_user', $user->id) }}" class="btn btn-warning btn-sm">Update</a>
                    <form action="{{ route('hapus_user', $user->id) }}" method="POST" style="display: inline-block;">
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
{{ $users->links('pagination::bootstrap-4') }}
