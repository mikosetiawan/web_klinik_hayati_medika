<x-app-layout title="Kelola Diagnosa">
    <div class="container mt-4">
        <h1 class="mb-4">Form Diagnosa</h1>
        <form id="diagnosaForm" action="{{ route('kelola-diagnosa.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="id">
            <div class="mb-3">
                <label for="kode_diagnosa" class="form-label">Kode Diagnosa</label>
                <input type="text" id="kode_diagnosa" name="kode_diagnosa" class="form-control @error('kode_diagnosa') is-invalid @enderror" required placeholder="Masukkan kode diagnosa" value="{{ old('kode_diagnosa') }}">
                <div class="invalid-feedback">
                    @error('kode_diagnosa')
                        {{ $message }}
                    @else
                        Kode diagnosa wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="nama_diagnosa" class="form-label">Nama Diagnosa</label>
                <input type="text" id="nama_diagnosa" name="nama_diagnosa" class="form-control @error('nama_diagnosa') is-invalid @enderror" required placeholder="Masukkan nama diagnosa" value="{{ old('nama_diagnosa') }}">
                <div class="invalid-feedback">
                    @error('nama_diagnosa')
                        {{ $message }}
                    @else
                        Nama diagnosa wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" required placeholder="Masukkan deskripsi diagnosa">{{ old('deskripsi') }}</textarea>
                <div class="invalid-feedback">
                    @error('deskripsi')
                        {{ $message }}
                    @else
                        Deskripsi wajib diisi.
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
        <h2 class="mt-5 mb-3">Daftar Diagnosa</h2>
        <table id="diagnosaTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Kode Diagnosa</th>
                    <th>Nama Diagnosa</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diagnosas as $diagnosa)
                    <tr>
                        <td>{{ $diagnosa->kode_diagnosa }}</td>
                        <td>{{ $diagnosa->nama_diagnosa }}</td>
                        <td>{{ $diagnosa->deskripsi }}</td>
                        <td>{{ number_format($diagnosa->harga, 2) }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editDiagnosa({{ $diagnosa->id }}, '{{ $diagnosa->kode_diagnosa }}', '{{ $diagnosa->nama_diagnosa }}', '{{ $diagnosa->deskripsi }}', {{ $diagnosa->harga }})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteDiagnosa({{ $diagnosa->id }})">Hapus</button>
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
            $('#diagnosaTable').DataTable();
        });

        function resetForm() {
            $('#diagnosaForm')[0].reset();
            $('#diagnosaForm').attr('action', '{{ route('kelola-diagnosa.store') }}');
            $('#diagnosaForm').find('input[name="_method"]').val('POST');
            $('#id').val('');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').hide();
        }

        function editDiagnosa(id, kode_diagnosa, nama_diagnosa, deskripsi, harga) {
            $('#id').val(id);
            $('#kode_diagnosa').val(kode_diagnosa);
            $('#nama_diagnosa').val(nama_diagnosa);
            $('#deskripsi').val(deskripsi);
            $('#harga').val(harga);
            $('#diagnosaForm').attr('action', '{{ url('kelola-diagnosa') }}/' + id);
            $('#diagnosaForm').find('input[name="_method"]').val('PUT');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function deleteDiagnosa(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data diagnosa akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('kelola-diagnosa') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Diagnosa berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus diagnosa.',
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