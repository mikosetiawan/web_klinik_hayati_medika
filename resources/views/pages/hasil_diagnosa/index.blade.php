<x-app-layout title="Hasil Diagnosa Dokter">
    <div class="container mt-4">
        <h1 class="mb-4">Form Hasil Diagnosa</h1>
        <form id="hasilDiagnosaForm" action="{{ route('hasil-diagnosa.store') }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="id">
            <div class="mb-3">
                <label for="no_rm" class="form-label">No. Rekam Medis</label>
                <input type="text" id="no_rm" name="no_rm"
                    class="form-control @error('no_rm') is-invalid @enderror" readonly>
                <div class="invalid-feedback">
                    @error('no_rm')
                        {{ $message }}
                    @else
                        Nomor rekam medis wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="dokter_id" class="form-label">Dokter</label>
                <select id="dokter_id" name="dokter_id" class="form-control @error('dokter_id') is-invalid @enderror"
                    required>
                    <option value="" disabled selected>Pilih dokter</option>
                    @foreach ($dokters as $dokter)
                        <option value="{{ $dokter->id }}"
                            {{ old('dokter_id', auth()->user()->id) == $dokter->id_user ? 'selected' : '' }}>
                            {{ $dokter->nama }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    @error('dokter_id')
                        {{ $message }}
                    @else
                        Dokter wajib dipilih.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="id_pendaftaran_pasien" class="form-label">Pendaftaran Pasien</label>
                <select id="id_pendaftaran_pasien" name="id_pendaftaran_pasien"
                    class="form-control @error('id_pendaftaran_pasien') is-invalid @enderror" required>
                    <option value="" disabled selected>Pilih pendaftaran pasien</option>
                    @foreach ($pendaftaranPasiens as $pendaftaran)
                        <option value="{{ $pendaftaran->id }}"
                            {{ old('id_pendaftaran_pasien') == $pendaftaran->id ? 'selected' : '' }}>
                            {{ $pendaftaran->pasien->nama }} (No Antrian: {{ $pendaftaran->no_antrian }})</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    @error('id_pendaftaran_pasien')
                        {{ $message }}
                    @else
                        Pendaftaran pasien wajib dipilih.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="diagnosa_ids" class="form-label">Diagnosa</label>
                <select id="diagnosa_ids" name="diagnosa_ids[]"
                    class="form-control @error('diagnosa_ids') is-invalid @enderror" multiple required>
                    @foreach ($diagnosas as $diagnosa)
                        <option value="{{ $diagnosa->id }}">{{ $diagnosa->nama_diagnosa }} -
                            Rp{{ number_format($diagnosa->harga, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    @error('diagnosa_ids')
                        {{ $message }}
                    @else
                        Pilih setidaknya satu diagnosa.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="obat_ids" class="form-label">Obat</label>
                <select id="obat_ids" name="obat_ids[]" class="form-control @error('obat_ids') is-invalid @enderror"
                    multiple required>
                    @foreach ($obats as $obat)
                        <option value="{{ $obat->id }}">{{ $obat->nama_obat }}
                            Rp{{ number_format($obat->harga, 0, ',', '.') }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    @error('obat_ids')
                        {{ $message }}
                    @else
                        Pilih setidaknya satu obat.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea id="catatan" name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                    placeholder="Masukkan catatan tambahan">{{ old('catatan') }}</textarea>
                <div class="invalid-feedback">
                    @error('catatan')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="tanggal_diagnosa" class="form-label">Tanggal Diagnosa</label>
                <input type="date" id="tanggal_diagnosa" name="tanggal_diagnosa"
                    class="form-control @error('tanggal_diagnosa') is-invalid @enderror" required
                    value="{{ old('tanggal_diagnosa') }}">
                <div class="invalid-feedback">
                    @error('tanggal_diagnosa')
                        {{ $message }}
                    @else
                        Tanggal diagnosa wajib diisi.
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
        </form>

        <!-- Tabel DataTables -->
        <h2 class="mt-5 mb-3">Daftar Hasil Diagnosa</h2>
        <table id="hasilDiagnosaTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>No. RM</th>
                    <th>Dokter</th>
                    <th>Pasien</th>
                    <th>No Antrian</th>
                    <th>Diagnosa</th>
                    <th>Obat</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasilDiagnosas as $hasil)
                    <tr>
                        <td>{{ $hasil->no_rm }}</td>
                        <td>{{ $hasil->dokter->nama }}</td>
                        <td>{{ $hasil->pendaftaranPasien->pasien->nama }}</td>
                        <td>{{ $hasil->pendaftaranPasien->no_antrian }}</td>
                        <td>{{ $hasil->diagnosas->pluck('nama_diagnosa')->implode(', ') }}</td>
                        <td>{{ $hasil->obats->pluck('nama_obat')->implode(', ') }}</td>
                        <td>{{ $hasil->catatan ?? '-' }}</td>
                        <td>{{ $hasil->tanggal_diagnosa }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                onclick='editHasilDiagnosa({{ $hasil->id }}, "{{ $hasil->no_rm }}", {{ $hasil->dokter_id }}, {{ $hasil->id_pendaftaran_pasien }}, {{ json_encode($hasil->diagnosas->pluck('id')->toArray()) }}, {{ json_encode($hasil->obats->pluck('id')->toArray()) }}, "{{ addslashes($hasil->catatan ?? '') }}", "{{ $hasil->tanggal_diagnosa }}")'>Edit</button>
                            <button class="btn btn-danger btn-sm"
                                onclick="deleteHasilDiagnosa({{ $hasil->id }})">Hapus</button>
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
    <!-- Include Selectize.js CDN -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap5.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            $('#hasilDiagnosaTable').DataTable();

            // Inisialisasi Selectize untuk diagnosa
            $('#diagnosa_ids').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                create: false,
                placeholder: 'Pilih diagnosa',
                maxItems: null
            });

            // Inisialisasi Selectize untuk obat
            $('#obat_ids').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                create: false,
                placeholder: 'Pilih obat',
                maxItems: null
            });
        });

        function resetForm() {
            $('#hasilDiagnosaForm')[0].reset();
            $('#hasilDiagnosaForm').attr('action', '{{ route('hasil-diagnosa.store') }}');
            $('#hasilDiagnosaForm').find('input[name="_method"]').val('POST');
            $('#id').val('');
            $('#no_rm').val('');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').hide();
            $('#diagnosa_ids')[0].selectize.clear();
            $('#obat_ids')[0].selectize.clear();
        }

        function editHasilDiagnosa(id, no_rm, dokter_id, id_pendaftaran_pasien, diagnosa_ids, obat_ids, catatan,
            tanggal_diagnosa) {
            $('#id').val(id);
            $('#no_rm').val(no_rm);
            $('#dokter_id').val(dokter_id);
            $('#id_pendaftaran_pasien').val(id_pendaftaran_pasien);
            $('#catatan').val(catatan);
            $('#tanggal_diagnosa').val(tanggal_diagnosa);
            $('#hasilDiagnosaForm').attr('action', '{{ url('hasil-diagnosa') }}/' + id);
            $('#hasilDiagnosaForm').find('input[name="_method"]').val('PUT');

            // Set diagnosa_ids di Selectize
            const diagnosaSelectize = $('#diagnosa_ids')[0].selectize;
            diagnosaSelectize.clear();
            diagnosa_ids.forEach(id => diagnosaSelectize.addItem(id));

            // Set obat_ids di Selectize
            const obatSelectize = $('#obat_ids')[0].selectize;
            obatSelectize.clear();
            obat_ids.forEach(id => obatSelectize.addItem(id));

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function deleteHasilDiagnosa(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data hasil diagnosa akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('hasil-diagnosa') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Hasil diagnosa berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus hasil diagnosa.',
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
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#hasilDiagnosaTable').DataTable();

            // Initialize Selectize for diagnosa
            $('#diagnosa_ids').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                create: false,
                placeholder: 'Pilih diagnosa',
                maxItems: null
            });

            // Initialize Selectize for obat
            $('#obat_ids').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                create: false,
                placeholder: 'Pilih obat',
                maxItems: null
            });

            // Event listener for id_pendaftaran_pasien change
            $('#id_pendaftaran_pasien').on('change', function() {
                const pendaftaranId = $(this).val();
                if (pendaftaranId) {
                    $.ajax({
                        url: '{{ route('hasil-diagnosa.get-no-rm') }}',
                        type: 'POST',
                        data: {
                            id_pendaftaran_pasien: pendaftaranId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.no_rm) {
                                $('#no_rm').val(response.no_rm);
                            } else {
                                $('#no_rm').val('');
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Perhatian!',
                                    text: 'Nomor rekam medis tidak ditemukan untuk pendaftaran ini.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal mengambil nomor rekam medis.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                } else {
                    $('#no_rm').val('');
                }
            });
        });

        function resetForm() {
            $('#hasilDiagnosaForm')[0].reset();
            $('#hasilDiagnosaForm').attr('action', '{{ route('hasil-diagnosa.store') }}');
            $('#hasilDiagnosaForm').find('input[name="_method"]').val('POST');
            $('#id').val('');
            $('#no_rm').val('');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').hide();
            $('#diagnosa_ids')[0].selectize.clear();
            $('#obat_ids')[0].selectize.clear();
        }

        function editHasilDiagnosa(id, no_rm, dokter_id, id_pendaftaran_pasien, diagnosa_ids, obat_ids, catatan,
            tanggal_diagnosa) {
            $('#id').val(id);
            $('#no_rm').val(no_rm);
            $('#dokter_id').val(dokter_id);
            $('#id_pendaftaran_pasien').val(id_pendaftaran_pasien);
            $('#catatan').val(catatan);
            $('#tanggal_diagnosa').val(tanggal_diagnosa);
            $('#hasilDiagnosaForm').attr('action', '{{ url('hasil-diagnosa') }}/' + id);
            $('#hasilDiagnosaForm').find('input[name="_method"]').val('PUT');

            // Set diagnosa_ids di Selectize
            const diagnosaSelectize = $('#diagnosa_ids')[0].selectize;
            diagnosaSelectize.clear();
            diagnosa_ids.forEach(id => diagnosaSelectize.addItem(id));

            // Set obat_ids di Selectize
            const obatSelectize = $('#obat_ids')[0].selectize;
            obatSelectize.clear();
            obat_ids.forEach(id => obatSelectize.addItem(id));

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function deleteHasilDiagnosa(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data hasil diagnosa akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('hasil-diagnosa') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Hasil diagnosa berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus hasil diagnosa.',
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
