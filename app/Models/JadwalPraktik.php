<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPraktik extends Model
{
    protected $fillable = ['id_dokter', 'hari', 'jam_mulai', 'jam_selesai'];

    // Relasi ke Dokter (many-to-one)
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    // Relasi ke PendaftaranPasien (one-to-many)
    public function pendaftaranPasiens()
    {
        return $this->hasMany(PendaftaranPasien::class, 'id_jadwal_praktik');
    }
}