<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Image</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Aksi</th>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    <tbody>
        @foreach ($promo as $promotion)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $promotion->name }}</td>
                <td>
                    <img src="{{ asset('storage/' . $promotion->image) }}" alt="Promotion Image" style="max-width: 150px;">
                </td>
                <td>{{ $promotion->deskripsi }}</td>
                <td>{{ $promotion->tgl }}</td>
                <td>{{ $promotion->waktu }}</td>
                <td>
                    <a href="{{ route('edit_promo', $promotion->id) }}" class="btn btn-warning btn-sm">Update</a>
                    <form action="{{ route('hapus_promo', $promotion->id) }}" method="POST"
                        style="display: inline-block;">
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
{{ $promo->links('pagination::bootstrap-4') }}
