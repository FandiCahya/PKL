<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 3%;">No</th>
            <th style="width: 15%;">Name</th>
            <th style="width: 10%;">Image</th>
            <th style="width: 20%;">Description</th>
            <th style="width: 10%;">Date</th>
            <th style="width: 10%;">Time</th>
            <th style="width: 10%;">Price</th>
            <th style="width: 10%;">Room</th>
            <th style="width: 10%;">Instruktur</th>
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
                    <img src="{{ asset('' . $promotion->image) }}" alt="Promotion Image" style="max-width: 150px;">
                </td>
                <td>{{ $promotion->deskripsi }}</td>
                <td>{{ $promotion->tgl }}</td>
                <td>{{ $promotion->waktu }}</td>
                <td>{{ $promotion->harga }}</td>
                <td>{{ $promotion->room->nama }}</td>
                <td>{{ $promotion->instruktur->nama }}</td>
                <td>
                    <a href="{{ route('edit_promo', $promotion->id) }}" class="btn btn-warning btn-sm" style="margin: 5px;">Update</a>
                    <form action="{{ route('hapus_promo', $promotion->id) }}" method="POST" style="display: inline-block; margin: 5px;">
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
