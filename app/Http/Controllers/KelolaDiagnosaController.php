<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use Illuminate\Http\Request;

class KelolaDiagnosaController extends Controller
{
    public function index()
    {
        $diagnosas = Diagnosa::all();
        return view('pages.diagnosa.index', compact('diagnosas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosas,kode_diagnosa',
            'nama_diagnosa' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
        ]);

        Diagnosa::create([
            'kode_diagnosa' => $request->kode_diagnosa,
            'nama_diagnosa' => $request->nama_diagnosa,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
        ]);

        return redirect()->route('kelola-diagnosa.index')->with('success', 'Diagnosa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_diagnosa' => 'required|string|max:255|unique:diagnosas,kode_diagnosa,' . $id,
            'nama_diagnosa' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
        ]);

        $diagnosa = Diagnosa::findOrFail($id);
        $diagnosa->update([
            'kode_diagnosa' => $request->kode_diagnosa,
            'nama_diagnosa' => $request->nama_diagnosa,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
        ]);

        return redirect()->route('kelola-diagnosa.index')->with('success', 'Diagnosa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $diagnosa = Diagnosa::findOrFail($id);
        $diagnosa->delete();

        return response()->json(['success' => 'Diagnosa berhasil dihapus.']);
    }
}