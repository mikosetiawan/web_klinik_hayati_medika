<x-app-layout title="Pendaftaran Pasien">
    <div class="container mt-4">
        <h1 class="mb-4">Pendaftaran Online</h1>
        <hr>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrationModal">
            + Booking
        </button>

        <!-- Modal Pendaftaran -->
        <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalLabel">Form Pendaftaran Pasien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="registrationForm" action="{{ route('pendaftaran-pasien.store') }}" method="POST"
                            class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" readonly
                                    value="{{ auth()->user()->name }}">
                            </div>
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" name="nik" readonly
                                    value="{{ auth()->user()->nik }}">
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir" required value="{{ old('tanggal_lahir') }}">
                                <div class="invalid-feedback">
                                    @error('tanggal_lahir')
                                        {{ $message }}
                                    @else
                                        Tanggal lahir wajib diisi.
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <div class="invalid-feedback">
                                    @error('jenis_kelamin')
                                        {{ $message }}
                                    @else
                                        Jenis kelamin wajib dipilih.
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="4"
                                    required>{{ old('alamat') }}</textarea>
                                <div class="invalid-feedback">
                                    @error('alamat')
                                        {{ $message }}
                                    @else
                                        Alamat wajib diisi.
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                    id="no_telepon" name="no_telepon" required value="{{ old('no_telepon') }}">
                                <div class="invalid-feedback">
                                    @error('no_telepon')
                                        {{ $message }}
                                    @else
                                        Nomor telepon wajib diisi.
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="id_dokter" class="form-label">Pilih Dokter</label>
                                <select class="form-control @error('id_dokter') is-invalid @enderror" id="id_dokter"
                                    name="id_dokter" required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach ($dokters as $dokter)
                                        <option value="{{ $dokter->id }}">{{ $dokter->nama }} - {{ $dokter->spesialisasi }}</option>
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
                                <label for="id_jadwal_praktik" class="form-label">Pilih Jadwal</label>
                                <select class="form-control @error('id_jadwal_praktik') is-invalid @enderror"
                                    id="id_jadwal_praktik" name="id_jadwal_praktik" required>
                                    <option value="">-- Pilih Jadwal --</option>
                                </select>
                                <div class="invalid-feedback">
                                    @error('id_jadwal_praktik')
                                        {{ $message }}
                                    @else
                                        Silakan pilih jadwal.
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="no_antrian" class="form-label">Nomor Antrian</label>
                                <input type="text" class="form-control" id="no_antrian" name="no_antrian" readonly>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="registrationForm" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pendaftaran -->
        <div class="table-responsive mt-4">
            <h4>Daftar Pendaftaran</h4>
            <table class="table table-striped" id="pendaftaranTable">
                <thead>
                    <tr>
                        <th>No. Antrian</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Dokter</th>
                        <th>Jadwal</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Status</th>
                        @if (Auth::check() && Auth::user()->role === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pasiens as $pasien)
                        <tr>
                            <td>{{ $pasien->no_antrian }}</td>
                            <td>{{ $pasien->pasien->user->name }}</td>
                            <td>{{ $pasien->pasien->user->nik }}</td>
                            <td>{{ \Carbon\Carbon::parse($pasien->pasien->tanggal_lahir)->format('d M Y') }}</td>
                            <td>{{ $pasien->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $pasien->pasien->alamat }}</td>
                            <td>{{ $pasien->pasien->no_telepon }}</td>
                            <td>{{ $pasien->jadwalPraktik->dokter->nama }} ({{ $pasien->jadwalPraktik->dokter->spesialisasi }})</td>
                            <td>{{ $pasien->jadwalPraktik->hari }}, {{ $pasien->jadwalPraktik->jam_mulai }} - {{ $pasien->jadwalPraktik->jam_selesai }}</td>
                            <td>{{ \Carbon\Carbon::parse($pasien->tanggal_pendaftaran)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $pasien->status == 'confirmed' ? 'bg-success' : ($pasien->status == 'cancelled' ? 'bg-danger' : ($pasien->status == 'finished' ? 'bg-info' : ($pasien->status == 'diagnosis' ? 'bg-primary' : ($pasien->status == 'paid' ? 'bg-success' : 'bg-warning')))) }}">
                                    {{ $pasien->status == 'booked' ? 'Menunggu Konfirmasi Pasien' : ($pasien->status == 'confirmed' ? 'Sudah Dikonfirmasi, Silahkan Konsultasi' : ($pasien->status == 'diagnosis' ? 'Menunggu Selesai, Lakukan Pembayaran' : ($pasien->status == 'finished' ? 'Selesai, Lakukan Pembayaran' : ($pasien->status == 'paid' ? 'Pembayaran Selesai' : 'Dibatalkan')))) }}
                                </span>
                            </td>
                            @if (Auth::check() && Auth::user()->role === 'admin')
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $pasien->id }}">Detail</button>
                                    @if ($pasien->status == 'booked')
                                        <button class="btn btn-success btn-sm" onclick="updateStatus({{ $pasien->id }}, 'confirmed')">Konfirmasi</button>
                                        <button class="btn btn-warning btn-sm" onclick="updateStatus({{ $pasien->id }}, 'cancelled')">Batal</button>
                                        <button class="btn btn-danger btn-sm" onclick="deletePendaftaran({{ $pasien->id }})">Hapus</button>
                                    @elseif($pasien->status == 'confirmed')
                                        <button class="btn btn-primary btn-sm" onclick="updateStatus({{ $pasien->id }}, 'diagnosis')">Diagnosa</button>
                                    @elseif($pasien->status == 'diagnosis')
                                        <button class="btn btn-info btn-sm" onclick="updateStatus({{ $pasien->id }}, 'finished')">Selesai</button>
                                    @elseif($pasien->status == 'finished')
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#paymentModal{{ $pasien->id }}">Pembayaran</button>
                                    @endif
                                </td>
                            @endif
                        </tr>

                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal{{ $pasien->id }}" tabindex="-1"
                            aria-labelledby="detailModalLabel{{ $pasien->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $pasien->id }}">Detail Pendaftaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <dl class="row">
                                            <dt class="col-sm-4">No. Booking</dt>
                                            <dd class="col-sm-8">{{ $pasien->no_antrian }}</dd>
                                            <dt class="col-sm-4">Nama</dt>
                                            <dd class="col-sm-8">{{ $pasien->pasien->user->name }}</dd>
                                            <dt class="col-sm-4">NIK</dt>
                                            <dd class="col-sm-8">{{ $pasien->pasien->user->nik }}</dd>
                                            <dt class="col-sm-4">Tanggal Lahir</dt>
                                            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($pasien->pasien->tanggal_lahir)->format('d M Y') }}</dd>
                                            <dt class="col-sm-4">Jenis Kelamin</dt>
                                            <dd class="col-sm-8">{{ $pasien->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                                            <dt class="col-sm-4">Alamat</dt>
                                            <dd class="col-sm-8">{{ $pasien->pasien->alamat }}</dd>
                                            <dt class="col-sm-4">No. Telepon</dt>
                                            <dd class="col-sm-8">{{ $pasien->pasien->no_telepon }}</dd>
                                            <dt class="col-sm-4">Dokter</dt>
                                            <dd class="col-sm-8">{{ $pasien->jadwalPraktik->dokter->nama }} ({{ $pasien->jadwalPraktik->dokter->spesialisasi }})</dd>
                                            <dt class="col-sm-4">Jadwal</dt>
                                            <dd class="col-sm-8">{{ $pasien->jadwalPraktik->hari }}, {{ $pasien->jadwalPraktik->jam_mulai }} - {{ $pasien->jadwalPraktik->jam_selesai }}</dd>
                                            <dt class="col-sm-4">Tanggal Pendaftaran</dt>
                                            <dd class="col-sm-8">{{ \Carbon\Carbon::parse($pasien->tanggal_pendaftaran)->format('d M Y') }}</dd>
                                            <dt class="col-sm-4">Status</dt>
                                            <dd class="col-sm-8">
                                                <span class="badge {{ $pasien->status == 'confirmed' ? 'bg-success' : ($pasien->status == 'cancelled' ? 'bg-danger' : ($pasien->status == 'finished' ? 'bg-info' : ($pasien->status == 'diagnosis' ? 'bg-primary' : ($pasien->status == 'paid' ? 'bg-success' : 'bg-warning')))) }}">
                                                    {{ $pasien->status == 'booked' ? 'Menunggu Konfirmasi Pasien' : ($pasien->status == 'confirmed' ? 'Sudah Dikonfirmasi, Silahkan Konsultasi' : ($pasien->status == 'diagnosis' ? 'Menunggu Selesai, Lakukan Pembayaran' : ($pasien->status == 'finished' ? 'Selesai, Lakukan Pembayaran' : ($pasien->status == 'paid' ? 'Pembayaran Selesai' : 'Dibatalkan')))) }}
                                                </span>
                                            </dd>
                                            <!-- Rincian Diagnosa -->
                                            <dt class="col-sm-4">Rincian Diagnosa</dt>
                                            <dd class="col-sm-8">
                                                @php
                                                    $hasilDiagnosa = App\Models\HasilDiagnosa::where('id_pendaftaran_pasien', $pasien->id)
                                                        ->with(['diagnosas', 'obats'])
                                                        ->first();
                                                    $totalHarga = 0;
                                                @endphp
                                                @if (!$hasilDiagnosa)
                                                    <p class="text-danger">Hasil diagnosa tidak ditemukan untuk pendaftaran ini.</p>
                                                @elseif($hasilDiagnosa->diagnosas->isEmpty())
                                                    <p>Tidak ada diagnosa yang tercatat.</p>
                                                @else
                                                    <ul class="list-group">
                                                        @foreach ($hasilDiagnosa->diagnosas as $diagnosa)
                                                            <li class="list-group-item">
                                                                {{ $diagnosa->kode_diagnosa }} - {{ $diagnosa->nama_diagnosa }} (Rp {{ number_format($diagnosa->harga, 2, ',', '.') }})
                                                            </li>
                                                            @php
                                                                $totalHarga += $diagnosa->harga;
                                                            @endphp
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </dd>
                                            <!-- Rincian Obat -->
                                            <dt class="col-sm-4">Rincian Obat</dt>
                                            <dd class="col-sm-8">
                                                @if (!$hasilDiagnosa)
                                                    <p class="text-danger">Hasil diagnosa tidak ditemukan, sehingga data obat tidak tersedia.</p>
                                                @elseif($hasilDiagnosa->obats->isEmpty())
                                                    <p>Tidak ada obat yang tercatat.</p>
                                                @else
                                                    <ul class="list-group">
                                                        @foreach ($hasilDiagnosa->obats as $obat)
                                                            <li class="list-group-item">
                                                                {{ $obat->nama_obat }} (Dosis: {{ $obat->dosis }}) - Rp {{ number_format($obat->harga, 2, ',', '.') }}
                                                            </li>
                                                            @php
                                                                $totalHarga += $obat->harga;
                                                            @endphp
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </dd>
                                            <!-- Total Harga -->
                                            <dt class="col-sm-4">Total Harga</dt>
                                            <dd class="col-sm-8">Rp {{ number_format($totalHarga, 2, ',', '.') }}</dd>
                                        </dl>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-primary" onclick="downloadPDF({{ $pasien->id }})">Download PDF</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Pembayaran -->
                        <div class="modal fade" id="paymentModal{{ $pasien->id }}" tabindex="-1"
                            aria-labelledby="paymentModalLabel{{ $pasien->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentModalLabel{{ $pasien->id }}">Form Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="paymentForm{{ $pasien->id }}" action="{{ route('pembayaran.store') }}" method="POST"
                                            class="needs-validation" novalidate>
                                            @csrf
                                            <input type="hidden" name="pendaftaran_pasien_id" value="{{ $pasien->id }}">

                                            <!-- Rincian Diagnosa -->
                                            <div class="mb-3">
                                                <label class="form-label">Rincian Diagnosa</label>
                                                @php
                                                    $hasilDiagnosa = App\Models\HasilDiagnosa::where('id_pendaftaran_pasien', $pasien->id)
                                                        ->with(['diagnosas', 'obats'])
                                                        ->first();
                                                    $totalHarga = 0;
                                                @endphp
                                                @if (!$hasilDiagnosa)
                                                    <p class="text-danger">Hasil diagnosa tidak ditemukan untuk pendaftaran ini.</p>
                                                @elseif($hasilDiagnosa->diagnosas->isEmpty())
                                                    <p>Tidak ada diagnosa yang tercatat.</p>
                                                @else
                                                    <ul class="list-group">
                                                        @foreach ($hasilDiagnosa->diagnosas as $diagnosa)
                                                            <li class="list-group-item">
                                                                {{ $diagnosa->kode_diagnosa }} - {{ $diagnosa->nama_diagnosa }} (Rp {{ number_format($diagnosa->harga, 2, ',', '.') }})
                                                            </li>
                                                            @php
                                                                $totalHarga += $diagnosa->harga;
                                                            @endphp
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>

                                            <!-- Rincian Obat -->
                                            <div class="mb-3">
                                                <label class="form-label">Rincian Obat</label>
                                                @if (!$hasilDiagnosa)
                                                    <p class="text-danger">Hasil diagnosa tidak ditemukan, sehingga data obat tidak tersedia.</p>
                                                @elseif($hasilDiagnosa->obats->isEmpty())
                                                    <p>Tidak ada obat yang tercatat.</p>
                                                @else
                                                    <ul class="list-group">
                                                        @foreach ($hasilDiagnosa->obats as $obat)
                                                            <li class="list-group-item">
                                                                {{ $obat->nama_obat }} (Dosis: {{ $obat->dosis }}) - Rp {{ number_format($obat->harga, 2, ',', '.') }}
                                                            </li>
                                                            @php
                                                                $totalHarga += $obat->harga;
                                                            @endphp
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>

                                            <!-- Total Harga -->
                                            <div class="mb-3">
                                                <label for="total_harga{{ $pasien->id }}" class="form-label">Total Harga (Rp)</label>
                                                <input type="number" step="0.01" class="form-control @error('total_harga') is-invalid @enderror"
                                                    id="total_harga{{ $pasien->id }}" name="total_harga" required value="{{ $totalHarga }}" readonly>
                                                <div class="invalid-feedback">
                                                    @error('total_harga')
                                                        {{ $message }}
                                                    @else
                                                        Total harga wajib diisi.
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tanggal_pembayaran{{ $pasien->id }}" class="form-label">Tanggal Pembayaran</label>
                                                <input type="date" class="form-control @error('tanggal_pembayaran') is-invalid @enderror"
                                                    id="tanggal_pembayaran{{ $pasien->id }}" name="tanggal_pembayaran" required
                                                    value="{{ old('tanggal_pembayaran', now()->format('Y-m-d')) }}">
                                                <div class="invalid-feedback">
                                                    @error('tanggal_pembayaran')
                                                        {{ $message }}
                                                    @else
                                                        Tanggal pembayaran wajib diisi.
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="metode_pembayaran{{ $pasien->id }}" class="form-label">Metode Pembayaran</label>
                                                <select class="form-control @error('metode_pembayaran') is-invalid @enderror"
                                                    id="metode_pembayaran{{ $pasien->id }}" name="metode_pembayaran" required>
                                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                                    <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                                    <option value="bank_transfer" {{ old('metode_pembayaran') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @error('metode_pembayaran')
                                                        {{ $message }}
                                                    @else
                                                        Metode pembayaran wajib dipilih.
                                                    @enderror
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" form="paymentForm{{ $pasien->id }}" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tabel Jadwal Dokter -->
        <div class="table-responsive mt-4">
            <h4>Jadwal Dokter</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>Hari</th>
                        <th>Jam Praktek</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwals as $jadwal)
                        <tr>
                            <td>{{ $jadwal->dokter->nama }}</td>
                            <td>{{ $jadwal->dokter->spesialisasi }}</td>
                            <td>{{ $jadwal->hari }}</td>
                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        </tr>
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
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#pendaftaranTable').DataTable({
                columnDefs: [{
                    targets: [4, 6, 11],
                    orderable: false
                }]
            });

            $('#id_dokter').on('change', function() {
                const id_dokter = $(this).val();
                const jadwalSelect = $('#id_jadwal_praktik');
                jadwalSelect.empty().append('<option value="">-- Pilih Jadwal --</option>');
                $('#no_antrian').val('');
                $('#estimasi_waktu').val('');

                if (id_dokter) {
                    $.ajax({
                        url: '{{ url('pendaftaran-pasien/jadwal') }}/' + id_dokter,
                        type: 'GET',
                        success: function(data) {
                            data.forEach(function(jadwal) {
                                const optionText = `${jadwal.hari}, ${jadwal.jam_mulai} - ${jadwal.jam_selesai}`;
                                jadwalSelect.append(`<option value="${jadwal.id}">${optionText}</option>`);
                            });
                        },
                        error: function(xhr) {
                            console.error('Error fetching jadwal:', xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal memuat jadwal dokter.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });

            $('#id_jadwal_praktik').on('change', function() {
                const id_jadwal_praktik = $(this).val();
                if (id_jadwal_praktik) {
                    $.ajax({
                        url: '{{ url('pendaftaran-pasien/queue-info') }}/' + id_jadwal_praktik,
                        type: 'GET',
                        success: function(data) {
                            $('#no_antrian').val(data.no_antrian);
                            $('#estimasi_waktu').val(data.estimasi_waktu + ' menit');
                        },
                        error: function(xhr) {
                            console.error('Error fetching queue info:', xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal memuat informasi antrian.',
                                confirmButtonText: 'OK'
                            });
                            $('#no_antrian').val('');
                            $('#estimasi_waktu').val('');
                        }
                    });
                } else {
                    $('#no_antrian').val('');
                    $('#estimasi_waktu').val('');
                }
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

        function updateStatus(id, status) {
            const statusText = status === 'confirmed' ? 'Dikonfirmasi' : (status === 'diagnosis' ? 'Diagnosa' : (status === 'finished' ? 'Selesai' : (status === 'paid' ? 'Pembayaran' : 'Dibatalkan')));
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Status pendaftaran akan diubah menjadi ${statusText}!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('pendaftaran-pasien') }}/' + id,
                        type: 'PUT',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "status": status
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.success || 'Status pendaftaran berhasil diperbarui.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            let errorMessage = 'Terjadi kesalahan saat memperbarui status.';
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

        function deletePendaftaran(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pendaftaran akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('pendaftaran-pasien') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.success || 'Pendaftaran berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            let errorMessage = 'Terjadi kesalahan saat menghapus pendaftaran.';
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

        function downloadPDF(id) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add header (KOP)
            doc.setFontSize(16);
            doc.setFont("helvetica", "bold");
            doc.text("Klinik Hayati Medika", 105, 15, { align: "center" });
            doc.setFontSize(12);
            doc.setFont("helvetica", "normal");
            doc.text("Cilegon - Indonesia", 105, 25, { align: "center" });
            doc.setLineWidth(0.5);
            doc.line(20, 30, 190, 30);

            // Fetch data from the modal
            const modal = document.querySelector(`#detailModal${id}`);
            const noBooking = modal.querySelector('dd:nth-of-type(1)').textContent;
            const nama = modal.querySelector('dd:nth-of-type(2)').textContent;
            const nik = modal.querySelector('dd:nth-of-type(3)').textContent;
            const tanggalLahir = modal.querySelector('dd:nth-of-type(4)').textContent;
            const jenisKelamin = modal.querySelector('dd:nth-of-type(5)').textContent;
            const alamat = modal.querySelector('dd:nth-of-type(6)').textContent;
            const noTelepon = modal.querySelector('dd:nth-of-type(7)').textContent;
            const dokter = modal.querySelector('dd:nth-of-type(8)').textContent;
            const jadwal = modal.querySelector('dd:nth-of-type(9)').textContent;
            const tanggalPendaftaran = modal.querySelector('dd:nth-of-type(10)').textContent;
            const status = modal.querySelector('dd:nth-of-type(11) span').textContent;

            // Extract diagnosis and medication lists as text
            let yPosition = 170;
            const diagnosaItems = modal.querySelectorAll('dd:nth-of-type(12) .list-group-item');
            const diagnosaText = Array.from(diagnosaItems).map(item => item.textContent.trim()).join('\n');
            const obatItems = modal.querySelectorAll('dd:nth-of-type(13) .list-group-item');
            const obatText = Array.from(obatItems).map(item => item.textContent.trim()).join('\n');
            const totalHarga = modal.querySelector('dd:nth-of-type(14)').textContent;

            // Set up PDF content
            doc.setFontSize(16);
            doc.text("Detail Pendaftaran Pasien", 20, 40);
            doc.setFontSize(12);
            doc.text(`No. Booking: ${noBooking}`, 20, 50);
            doc.text(`Nama: ${nama}`, 20, 60);
            doc.text(`NIK: ${nik}`, 20, 70);
            doc.text(`Tanggal Lahir: ${tanggalLahir}`, 20, 80);
            doc.text(`Jenis Kelamin: ${jenisKelamin}`, 20, 90);
            doc.text(`Alamat: ${alamat}`, 20, 100, { maxWidth: 160 });
            doc.text(`No. Telepon: ${noTelepon}`, 20, 120);
            doc.text(`Dokter: ${dokter}`, 20, 130);
            doc.text(`Jadwal: ${jadwal}`, 20, 140);
            doc.text(`Tanggal Pendaftaran: ${tanggalPendaftaran}`, 20, 150);
            doc.text(`Status: ${status}`, 20, 160);

            // Add diagnosis and medication details
            doc.text("Rincian Diagnosa:", 20, yPosition);
            if (diagnosaItems.length > 0) {
                doc.text(diagnosaText, 20, yPosition + 10, { maxWidth: 160 });
                yPosition += 10 + (diagnosaItems.length * 10);
            } else {
                doc.text("Tidak ada diagnosa yang tercatat.", 20, yPosition + 10);
                yPosition += 20;
            }

            doc.text("Rincian Obat:", 20, yPosition);
            if (obatItems.length > 0) {
                doc.text(obatText, 20, yPosition + 10, { maxWidth: 160 });
                yPosition += 10 + (obatItems.length * 10);
            } else {
                doc.text("Tidak ada obat yang tercatat.", 20, yPosition + 10);
                yPosition += 20;
            }

            doc.text(`Total Harga: ${totalHarga}`, 20, yPosition);

            // Save the PDF
            doc.save(`Pendaftaran_${noBooking}.pdf`);
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