<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $fillable = ['id_user', 'nama', 'spesialisasi', 'no_telepon', 'jam_mulai', 'jam_selesai', 'hari_mulai', 'hari_selesai'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}