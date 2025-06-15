<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class KelolaObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();
        return view('pages.obat.index', compact('obats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obats,nama_obat',
            'deskripsi' => 'nullable|string',
            'dosis' => 'nullable|string|max:50',
            'harga' => 'required|numeric|min:0',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'dosis' => $request->dosis,
            'harga' => $request->harga,
        ]);

        return redirect()->route('kelola-obat.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:255|unique:obats,nama_obat,' . $id,
            'deskripsi' => 'nullable|string',
            'dosis' => 'nullable|string|max:50',
            'harga' => 'required|numeric|min:0',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama_obat' => $request->nama_obat,
            'deskripsi' => $request->deskripsi,
            'dosis' => $request->dosis,
            'harga' => $request->harga,
        ]);

        return redirect()->route('kelola-obat.index')->with('success', 'Obat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return response()->json(['success' => 'Obat berhasil dihapus.']);
    }
}