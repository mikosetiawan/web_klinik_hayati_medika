<?php 

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KelolaDokterController extends Controller
{

    public function index()
    {
        $dokters = Dokter::with('user')->get();
        $users = User::where('role', 'dokter')->whereDoesntHave('dokter')->get();
        return view('pages.dokter.index', compact('dokters', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari_mulai' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'hari_selesai' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'hari_mulai.in' => 'Hari mulai harus salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu.',
            'hari_selesai.in' => 'Hari selesai harus salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu.',
        ]);

        if (Dokter::where('id_user', $request->id_user)->exists()) {
            return redirect()->route('kelola-dokter.index')->with('error', 'Dokter dengan ID User ini sudah terdaftar.');
        }

        try {
            Dokter::create([
                'id_user' => $request->id_user,
                'nama' => $request->nama,
                'spesialisasi' => $request->spesialisasi,
                'no_telepon' => $request->no_telepon,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'hari_mulai' => $request->hari_mulai,
                'hari_selesai' => $request->hari_selesai,
            ]);

            return redirect()->route('kelola-dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Store dokter error', ['error' => $e->getMessage()]);
            return redirect()->route('kelola-dokter.index')->with('error', 'Gagal menambahkan dokter: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari_mulai' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'hari_selesai' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'hari_mulai.in' => 'Hari mulai harus salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu.',
            'hari_selesai.in' => 'Hari selesai harus salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu.',
        ]);

        $dokter = Dokter::findOrFail($id);

        if (Dokter::where('id_user', $request->id_user)->where('id', '!=', $id)->exists()) {
            return redirect()->route('kelola-dokter.index')->with('error', 'Dokter dengan ID User ini sudah terdaftar.');
        }

        try {
            $dokter->update([
                'id_user' => $request->id_user,
                'nama' => $request->nama,
                'spesialisasi' => $request->spesialisasi,
                'no_telepon' => $request->no_telepon,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'hari_mulai' => $request->hari_mulai,
                'hari_selesai' => $request->hari_selesai,
            ]);

            return redirect()->route('kelola-dokter.index')->with('success', 'Dokter berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Update dokter error', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('kelola-dokter.index')->with('error', 'Gagal memperbarui dokter: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $dokter = Dokter::findOrFail($id);
            $dokter->delete();

            return response()->json(['success' => 'Dokter berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Delete dokter error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menghapus dokter: ' . $e->getMessage()], 500);
        }
    }
}