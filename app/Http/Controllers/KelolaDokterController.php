<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;

class KelolaDokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::with('user')->get();
        $users = User::where('role', 'dokter')->get();
        return view('pages.dokter.index', compact('dokters', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
        ]);

        if (Dokter::where('id_user', $request->id_user)->exists()) {
            return redirect()->route('kelola-dokter.index')->with('error', 'Dokter dengan ID User ini sudah terdaftar.');
        }

        Dokter::create([
            'id_user' => $request->id_user,
            'nama' => $request->nama,
            'spesialisasi' => $request->spesialisasi,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('kelola-dokter.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
        ]);

        $dokter = Dokter::findOrFail($id);

        if (Dokter::where('id_user', $request->id_user)->where('id', '!=', $id)->exists()) {
            return redirect()->route('kelola-dokter.index')->with('error', 'Dokter dengan ID User ini sudah terdaftar.');
        }

        $dokter->update([
            'id_user' => $request->id_user,
            'nama' => $request->nama,
            'spesialisasi' => $request->spesialisasi,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('kelola-dokter.index')->with('success', 'Dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        $dokter->delete();

        return response()->json(['success' => 'Dokter berhasil dihapus.']);
    }
}