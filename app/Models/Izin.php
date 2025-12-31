<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Presensi;

class Izin extends Model
{
    protected $table = 'izin';
    
    protected $fillable = [
        'wali_id', 'siswa_id', 
        'tanggal_mulai', 'tanggal_selesai', 'keterangan',
        'alasan', 'foto_bukti', 'status', 'mapel' // ✅ TAMBAH MAPEL
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // ✅ OBSERVER: AUTO RESET MAPEL SAAT STATUS BERUBAH
    protected static function booted()
    {
        static::updating(function ($izin) {
            // Saat status bukan pending → reset mapel
            if ($izin->isDirty('status') && $izin->status !== 'pending') {
                $izin->mapel = null;
                
                // Update presensi terkait jika ada
                if ($izin->presensi_id) {
                    Presensi::where('id', $izin->presensi_id)
                        ->update(['mapel' => null]);
                }
            }
        });
    }

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke presensi
    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'presensi_id');
    }
}