<?php

namespace App\Http\Controllers;

use App\Models\JadwalPraktik;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KelolaJadwalPraktikController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPraktik::with('dokter')->get();
        $dokters = Dokter::all();
        return view('pages.jadwal_praktik.index', compact('jadwals', 'dokters'));
    }

    public function store(Request $request)
    {
        // Normalize time inputs to HH:MM
        $jam_mulai = date('H:i', strtotime($request->jam_mulai));
        $jam_selesai = date('H:i', strtotime($request->jam_selesai));

        $request->merge([
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
        ]);

        $request->validate([
            'id_dokter' => 'required|exists:dokters,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'jam_mulai.date_format' => 'Format jam mulai harus HH:MM (contoh: 09:30).',
            'jam_selesai.date_format' => 'Format jam selesai harus HH:MM (contoh: 09:30).',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        if (JadwalPraktik::where('id_dokter', $request->id_dokter)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })->exists()) {
            return redirect()->route('kelola-jadwal-praktik.index')->with('error', 'Jadwal dokter pada hari dan jam tersebut sudah ada.');
        }

        JadwalPraktik::create([
            'id_dokter' => $request->id_dokter,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('kelola-jadwal-praktik.index')->with('success', 'Jadwal praktik berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Log incoming request data for debugging
        Log::info('Update Jadwal Request:', $request->all());

        // Normalize time inputs to HH:MM
        $jam_mulai = date('H:i', strtotime($request->jam_mulai));
        $jam_selesai = date('H:i', strtotime($request->jam_selesai));

        $request->merge([
            'jam_mulai' => $jam_mulai,
            'jam_selesai' => $jam_selesai,
        ]);

        $request->validate([
            'id_dokter' => 'required|exists:dokters,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ], [
            'jam_mulai.date_format' => 'Format jam mulai harus HH:MM (contoh: 09:30).',
            'jam_selesai.date_format' => 'Format jam selesai harus HH:MM (contoh: 09:30).',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $jadwal = JadwalPraktik::findOrFail($id);

        if (JadwalPraktik::where('id_dokter', $request->id_dokter)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })->exists()) {
            return redirect()->route('kelola-jadwal-praktik.index')->with('error', 'Jadwal dokter pada hari dan jam tersebut sudah ada.');
        }

        $jadwal->update([
            'id_dokter' => $request->id_dokter,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('kelola-jadwal-praktik.index')->with('success', 'Jadwal praktik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPraktik::findOrFail($id);
        $jadwal->delete();

        return response()->json(['success' => 'Jadwal praktik berhasil dihapus.']);
    }
}