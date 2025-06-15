<x-app-layout title="Laporan Pendaftaran Pasien">
    <div class="container mt-4">
        <h1 class="mb-4">Laporan Pendaftaran Pasien</h1>
        <hr>

        <!-- Date Range Filter Form -->
        <div class="mb-4">
            <form method="GET" action="{{ route('report.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('report.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tabel Laporan -->
        <div class="table-responsive mt-4">
            <table class="table table-striped" id="reportTable">
                <thead>
                    <tr>
                        <th>No. Antrian</th>
                        <th>Nama Pasien</th>
                        <th>NIK</th>
                        <th>Dokter</th>
                        <th>Jadwal</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Status</th>
                        <th>Diagnosa</th>
                        <th>Obat</th>
                        <th>Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftarans as $pendaftaran)
                        <tr>
                            <td>{{ $pendaftaran->no_antrian }}</td>
                            <td>{{ $pendaftaran->pasien->nama }}</td>
                            <td>{{ $pendaftaran->pasien->nik }}</td>
                            <td>{{ $pendaftaran->jadwalPraktik->dokter->nama }} ({{ $pendaftaran->jadwalPraktik->dokter->spesialisasi }})</td>
                            <td>{{ $pendaftaran->jadwalPraktik->hari }}, {{ $pendaftaran->jadwalPraktik->jam_mulai }} - {{ $pendaftaran->jadwalPraktik->jam_selesai }}</td>
                            <td>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pendaftaran)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $pendaftaran->status == 'confirmed' ? 'bg-success' : ($pendaftaran->status == 'cancelled' ? 'bg-danger' : ($pendaftaran->status == 'finished' ? 'bg-info' : ($pendaftaran->status == 'diagnosis' ? 'bg-primary' : ($pendaftaran->status == 'paid' ? 'bg-success' : 'bg-warning')))) }}">
                                    {{ $pendaftaran->status == 'booked' ? 'Menunggu Konfirmasi' : ($pendaftaran->status == 'confirmed' ? 'Dikonfirmasi' : ($pendaftaran->status == 'diagnosis' ? 'Diagnosa' : ($pendaftaran->status == 'finished' ? 'Selesai' : ($pendaftaran->status == 'paid' ? 'Lunas' : 'Dibatalkan')))) }}
                                </span>
                            </td>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap CSS (for styling) -->
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
            $('#reportTable').DataTable({
                dom: 'Bfrtip', // Place buttons above the table
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Laporan Pendaftaran Pasien',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6] // Exclude Diagnosa, Obat, Pembayaran
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Laporan Pendaftaran Pasien',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6] // Exclude Diagnosa, Obat, Pembayaran
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        title: 'Laporan Pendaftaran Pasien',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6] // Exclude Diagnosa, Obat, Pembayaran
                        }
                    }
                ],
                columnDefs: [
                    { targets: [7, 8, 9], orderable: false } // Disable sorting for Diagnosa, Obat, and Pembayaran
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