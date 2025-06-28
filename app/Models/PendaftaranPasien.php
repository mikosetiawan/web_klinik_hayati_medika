<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPasien extends Model
{
    protected $fillable = ['no_antrian', 'id_dokter', 'id_pasien', 'tanggal_pendaftaran', 'status'];

    // Relasi ke Pasien (many-to-one)
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    // Relasi ke Dokter (many-to-one)
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    // Relasi ke HasilDiagnosa (one-to-one)
    public function hasilDiagnosa()
    {
        return $this->hasOne(HasilDiagnosa::class, 'id_pendaftaran_pasien');
    }
}