<x-app-layout title="Kelola Obat">
    <div class="container mt-4">
        <h1 class="mb-4">Form Obat</h1>
        <form id="obatForm" action="{{ route('kelola-obat.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="id">
            <div class="mb-3">
                <label for="nama_obat" class="form-label">Nama Obat</label>
                <input type="text" id="nama_obat" name="nama_obat" class="form-control @error('nama_obat') is-invalid @enderror" required placeholder="Masukkan nama obat" value="{{ old('nama_obat') }}">
                <div class="invalid-feedback">
                    @error('nama_obat')
                        {{ $message }}
                    @else
                        Nama obat wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" required placeholder="Masukkan deskripsi obat">{{ old('deskripsi') }}</textarea>
                <div class="invalid-feedback">
                    @error('deskripsi')
                        {{ $message }}
                    @else
                        Deskripsi wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="dosis" class="form-label">Dosis</label>
                <input type="text" id="dosis" name="dosis" class="form-control @error('dosis') is-invalid @enderror" required placeholder="Masukkan dosis" value="{{ old('dosis') }}">
                <div class="invalid-feedback">
                    @error('dosis')
                        {{ $message }}
                    @else
                        Dosis wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="number" id="harga" name="harga" class="form-control @error('harga') is-invalid @enderror" required placeholder="Masukkan harga" value="{{ old('harga') }}" min="0">
                <div class="invalid-feedback">
                    @error('harga')
                        {{ $message }}
                    @else
                        Harga wajib diisi.
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
        </form>

        <!-- Tabel DataTables -->
        <h2 class="mt-5 mb-3">Daftar Obat</h2>
        <table id="obatTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Deskripsi</th>
                    <th>Dosis</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($obats as $obat)
                    <tr>
                        <td>{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->deskripsi }}</td>
                        <td>{{ $obat->dosis }}</td>
                        <td>{{ number_format($obat->harga, 2) }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editObat({{ $obat->id }}, '{{ $obat->nama_obat }}', '{{ $obat->deskripsi }}', '{{ $obat->dosis }}', {{ $obat->harga }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteObat({{ $obat->id }})">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Include SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery and DataTables CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#obatTable').DataTable();
        });

        function resetForm() {
            $('#obatForm')[0].reset();
            $('#obatForm').attr('action', '{{ route('kelola-obat.store') }}');
            $('#obatForm').find('input[name="_method"]').val('POST');
            $('#id').val('');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').hide();
        }

        function editObat(id, nama_obat, deskripsi, dosis, harga) {
            $('#id').val(id);
            $('#nama_obat').val(nama_obat);
            $('#deskripsi').val(deskripsi);
            $('#dosis').val(dosis);
            $('#harga').val(harga);
            $('#obatForm').attr('action', '{{ url('kelola-obat') }}/' + id);
            $('#obatForm').find('input[name="_method"]').val('PUT');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function deleteObat(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data obat akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('kelola-obat') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Obat berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus obat.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: '@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
</x-app-layout>