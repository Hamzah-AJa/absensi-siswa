<?php

namespace App\Http\Controllers;

use App\Models\Siswa;     // ← siswa (tabel siswa)
use App\Models\Izin;      // ← izin (tabel izin)  
use App\Models\Presensi;  // ← presensi (sesuaikan kalau ada)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class WaliController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $siswa = Siswa::where('wali_id', $user->id)->get();
        
        $siswaIds = $siswa->pluck('id');
        $presensi = Presensi::whereIn('siswa_id', $siswaIds)
            ->with('siswa')
            ->latest()
            ->paginate(15);

        return view('wali.dashboard', compact('siswa', 'presensi'));
    }

    public function izinForm()
    {
        $user = Auth::user();
        $siswa = Siswa::where('wali_id', $user->id)->get();

        return view('wali.izin', compact('siswa'));
    }

    public function submitIzin(Request $request)
{
    $user = Auth::user();

    $request->validate([
    'siswa_id' => 'required|exists:siswa,id',
    'keterangan' => 'required|in:izin,sakit',  // ✅ TAMBAH
    'tanggal_mulai' => 'required|date',
    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    'alasan' => 'required|string|max:1000',
    'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
]);
        // Cek apakah siswa milik wali ini
        $siswa = Siswa::where('id', $request->siswa_id)
            ->where('wali_id', $user->id)
            ->first();

        if (!$siswa) {
            return back()->withErrors(['siswa_id' => 'Siswa tidak valid']);
        }

        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('izin', 'public');
        }

        // SESUAI MIGRASI: wali_id, siswa_id, alasan, tanggal, foto_bukti
        Izin::create([
    'wali_id' => $user->id,
    'siswa_id' => $request->siswa_id,
    'keterangan' => $request->keterangan,  // ✅ TAMBAH
    'tanggal_mulai' => $request->tanggal_mulai,
    'tanggal_selesai' => $request->tanggal_selesai,
    'alasan' => $request->alasan,
    'foto_bukti' => $fotoPath,
    'status' => 'pending',
]);

        return back()->with('success', 'Izin berhasil diajukan!');
    }

    public function riwayatIzin()
{
    $izin = Izin::where('siswa_id', Auth::user()->wali_siswa_id)  // ← UBAH INI SAJA
                ->orderBy('created_at', 'desc')
                ->paginate(10);

    return view('wali.riwayat-izin', compact('izin'));
}


    public function profile()
    {
        $user = Auth::user();
        return view('wali.profile', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}