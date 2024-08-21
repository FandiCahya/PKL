<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
    <thead class="thead-dark">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Image</th>
            <th>Role</th>
            <th style="width: 20%">Actions</th>
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
                    <img src="{{ asset($user->image) }}" alt="Photo Profile" class="img-thumbnail" style="max-width: 100px;">
                </td>
                <td>
                    <span class="badge {{ $user->role == 'admin' ? 'badge-success' : 'badge-secondary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('edit_user', $user->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Update
                    </a>
                    <form action="{{ route('hapus_user', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this users?');">
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
<div class="d-flex justify-content-center mt-3">
    {{ $users->links('pagination::bootstrap-4') }}
</div>
