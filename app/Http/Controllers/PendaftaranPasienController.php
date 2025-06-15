<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\JadwalPraktik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PendaftaranPasienController extends Controller
{
    // Tampilkan semua pendaftaran berdasarkan peran pengguna
    public function index()
    {
        $user = Auth::user();
        $pasiens = collect();

        if ($user->role === 'admin') {
            $pasiens = PendaftaranPasien::with(['pasien.user', 'jadwalPraktik.dokter'])->get();
        } elseif ($user->role === 'pasien') {
            $pasiens = PendaftaranPasien::with(['pasien.user', 'jadwalPraktik.dokter'])
                ->whereHas('pasien', fn($q) => $q->where('user_id', $user->id))
                ->get();
        } elseif ($user->role === 'dokter') {
            $dokter = Dokter::where('nik', $user->nik)->first();
            if ($dokter) {
                $pasiens = PendaftaranPasien::with(['pasien.user', 'jadwalPraktik.dokter'])
                    ->whereHas('jadwalPraktik', fn($q) => $q->where('id_dokter', $dokter->id))
                    ->get();
            }
        }

        $dokters = Dokter::all();
        $jadwals = JadwalPraktik::with('dokter')->get();

        return view('pages.pendaftaran-pasien.index', compact('pasiens', 'dokters', 'jadwals'));
    }

    // Simpan data pendaftaran pasien
    public function store(Request $request)
    {
        $request->validate(
            [
                'tanggal_lahir' => 'required|date|before:today',
                'jenis_kelamin' => 'required|in:L,P',
                'alamat' => 'required|string|max:500',
                'no_telepon' => 'required|string|max:15',
                'id_dokter' => 'required|exists:dokters,id',
                'id_jadwal_praktik' => 'required|exists:jadwal_praktiks,id',
            ],
            [
                'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
                'jenis_kelamin.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
            ],
        );

        // Validasi hubungan jadwal dengan dokter
        $jadwal = JadwalPraktik::findOrFail($request->id_jadwal_praktik);
        if ($jadwal->id_dokter != $request->id_dokter) {
            return redirect()->route('pendaftaran-pasien.index')->with('error', 'Jadwal tidak sesuai dengan dokter yang dipilih.');
        }

        // Simpan atau update data pasien terkait user
        $user = Auth::user();
        $pasien = Pasien::updateOrCreate(
            ['user_id' => $user->id],
            [
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
            ],
        );

        // Simpan pendaftaran pasien
        PendaftaranPasien::create([
            'no_antrian' => PendaftaranPasien::generateNoBooking(),
            'id_jadwal_praktik' => $request->id_jadwal_praktik,
            'id_pasien' => $pasien->id,
            'tanggal_pendaftaran' => Carbon::today(),
            'status' => 'booked',
        ]);

        return redirect()->route('pendaftaran-pasien.index')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    // Perbarui status pendaftaran pasien
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:confirmed,diagnosis,finished,cancelled,paid',
            ]);

            $pendaftaran = PendaftaranPasien::findOrFail($id);

            // Validasi perubahan status
            $validTransitions = [
                'booked' => ['confirmed', 'cancelled'],
                'confirmed' => ['diagnosis', 'cancelled'],
                'diagnosis' => ['finished', 'cancelled'],
            ];

            if (in_array($pendaftaran->status, ['finished', 'cancelled', 'paid'])) {
                return response()->json(['error' => 'Pendaftaran tidak dapat diubah.'], 422);
            }

            $allowed = $validTransitions[$pendaftaran->status] ?? [];
            if (!in_array($request->status, $allowed)) {
                return response()->json(['error' => 'Perubahan status tidak valid.'], 422);
            }

            $pendaftaran->update(['status' => $request->status]);

            return response()->json([
                'success' => 'Status pendaftaran berhasil diperbarui.',
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Update error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Hapus pendaftaran pasien
    public function destroy($id)
    {
        try {
            $pendaftaran = PendaftaranPasien::findOrFail($id);
            $pendaftaran->delete();

            return response()->json(['success' => 'Pendaftaran berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Delete error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    // Ambil jadwal praktik berdasarkan dokter
    public function getJadwal(Request $request, $id_dokter)
    {
        try {
            Log::info('Mengambil jadwal untuk dokter ID: ' . $id_dokter); // Debugging
            $jadwals = JadwalPraktik::where('id_dokter', $id_dokter)->select('id', 'hari', 'jam_mulai', 'jam_selesai')->get();

            Log::info('Jadwal ditemukan: ', $jadwals->toArray()); // Debugging
            if ($jadwals->isEmpty()) {
                return response()->json(['message' => 'Tidak ada jadwal tersedia untuk dokter ini.']);
            }

            return response()->json($jadwals);
        } catch (\Exception $e) {
            Log::error('Get jadwal error', ['id_dokter' => $id_dokter, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal memuat jadwal: ' . $e->getMessage()], 500);
        }
    }

    // Ambil informasi antrian untuk jadwal tertentu
    public function getQueueInfo($id_jadwal_praktik)
    {
        try {
            $jadwal = JadwalPraktik::find($id_jadwal_praktik);
            if (!$jadwal) {
                return response()->json(['error' => 'Jadwal tidak ditemukan.'], 404);
            }

            $no_antrian = PendaftaranPasien::generateNoBooking();
            $queue_count = PendaftaranPasien::where('id_jadwal_praktik', $id_jadwal_praktik)
                ->where('tanggal_pendaftaran', Carbon::today())
                ->whereIn('status', ['booked', 'confirmed'])
                ->count();

            $estimasi_waktu = $queue_count * 15;

            return response()->json([
                'no_antrian' => $no_antrian,
                'estimasi_waktu' => $estimasi_waktu,
            ]);
        } catch (\Exception $e) {
            Log::error('Get queue info error', ['id_jadwal_praktik' => $id_jadwal_praktik, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal mengambil informasi antrian: ' . $e->getMessage()], 500);
        }
    }
}
