<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Diagnosa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah pasien (User dengan role 'pasien')
        $jumlahPasien = User::where('role', 'pasien')->count();

        // Hitung jumlah dokter
        $jumlahDokter = Dokter::count();

        // Hitung jumlah obat
        $jumlahObat = Obat::count();

        // Hitung jumlah diagnosa
        $jumlahDiagnosa = Diagnosa::count();

        // Kirim data ke view
        return view('dashboard', compact('jumlahPasien', 'jumlahDokter', 'jumlahObat', 'jumlahDiagnosa'));
    }
}