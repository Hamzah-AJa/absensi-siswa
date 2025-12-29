<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'mapel', 'google_id', 'izin_tanpa_foto, profile_photo'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'guru_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'wali_id');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'wali_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isWali()
    {
        return $this->role === 'wali';
    }

    
}