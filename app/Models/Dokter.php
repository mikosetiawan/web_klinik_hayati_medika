<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $fillable = ['id_user', 'nama', 'spesialisasi', 'no_telepon'];

    public function jadwalPraktiks()
    {
        return $this->hasMany(JadwalPraktik::class, 'id_dokter');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}