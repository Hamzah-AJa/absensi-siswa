<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi'; // ✅ EXPLISIT TABEL NAME
    
    protected $fillable = [
        'guru_id', 'siswa_id', 'kelas', 'mapel', 'tanggal', 'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function izin()
    {
        return $this->hasOne(Izin::class, 'presensi_id');
    }
}