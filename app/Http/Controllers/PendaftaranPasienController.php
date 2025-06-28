<?php 


namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use App\Models\Pasien;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PendaftaranPasienController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pasiens = collect();

        if ($user->role === 'admin') {
            $pasiens = PendaftaranPasien::with(['pasien.user', 'dokter'])->get();
        } else {
            $pasien = Pasien::where('user_id', $user->id)->first();
            if ($pasien) {
                $pasiens = PendaftaranPasien::where('id_pasien', $pasien->id)
                    ->with(['pasien.user', 'dokter'])
                    ->get();
            }
        }

        $dokters = Dokter::all();

        return view('pages.pendaftaran-pasien.index', compact('pasiens', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:500',
            'no_telepon' => 'required|string|max:15',
            'id_dokter' => 'required|exists:dokters,id',
        ], [
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L (Laki-laki) atau P (Perempuan).',
        ]);

        $user = Auth::user();
        $pasien = Pasien::updateOrCreate(
            ['user_id' => $user->id],
            [
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_telepon' => $request->no_telepon,
            ]
        );

        PendaftaranPasien::create([
            'no_antrian' => PendaftaranPasien::generateNoBooking(),
            'id_dokter' => $request->id_dokter,
            'id_pasien' => $pasien->id,
            'tanggal_pendaftaran' => Carbon::today(),
            'status' => 'booked',
        ]);

        return redirect()->route('pendaftaran-pasien.index')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:confirmed,diagnosis,finished,cancelled,paid',
            ]);

            $pendaftaran = PendaftaranPasien::findOrFail($id);

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

    public function getQueueInfo($id_dokter)
    {
        $count = PendaftaranPasien::where('id_dokter', $id_dokter)
            ->where('tanggal_pendaftaran', Carbon::today())
            ->count();
        $no_antrian = $count + 1;
        $estimasi_waktu = $no_antrian * 15; // Misalnya, setiap pasien 15 menit

        return response()->json([
            'no_antrian' => $no_antrian,
            'estimasi_waktu' => $estimasi_waktu,
        ]);
    }
}