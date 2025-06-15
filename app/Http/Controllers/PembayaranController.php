<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\HasilDiagnosa;
use App\Models\PendaftaranPasien;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_pasien_id' => 'required|exists:pendaftaran_pasiens,id',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|in:cash,credit_card,bank_transfer,ewallet',
        ]);

        // Find the HasilDiagnosa related to the PendaftaranPasien
        $hasilDiagnosa = HasilDiagnosa::where('id_pendaftaran_pasien', $request->pendaftaran_pasien_id)
            ->with(['diagnosas', 'obats'])
            ->first();

        if (!$hasilDiagnosa) {
            return redirect()->back()->with('error', 'Hasil diagnosa tidak ditemukan untuk pendaftaran ini.');
        }

        // Calculate total cost from diagnoses and medications
        $totalDiagnosa = $hasilDiagnosa->diagnosas->sum('harga');
        $totalObat = $hasilDiagnosa->obats->sum('harga');
        $calculatedTotal = $totalDiagnosa + $totalObat;

        // Validate that the submitted total_harga matches the calculated total
        if (abs($calculatedTotal - $request->total_harga) > 0.01) {
            return redirect()->back()->with('error', 'Total harga tidak sesuai dengan rincian diagnosa dan obat.');
        }

        // Create Pembayaran record
        Pembayaran::create([
            'hasil_diagnosa_id' => $hasilDiagnosa->id,
            'total_harga' => $request->total_harga,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_pembayaran' => 'completed',
        ]);

        // Update PendaftaranPasien status to 'paid'
        PendaftaranPasien::where('id', $request->pendaftaran_pasien_id)->update(['status' => 'paid']);

        return redirect()->route('pendaftaran-pasien.index')->with('success', 'Pembayaran berhasil disimpan.');
    }
}