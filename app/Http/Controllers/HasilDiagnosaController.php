<?php

namespace App\Http\Controllers;

use App\Models\HasilDiagnosa;
use App\Models\Diagnosa;
use App\Models\Obat;
use App\Models\Dokter;
use App\Models\PendaftaranPasien;
use Illuminate\Http\Request;

class HasilDiagnosaController extends Controller
{
    public function index()
    {
        $hasilDiagnosas = HasilDiagnosa::with(['dokter', 'pendaftaranPasien.pasien', 'diagnosas', 'obats'])->get();
        $dokters = Dokter::all();
        $pendaftaranPasiens = PendaftaranPasien::where('status', 'confirmed')->get();
        $diagnosas = Diagnosa::all();
        $obats = Obat::all();
        return view('pages.hasil_diagnosa.index', compact('hasilDiagnosas', 'dokters', 'pendaftaranPasiens', 'diagnosas', 'obats'));
    }

    private function generateNoRM()
    {
        $lastRecord = HasilDiagnosa::orderBy('no_rm', 'desc')->first();
        $lastNumber = $lastRecord ? (int) str_replace('RM', '', $lastRecord->no_rm) : 0;
        $newNumber = $lastNumber + 1;
        return 'RM' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getNoRM(Request $request)
    {
        $pendaftaranId = $request->input('id_pendaftaran_pasien');
        $hasilDiagnosa = HasilDiagnosa::where('id_pendaftaran_pasien', $pendaftaranId)->first();

        if ($hasilDiagnosa) {
            return response()->json(['no_rm' => $hasilDiagnosa->no_rm]);
        }

        // Generate new no_rm if none exists
        $no_rm = $this->generateNoRM();
        return response()->json(['no_rm' => $no_rm]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'id_pendaftaran_pasien' => 'required|exists:pendaftaran_pasiens,id',
            'diagnosa_ids' => 'required|array|min:1',
            'diagnosa_ids.*' => 'exists:diagnosas,id',
            'obat_ids' => 'required|array|min:1',
            'obat_ids.*' => 'exists:obats,id',
            'catatan' => 'nullable|string',
            'tanggal_diagnosa' => 'required|date',
        ]);

        $hasilDiagnosa = HasilDiagnosa::create([
            'no_rm' => $this->generateNoRM(),
            'dokter_id' => $request->dokter_id,
            'id_pendaftaran_pasien' => $request->id_pendaftaran_pasien,
            'catatan' => $request->catatan,
            'tanggal_diagnosa' => $request->tanggal_diagnosa,
        ]);

        // Attach diagnosa dan obat ke hasil diagnosa
        $hasilDiagnosa->diagnosas()->attach($request->diagnosa_ids);
        $hasilDiagnosa->obats()->attach($request->obat_ids);

        // Update status PendaftaranPasien
        PendaftaranPasien::where('id', $request->id_pendaftaran_pasien)->update(['status' => 'diagnosis']);

        return redirect()->route('hasil-diagnosa.index')->with('success', 'Hasil diagnosa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'id_pendaftaran_pasien' => 'required|exists:pendaftaran_pasiens,id',
            'diagnosa_ids' => 'required|array|min:1',
            'diagnosa_ids.*' => 'exists:diagnosas,id',
            'obat_ids' => 'required|array|min:1',
            'obat_ids.*' => 'exists:obats,id',
            'catatan' => 'nullable|string',
            'tanggal_diagnosa' => 'required|date',
        ]);

        $hasilDiagnosa = HasilDiagnosa::findOrFail($id);
        $hasilDiagnosa->update([
            'dokter_id' => $request->dokter_id,
            'id_pendaftaran_pasien' => $request->id_pendaftaran_pasien,
            'catatan' => $request->catatan,
            'tanggal_diagnosa' => $request->tanggal_diagnosa,
        ]);

        // Sync diagnosa dan obat
        $hasilDiagnosa->diagnosas()->sync($request->diagnosa_ids);
        $hasilDiagnosa->obats()->sync($request->obat_ids);

        // Update status PendaftaranPasien
        PendaftaranPasien::where('id', $request->id_pendaftaran_pasien)->update(['status' => 'diagnosa_obat_pemeriksaan_berhasil']);

        return redirect()->route('hasil-diagnosa.index')->with('success', 'Hasil diagnosa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $hasilDiagnosa = HasilDiagnosa::findOrFail($id);
        $hasilDiagnosa->diagnosas()->detach();
        $hasilDiagnosa->obats()->detach();
        $hasilDiagnosa->delete();

        return response()->json(['success' => 'Hasil diagnosa berhasil dihapus.']);
    }
}
