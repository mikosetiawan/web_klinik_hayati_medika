<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use Illuminate\Http\Request;

class ListPendaftaranController extends Controller
{
    //
    public function index()
    {
        $pasien = PendaftaranPasien::all();
        return view('pages.pendaftaran-pasien.list-pendaftaran', compact('pasien'));
    }
}
