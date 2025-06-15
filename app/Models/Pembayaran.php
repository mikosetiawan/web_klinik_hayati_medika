<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'hasil_diagnosa_id',
        'total_harga',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
    ];

    public function hasilDiagnosa()
    {
        return $this->belongsTo(HasilDiagnosa::class, 'hasil_diagnosa_id');
    }
}