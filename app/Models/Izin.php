<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    protected $table = 'izin';
    
    protected $fillable = [
        'wali_id', 'siswa_id', 'alasan', 'tanggal', 'foto_bukti', 'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}