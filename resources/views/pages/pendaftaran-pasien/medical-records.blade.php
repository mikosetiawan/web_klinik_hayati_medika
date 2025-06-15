<x-app-layout title="Laporan Administrasi Rekam Medis Pasien">
    <div class="container mt-4">
        <h1 class="mb-4">Laporan Administrasi Rekam Medis Pasien</h1>
        <hr>

        <!-- Date Range Filter Form -->
        <div class="mb-4">
            <form method="GET" action="{{ route('report.medical-records') }}" class="row g-3 filter-form">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Awal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('report.medical-records') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tabel Laporan -->
        <div class="table-responsive mt-4">
            <table class="table table-striped" id="medicalRecordsTable">
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>NIK</th>
                        <th>Dokter Diagnosa</th>
                        <th>Tanggal Diagnosa</th>
                        <th>Diagnosa</th>
                        <th>Obat</th>
                        <th>Catatan</th>
                        <th>Pembayaran</th>
                        <th>Status Pendaftaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftarans as $pendaftaran)
                        <tr>
                            <td>{{ $pendaftaran->hasilDiagnosa->no_rm }}</td>
                            <td>{{ $pendaftaran->pasien->nama }}</td>
                            <td>{{ $pendaftaran->pasien->nik }}</td>
                            <td>{{ $pendaftaran->hasilDiagnosa->dokter->nama }} ({{ $pendaftaran->hasilDiagnosa->dokter->spesialisasi }})</td>
                            <td>{{ \Carbon\Carbon::parse($pendaftaran->hasilDiagnosa->tanggal_diagnosa)->format('d M Y') }}</td>
                            <td>
                                @if($pendaftaran->hasilDiagnosa && $pendaftaran->hasilDiagnosa->diagnosas->isNotEmpty())
                                    <ul class="list-group list-group-flush">
                                        @foreach($pendaftaran->hasilDiagnosa->diagnosas as $diagnosa)
                                            <li class="list-group-item">
                                                {{ $diagnosa->kode_diagnosa }} - {{ $diagnosa->nama_diagnosa }} (Rp {{ number_format($diagnosa->harga, 2, ',', '.') }})
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Tidak ada diagnosa</span>
                                @endif
                            </td>
                            <td>
                                @if($pendaftaran->hasilDiagnosa && $pendaftaran->hasilDiagnosa->obats->isNotEmpty())
                                    <ul class="list-group list-group-flush">
                                        @foreach($pendaftaran->hasilDiagnosa->obats as $obat)
                                            <li class="list-group-item">
                                                {{ $obat->nama_obat }} (Dosis: {{ $obat->dosis }}) - Rp {{ number_format($obat->harga, 2, ',', '.') }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Tidak ada obat</span>
                                @endif
                            </td>
                            <td>{{ $pendaftaran->hasilDiagnosa->catatan ?? 'Tidak ada catatan' }}</td>
                            <td>
                                @if($pendaftaran->hasilDiagnosa && $pendaftaran->hasilDiagnosa->pembayaran)
                                    <div>
                                        Total: Rp {{ number_format($pendaftaran->hasilDiagnosa->pembayaran->total_harga, 2, ',', '.') }}<br>
                                        Tanggal: {{ \Carbon\Carbon::parse($pendaftaran->hasilDiagnosa->pembayaran->tanggal_pembayaran)->format('d M Y') }}<br>
                                        Metode: {{ str_replace('_', ' ', ucwords($pendaftaran->hasilDiagnosa->pembayaran->metode_pembayaran)) }}<br>
                                        Status: <span class="badge bg-success">{{ $pendaftaran->hasilDiagnosa->pembayaran->status_pembayaran }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Belum dibayar</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $pendaftaran->status == 'confirmed' ? 'bg-success' : ($pendaftaran->status == 'cancelled' ? 'bg-danger' : ($pendaftaran->status == 'finished' ? 'bg-info' : ($pendaftaran->status == 'diagnosis' ? 'bg-primary' : ($pendaftaran->status == 'paid' ? 'bg-success' : 'bg-warning')))) }}">
                                    {{ $pendaftaran->status == 'booked' ? 'Menunggu Konfirmasi' : ($pendaftaran->status == 'confirmed' ? 'Dikonfirmasi' : ($pendaftaran->status == 'diagnosis' ? 'Diagnosa' : ($pendaftaran->status == 'finished' ? 'Selesai' : ($pendaftaran->status == 'paid' ? 'Lunas' : 'Dibatalkan')))) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#medicalRecordsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Laporan Administrasi Rekam Medis Pasien',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] // Exclude Pembayaran
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Laporan Administrasi Rekam Medis Pasien',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] // Exclude Pembayaran
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: 'Laporan Administrasi Rekam Medis Pasien',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] // Exclude Pembayaran
                        }
                    }
                ],
                columnDefs: [
                    { targets: [5, 6, 7, 8], orderable: false } // Disable sorting for Diagnosa, Obat, Catatan, Pembayaran
                ],
                pageLength: 10
            });
        });
    </script>

    <!-- Print-specific CSS -->
    <style>
        @media print {
            .dt-buttons, .filter-form {
                display: none;
            }
            .dataTables_filter, .dataTables_paginate {
                display: none;
            }
        }
    </style>
</x-app-layout>