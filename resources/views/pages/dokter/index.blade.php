<x-app-layout title="Kelola Dokter">
    <div class="container mt-4">
        <h1 class="mb-4">Kelola Dokter</h1>
        <hr>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDokterModal">
            + Tambah Dokter
        </button>

        <!-- Modal Tambah Dokter -->
        <div class="modal fade" id="addDokterModal" tabindex="-1" aria-labelledby="addDokterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDokterModalLabel">Tambah Dokter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addDokterForm" action="{{ route('kelola-dokter.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="id_user" class="form-label">Pilih User</label>
                                <select class="form-control @error('id_user') is-invalid @enderror" id="id_user" name="id_user" required>
                                    <option value="">-- Pilih User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('id_user') {{ $message }} @else User wajib dipilih. @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" required value="{{ old('nama') }}">
                                <div class="invalid-feedback">
                                    @error('nama') {{ $message }} @else Nama wajib diisi. @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="spesialisasi" class="form-label">Spesialisasi</label>
                                <input type="text" class="form-control @error('spesialisasi') is-invalid @enderror" id="spesialisasi" name="spesialisasi" required value="{{ old('spesialisasi') }}">
                                <div class="invalid-feedback">
                                    @error('spesialisasi') {{ $message }} @else Spesialisasi wajib diisi. @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon" name="no_telepon" required value="{{ old('no_telepon') }}">
                                <div class="invalid-feedback">
                                    @error('no_telepon') {{ $message }} @else Nomor telepon wajib diisi. @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" required value="{{ old('jam_mulai') }}">
                                <div class="invalid-feedback">
                                    @error('jam_mulai') {{ $message }} @else Jam mulai wajib diisi. @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" required value="{{ old('jam_selesai') }}">
                                <div class="invalid-feedback">
                                    @error('jam_selesai') {{ $message }} @else Jam selesai wajib diisi dan harus setelah jam mulai. @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="hari_mulai" class="form-label">Hari Mulai</label>
                                <select class="form-control @error('hari_mulai') is-invalid @enderror" id="hari_mulai" name="hari_mulai" required>
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                        <option value="{{ $hari }}" {{ old('hari_mulai') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('hari_mulai') {{ $message }} @else Hari mulai wajib dipilih. @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="hari_selesai" class="form-label">Hari Selesai</label>
                                <select class="form-control @error('hari_selesai') is-invalid @enderror" id="hari_selesai" name="hari_selesai" required>
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                        <option value="{{ $hari }}" {{ old('hari_selesai') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    @error('hari_selesai') {{ $message }} @else Hari selesai wajib dipilih. @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="addDokterForm" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Dokter -->
        <div class="table-responsive mt-4">
            <h4>Daftar Dokter</h4>
            <table class="table table-striped" id="dokterTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Spesialisasi</th>
                        <th>No. Telepon</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dokters as $dokter)
                        <tr>
                            <td>{{ $dokter->nama }}</td>
                            <td>{{ $dokter->user->email }}</td>
                            <td>{{ $dokter->spesialisasi }}</td>
                            <td>{{ $dokter->no_telepon }}</td>
                            <td>{{ $dokter->hari_mulai }} s/d {{ $dokter->hari_selesai }}, {{ $dokter->jam_mulai }} - {{ $dokter->jam_selesai }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editDokterModal{{ $dokter->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteDokter({{ $dokter->id }})">Hapus</button>
                            </td>
                        </tr>

                        <!-- Modal Edit Dokter -->
                        <div class="modal fade" id="editDokterModal{{ $dokter->id }}" tabindex="-1" aria-labelledby="editDokterModalLabel{{ $dokter->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editDokterModalLabel{{ $dokter->id }}">Edit Dokter</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editDokterForm{{ $dokter->id }}" action="{{ route('kelola-dokter.update', $dokter->id) }}" method="POST" class="needs-validation" novalidate>
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="id_user{{ $dokter->id }}" class="form-label">Pilih User</label>
                                                <select class="form-control @error('id_user') is-invalid @enderror" id="id_user{{ $dokter->id }}" name="id_user" required>
                                                    <option value="">-- Pilih User --</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}" {{ $dokter->id_user == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                                    @endforeach
                                                    <option value="{{ $dokter->user->id }}" selected>{{ $dokter->user->name }} ({{ $dokter->user->email }})</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @error('id_user') {{ $message }} @else User wajib dipilih. @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama{{ $dokter->id }}" class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama{{ $dokter->id }}" name="nama" required value="{{ old('nama', $dokter->nama) }}">
                                                <div class="invalid-feedback">
                                                    @error('nama') {{ $message }} @else Nama wajib diisi. @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="spesialisasi{{ $dokter->id }}" class="form-label">Spesialisasi</label>
                                                <input type="text" class="form-control @error('spesialisasi') is-invalid @enderror" id="spesialisasi{{ $dokter->id }}" name="spesialisasi" required value="{{ old('spesialisasi', $dokter->spesialisasi) }}">
                                                <div class="invalid-feedback">
                                                    @error('spesialisasi') {{ $message }} @else Spesialisasi wajib diisi. @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="no_telepon{{ $dokter->id }}" class="form-label">No. Telepon</label>
                                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon{{ $dokter->id }}" name="no_telepon" required value="{{ old('no_telepon', $dokter->no_telepon) }}">
                                                <div class="invalid-feedback">
                                                    @error('no_telepon') {{ $message }} @else Nomor telepon wajib diisi. @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jam_mulai{{ $dokter->id }}" class="form-label">Jam Mulai</label>
                                                <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai{{ $dokter->id }}" name="jam_mulai" required value="{{ old('jam_mulai', $dokter->jam_mulai) }}">
                                                <div class="invalid-feedback">
                                                    @error('jam_mulai') {{ $message }} @else Jam mulai wajib diisi. @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="jam_selesai{{ $dokter->id }}" class="form-label">Jam Selesai</label>
                                                <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai{{ $dokter->id }}" name="jam_selesai" required value="{{ old('jam_selesai', $dokter->jam_selesai) }}">
                                                <div class="invalid-feedback">
                                                    @error('jam_selesai') {{ $message }} @else Jam selesai wajib diisi dan harus setelah jam mulai. @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="hari_mulai{{ $dokter->id }}" class="form-label">Hari Mulai</label>
                                                <select class="form-control @error('hari_mulai') is-invalid @enderror" id="hari_mulai{{ $dokter->id }}" name="hari_mulai" required>
                                                    <option value="">-- Pilih Hari --</option>
                                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                                        <option value="{{ $hari }}" {{ old('hari_mulai', $dokter->hari_mulai) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    @error('hari_mulai') {{ $message }} @else Hari mulai wajib dipilih. @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="hari_selesai{{ $dokter->id }}" class="form-label">Hari Selesai</label>
                                                <select class="form-control @error('hari_selesai') is-invalid @enderror" id="hari_selesai{{ $dokter->id }}" name="hari_selesai" required>
                                                    <option value="">-- Pilih Hari --</option>
                                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                                                        <option value="{{ $hari }}" {{ old('hari_selesai', $dokter->hari_selesai) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    @error('hari_selesai') {{ $message }} @else Hari selesai wajib dipilih. @enderror
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" form="editDokterForm{{ $dokter->id }}" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#dokterTable').DataTable({
                columnDefs: [{
                    targets: [5],
                    orderable: false
                }]
            });

            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });

        function deleteDokter(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Dokter akan dihapus secara permanen!",
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
                                text: response.success || 'Dokter berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            let errorMessage = 'Terjadi kesalahan saat menghapus dokter.';
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            } else if (xhr.status === 419) {
                                errorMessage = 'Sesi telah kedaluwarsa. Silakan muat ulang halaman.';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: '@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
</x-app-layout>