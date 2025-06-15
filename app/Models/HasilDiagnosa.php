<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilDiagnosa extends Model
{
    use HasFactory;

    protected $table = 'hasil_diagnosas';
    protected $fillable = [
        'no_rm',
        'dokter_id',
        'id_pendaftaran_pasien',
        'catatan',
        'tanggal_diagnosa',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function pendaftaranPasien()
    {
        return $this->belongsTo(PendaftaranPasien::class, 'id_pendaftaran_pasien');
    }

    public function diagnosas()
    {
        return $this->belongsToMany(Diagnosa::class, 'hasil_diagnosa_diagnosa', 'hasil_diagnosa_id', 'diagnosa_id');
    }

    public function obats()
    {
        return $this->belongsToMany(Obat::class, 'hasil_diagnosa_obat', 'hasil_diagnosa_id', 'obat_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'hasil_diagnosa_id');
    }
}