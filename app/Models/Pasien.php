<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pasien extends Model
{
   protected $fillable = [
        'user_id',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
    ];

    /**
     * Relasi ke tabel users.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke PendaftaranPasien (one-to-many)
    public function pendaftaranPasiens()
    {
        return $this->hasMany(PendaftaranPasien::class, 'id_pasien');
    }
}