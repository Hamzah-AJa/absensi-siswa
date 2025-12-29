<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nama',      // ← BENAR untuk siswa
        'kelas',     // ← BENAR untuk siswa
        'wali_id',   // ← BENAR untuk siswa
        // HAPUS SEMUA: name, email, password, role, dll - itu untuk User!
    ];

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'siswa_id');  // ← tambah siswa_id
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'siswa_id');      // ← tambah siswa_id
    }
}