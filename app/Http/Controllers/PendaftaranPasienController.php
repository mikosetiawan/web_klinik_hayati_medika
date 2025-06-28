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

        // Hitung nomor antrian berdasarkan dokter dan tanggal pendaftaran
        $today = Carbon::today();
        $count = PendaftaranPasien::where('id_dokter', $request->id_dokter)
            ->where('tanggal_pendaftaran', $today)
            ->count();
        $no_antrian = 'P' . str_pad($count + 1, 3, '0', STR_PAD_LEFT) . '-' . $request->id_dokter . '-' . $today->format('Ymd');

        PendaftaranPasien::create([
            'no_antrian' => $no_antrian,
            'id_dokter' => $request->id_dokter,
            'id_pasien' => $pasien->id,
            'tanggal_pendaftaran' => $today,
            'status' => 'booked',
        ]);

        return redirect()->route('pendaftaran-pasien.index')->with('success', 'Pendaftaran berhasil disimpan.');
    }

    /**
     * Get queue number for any authenticated user (admin or patient)
     * Fixed: Simplified authentication check and improved error handling
     */
    public function getQueueNumber($id_dokter)
    {
        try {
            // Enhanced logging untuk debugging
            Log::info('Queue number request started', [
                'id_dokter' => $id_dokter,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role ?? 'no_role',
                'user_name' => Auth::user()->name ?? 'no_name',
                'request_ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            // Validate if user is authenticated
            if (!Auth::check()) {
                Log::warning('Unauthorized access to queue number', [
                    'id_dokter' => $id_dokter,
                    'session_id' => session()->getId()
                ]);
                return response()->json(['error' => 'Akses tidak diizinkan'], 401);
            }

            // Convert id_dokter to integer for better validation
            $id_dokter = (int) $id_dokter;

            // Validate doctor exists
            $dokter = Dokter::find($id_dokter);
            if (!$dokter) {
                Log::warning('Doctor not found', [
                    'id_dokter' => $id_dokter,
                    'user_id' => Auth::id()
                ]);
                return response()->json(['error' => 'Dokter tidak ditemukan'], 404);
            }

            // Get today's date
            $today = Carbon::today();

            // Count existing registrations for this doctor today dengan logging
            $count = PendaftaranPasien::where('id_dokter', $id_dokter)
                ->whereDate('tanggal_pendaftaran', $today)
                ->count();

            Log::info('Queue count calculated', [
                'id_dokter' => $id_dokter,
                'today' => $today->format('Y-m-d'),
                'existing_count' => $count,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role
            ]);

            // Generate queue number
            $no_antrian = 'P' . str_pad($count + 1, 3, '0', STR_PAD_LEFT) . '-' . $id_dokter . '-' . $today->format('Ymd');

            // Get doctor information
            $dokter_info = [
                'nama' => $dokter->nama,
                'spesialisasi' => $dokter->spesialisasi,
                'jam_praktek' => $dokter->jam_mulai . ' - ' . $dokter->jam_selesai,
                'hari_praktek' => $dokter->hari_mulai . ' s/d ' . $dokter->hari_selesai
            ];

            Log::info('Queue number generated successfully', [
                'id_dokter' => $id_dokter,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role,
                'no_antrian' => $no_antrian,
                'queue_position' => $count + 1
            ]);

            return response()->json([
                'success' => true,
                'no_antrian' => $no_antrian,
                'current_queue' => $count + 1,
                'dokter' => $dokter_info,
                'tanggal' => $today->format('d M Y'),
                'message' => 'Nomor antrian berhasil dibuat',
                'debug_info' => [
                    'user_role' => Auth::user()->role,
                    'user_id' => Auth::id(),
                    'count' => $count,
                    'today' => $today->format('Y-m-d H:i:s'),
                    'doctor_found' => true
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getQueueNumber', [
                'id_dokter' => $id_dokter,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role ?? 'unknown',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan server: ' . $e->getMessage(),
                'debug_info' => [
                    'user_role' => Auth::user()->role ?? 'unknown',
                    'user_id' => Auth::id(),
                    'error_location' => $e->getFile() . ':' . $e->getLine()
                ]
            ], 500);
        }
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

            Log::info('Registration status updated', [
                'registration_id' => $id,
                'old_status' => $pendaftaran->getOriginal('status'),
                'new_status' => $request->status,
                'updated_by' => Auth::id()
            ]);

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

            Log::info('Registration deleted', [
                'registration_id' => $id,
                'no_antrian' => $pendaftaran->no_antrian,
                'deleted_by' => Auth::id()
            ]);

            $pendaftaran->delete();

            return response()->json(['success' => 'Pendaftaran berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Delete error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}