<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IzinController extends Controller
{
        
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function konfirmasi(Request $request, $id)
    {
        $izin = Izin::findOrFail($id);
        
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isGuru()) {
            return back()->with('error', 'Tidak memiliki akses!');
        }
        
        // ✅ AUTO BUAT PRESENSI MAPEL = NULL
        $this->buatPresensiOtomatis($izin, 'izin');
        
        $izin->update([
            'status' => 'approved',
            'mapel' => null // ✅ RESET MAPEL KOSONG
        ]);
        
        return back()->with('success', 'Izin ' . $izin->siswa->nama . ' dikonfirmasi & presensi otomatis dibuat!');
    }

    public function tolak(Request $request, $id)
    {
        $izin = Izin::findOrFail($id);
        
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isGuru()) {
            return back()->with('error', 'Tidak memiliki akses!');
        }
        
        // ✅ AUTO ALPA MAPEL = NULL
        $this->buatPresensiOtomatis($izin, 'alpa');
        
        $izin->update([
            'status' => 'rejected',
            'mapel' => null // ✅ RESET MAPEL KOSONG
        ]);
        
        return back()->with('success', 'Izin ' . $izin->siswa->nama . ' ditolak & presensi alpa otomatis dibuat!');
    }

    private function buatPresensiOtomatis($izin, $keteranganDefault)
    {
        $tanggalMulai = Carbon::parse($izin->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($izin->tanggal_selesai);
        
        $tanggal = $tanggalMulai->copy();
        while ($tanggal->lte($tanggalSelesai)) {
            // Cek apakah sudah ada presensi hari itu
            $existing = Presensi::where('siswa_id', $izin->siswa_id)
                ->whereDate('tanggal', $tanggal->format('Y-m-d'))
                ->first();
                
            if (!$existing) {
                Presensi::create([
                    'siswa_id' => $izin->siswa_id,
                    'guru_id' => Auth::id(),
                    'kelas' => $izin->siswa->kelas,
                    'mapel' => null, // ✅ SELALU NULL UNTUK IZIN
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'keterangan' => $keteranganDefault,
                ]);
            }
            
            $tanggal->addDay();
        }
    }

    
}