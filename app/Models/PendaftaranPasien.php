<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPasien extends Model
{
    protected $fillable = ['no_antrian', 'id_jadwal_praktik', 'id_pasien', 'tanggal_pendaftaran', 'status'];

    // Relasi ke JadwalPraktik (many-to-one)
    public function jadwalPraktik()
    {
        return $this->belongsTo(JadwalPraktik::class, 'id_jadwal_praktik');
    }

    // Relasi ke Pasien (many-to-one)
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    // Relasi ke HasilDiagnosa (one-to-one)
    public function hasilDiagnosa()
    {
        return $this->hasOne(HasilDiagnosa::class, 'id_pendaftaran_pasien');
    }

    // Generate unique no_antrian
    public static function generateNoBooking()
    {
        $lastBooking = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastBooking ? (int) substr($lastBooking->no_antrian, 1) : 0;
        return 'P' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}