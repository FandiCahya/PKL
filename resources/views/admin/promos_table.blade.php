<table class="table table-hover table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th style="width: 3%;">No</th>
            <th style="width: 15%;">Name</th>
            <th style="width: 10%;">Image</th>
            <th style="width: 20%;">Description</th>
            <th style="width: 10%;">Date</th>
            <th style="width: 10%;">Time</th>
            <th style="width: 10%;">Price</th>
            <th style="width: 10%;">Room</th>
            <th style="width: 10%;">Instructor</th>
            <th style="width: 10%;">Action</th>
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
                    <img src="{{ asset('' . $promotion->image) }}" alt="Promotion Image" class="img-thumbnail" style="max-width: 100px;">
                </td>
                <td>{{ Str::limit($promotion->deskripsi, 100, '...') }}</td>
                <td>{{ $promotion->tgl }}</td>
                <td>{{ $promotion->waktu }}</td>
                <td>{{ $promotion->harga }}</td>
                <td>{{ $promotion->room->nama ?? '-' }}</td>
                <td>{{ $promotion->instruktur->nama ?? '-' }}</td>
                
                <td>
                    <a href="{{ route('edit_promo', $promotion->id) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Update" style="margin: 5px;">
                        <i class="fas fa-edit"></i> Update
                    </a>
                    <form action="{{ route('hapus_promo', $promotion->id) }}" method="POST" style="display: inline-block; margin: 5px;" onsubmit="return confirm('Are you sure you want to delete this class?');">
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
    {{ $promo->links('pagination::bootstrap-4') }}
</div>
