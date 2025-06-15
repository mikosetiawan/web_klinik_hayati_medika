<x-app-layout title="Kelola Dokter">
    <div class="container mt-4">
        <h1 class="mb-4">Form Dokter</h1>
        <form id="dokterForm" action="{{ route('kelola-dokter.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="id">
            <div class="mb-3">
                <label for="id_user" class="form-label">User ID</label>
                <select id="id_user" name="id_user" class="form-select @error('id_user') is-invalid @enderror" required>
                    <option value="">Pilih User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    @error('id_user')
                        {{ $message }}
                    @else
                        Silakan pilih user.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Dokter</label>
                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" required placeholder="Masukkan nama dokter" value="{{ old('nama') }}">
                <div class="invalid-feedback">
                    @error('nama')
                        {{ $message }}
                    @else
                        Nama dokter wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="spesialisasi" class="form-label">Spesialisasi</label>
                <input type="text" id="spesialisasi" name="spesialisasi" class="form-control @error('spesialisasi') is-invalid @enderror" required placeholder="Masukkan spesialisasi" value="{{ old('spesialisasi') }}">
                <div class="invalid-feedback">
                    @error('spesialisasi')
                        {{ $message }}
                    @else
                        Spesialisasi wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" id="no_telepon" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" required placeholder="Masukkan nomor telepon" value="{{ old('no_telepon') }}">
                <div class="invalid-feedback">
                    @error('no_telepon')
                        {{ $message }}
                    @else
                        Nomor telepon wajib diisi.
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
        </form>

        <!-- Tabel DataTables -->
        <h2 class="mt-5 mb-3">Daftar Dokter</h2>
        <table id="dokterTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ID User</th>
                    <th>Nama Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Nomor Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokters as $dokter)
                    <tr>
                        <td>{{ $dokter->id_user }}</td>
                        <td>{{ $dokter->nama }}</td>
                        <td>{{ $dokter->spesialisasi }}</td>
                        <td>{{ $dokter->no_telepon }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editDokter({{ $dokter->id }}, '{{ $dokter->id_user }}', '{{ $dokter->nama }}', '{{ $dokter->spesialisasi }}', '{{ $dokter->no_telepon }}')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteDokter({{ $dokter->id }})">Hapus</button>
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
            $('#dokterTable').DataTable();
        });

        function resetForm() {
            $('#dokterForm')[0].reset();
            $('#dokterForm').attr('action', '{{ route('kelola-dokter.store') }}');
            $('#dokterForm').find('input[name="_method"]').val('POST');
            $('#id').val('');
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').hide();
        }

        function editDokter(id, id_user, nama, spesialisasi, no_telepon) {
            $('#id').val(id);
            $('#id_user').val(id_user);
            $('#nama').val(nama);
            $('#spesialisasi').val(spesialisasi);
            $('#no_telepon').val(no_telepon);
            $('#dokterForm').attr('action', '{{ url('kelola-dokter') }}/' + id);
            $('#dokterForm').find('input[name="_method"]').val('PUT');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function deleteDokter(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data dokter akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('kelola-dokter') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Dokter berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus dokter.',
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