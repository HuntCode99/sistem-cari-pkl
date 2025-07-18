@extends("layout.admin.admin")
@section("content")
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Daftar Perusahaan Terkonfirmasi</h5>
            <!-- Search Form -->
            <form method="GET" action="" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama perusahaan, nomor izin, atau pemilik..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Perusahaan</th>
                            <th>Nomor Izin Usaha</th>
                            <th>Pemilik</th>
                            <th>No Telepon</th>
                            <th>Logo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>


                        <!-- Data perusahaan akan ditampilkan di sini -->
                        @foreach ($perusahaan as $konfir )

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $konfir->nama_perusahaan }}</td>
                            <td>{{ $konfir->nomor_izin_usaha }}</td>
                            <td>{{ $konfir->pemilik }}</td>
                            <td>{{ $konfir->telepon }}</td>
                            <td>
                                <img src="{{ asset('storage') }}/{{ $konfir->logo ?? "-" }}" alt="Logo PT Maju Jaya" width="50">
                            </td>
                            <td>
                                <form action="{{ route('admin.perusahaan.terkonfirmasi.detail', $konfir->perusahaan_id) }}" method="get" class="d-inline">
                                    <button type="submit" class="btn btn-info btn-sm">Detail</button>
                                </form>
                                <form action="{{ route('admin.perusahaan.hapus', $konfir->perusahaan_id) }}" method="post" class="d-inline">
                                    @csrf
                                    @method("delete")
                                    <button type="button" class="btn btn-danger btn-sm btn-konfirmasi">Hapus</button>
                                </form>
                           
                        </tr>
                        @endforeach
                        {{ $perusahaan->links("pagination::bootstrap-5") }}
                    @if($perusahaan->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada perusahaan dengan nama {{ request("search") }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script>
          document.querySelectorAll('.btn-konfirmasi').forEach(button => {
    button.addEventListener('click', function () {
        const form = this.closest('form');
        Swal.fire({
            title: "Yakin ingin menghapus?",
            text: "Data ini tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
    </script>
@endpush