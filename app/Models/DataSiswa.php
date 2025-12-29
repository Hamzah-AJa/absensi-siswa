<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSiswa extends Model
{
    protected $table = 'datasiswa';  // ← EXPLICIT TABEL NAME
    
    protected $fillable = [
        'nama', 'kelas', 'wali_id', 'nis', 'no_telp_siswa', 'alamat'
    ];

    public function waliUtama()
    {
        return $this->belongsTo(WaliSiswa::class, 'wali_id');
    }
}
