<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mapel',
        'google_id',
        'izin_tanpa_foto',
        'profile_photo'
    ];


    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function izin()
    {
        return $this->hasMany(Izin::class);
    }
}
