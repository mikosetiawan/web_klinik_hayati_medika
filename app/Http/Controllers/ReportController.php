<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Existing index method (unchanged)
    public function index(Request $request)
    {
        $startDate = null;
        $endDate = null;

        $query = PendaftaranPasien::with([
            'pasien',
            'jadwalPraktik.dokter',
            'hasilDiagnosa.diagnosas',
            'hasilDiagnosa.obats',
            'hasilDiagnosa.pembayaran'
        ]);

        // Apply date range filter if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('tanggal_pendaftaran', [$startDate, $endDate]);
        }

        $pendaftarans = $query->get();

        return view('pages.pendaftaran-pasien.report', compact('pendaftarans', 'startDate', 'endDate'));
    }

    // New method for medical records report
    public function medicalRecords(Request $request)
    {
        $startDate = null;
        $endDate = null;

        $query = PendaftaranPasien::with([
            'pasien',
            'jadwalPraktik.dokter',
            'hasilDiagnosa.diagnosas',
            'hasilDiagnosa.obats',
            'hasilDiagnosa.pembayaran',
            'hasilDiagnosa.dokter'
        ])->whereHas('hasilDiagnosa'); // Only include records with diagnoses

        // Apply date range filter if provided
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('tanggal_pendaftaran', [$startDate, $endDate]);
        }

        $pendaftarans = $query->get();

        return view('pages.pendaftaran-pasien.medical-records', compact('pendaftarans', 'startDate', 'endDate'));
    }
}