<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    
    protected $fillable = [
        'nis', 'nama', 'kelas', 'no_telepon', 'alamat', 'wali_id'
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