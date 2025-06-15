<x-app-layout title="Kelola Jadwal Praktik">
    <div class="container mt-4">
        <h1 class="mb-4">Form Jadwal Praktik</h1>
        <form id="jadwalForm" action="{{ route('kelola-jadwal-praktik.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="id">
            <div class="mb-3">
                <label for="id_dokter" class="form-label">Dokter</label>
                <select id="id_dokter" name="id_dokter" class="form-select @error('id_dokter') is-invalid @enderror" required>
                    <option value="">Pilih Dokter</option>
                    @foreach($dokters as $dokter)
                        <option value="{{ $dokter->id }}">{{ $dokter->nama }} ({{ $dokter->spesialisasi }})</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    @error('id_dokter')
                        {{ $message }}
                    @else
                        Silakan pilih dokter.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="hari" class="form-label">Hari</label>
                <select id="hari" name="hari" class="form-select @error('hari') is-invalid @enderror" required>
                    <option value="">Pilih Hari</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                        <option value="{{ $day }}">{{ $day }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    @error('hari')
                        {{ $message }}
                    @else
                        Hari wajib dipilih.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" required value="{{ old('jam_mulai') }}">
                <div class="invalid-feedback">
                    @error('jam_mulai')
                        {{ $message }}
                    @else
                        Jam mulai wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                <input type="time" id="jam_selesai" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" required value="{{ old('jam_selesai') }}">
                <div class="invalid-feedback">
                    @error('jam_selesai')
                        {{ $message }}
                    @else
                        Jam selesai wajib diisi dan harus setelah jam mulai.
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
        </form>

        <!-- Tabel DataTables -->
        <h2 class="mt-5 mb-3">Daftar Jadwal Praktik</h2>
        <table id="jadwalTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>Nama Dokter</th>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwals as $jadwal)
                    <tr>
                        <td>{{ $jadwal->dokter->nama }}</td>
                        <td>{{ $jadwal->hari }}</td>
                        <td>{{ $jadwal->jam_mulai }}</td>
                        <td>{{ $jadwal->jam_selesai }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editJadwal({{ $jadwal->id }}, '{{ $jadwal->id_dokter }}', '{{ $jadwal->hari }}', '{{ $jadwal->jam_mulai }}', '{{ $jadwal->jam_selesai }}')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteJadwal({{ $jadwal->id }})">Hapus</button>
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
            $('#jadwalTable').DataTable();
        });

        function resetForm() {
            $('#jadwalForm')[0].reset();
            $('#jadwalForm').attr('action', '{{ route('kelola-jadwal-praktik.store') }}');
            $('#jadwalForm').find('input[name="_method"]').val('POST');
            $('#id').val('');
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').hide();
        }

        function editJadwal(id, id_dokter, hari, jam_mulai, jam_selesai) {
            // Normalize time to HH:MM format
            function formatTime(time) {
                const [hours, minutes] = time.split(':');
                return `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
            }

            $('#id').val(id);
            $('#id_dokter').val(id_dokter);
            $('#hari').val(hari);
            $('#jam_mulai').val(formatTime(jam_mulai));
            $('#jam_selesai').val(formatTime(jam_selesai));
            $('#jadwalForm').attr('action', '{{ url('kelola-jadwal-praktik') }}/' + id);
            $('#jadwalForm').find('input[name="_method"]').val('PUT');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function deleteJadwal(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Jadwal praktik akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('kelola-jadwal-praktik') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Jadwal praktik berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus jadwal praktik.',
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