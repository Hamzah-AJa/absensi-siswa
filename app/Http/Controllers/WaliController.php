<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Izin;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WaliController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Semua siswa milik wali
        $siswa = $user->siswa()->with(['presensi' => function ($q) {
            $q->latest()->limit(5);
        }])->get();

        return view('wali.dashboard', compact('siswa'));
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
            'alasan' => 'required|string',
            'tanggal' => 'required|date',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek apakah siswa milik wali ini
        $siswa = Siswa::where('id', $request->siswa_id)
            ->where('wali_id', $user->id)
            ->first();

        if (!$siswa) {
            return back()->withErrors(['siswa_id' => 'Siswa tidak valid']);
        }

        // Logika kuota foto
        if (!$request->hasFile('foto_bukti')) {
            if ($user->izin_tanpa_foto >= 2) {
                return back()->withErrors(['foto_bukti' => 'Anda telah mencapai batas 2x pengajuan tanpa foto. Mohon lampirkan foto bukti.']);
            }

            // Tambah counter
            $user->increment('izin_tanpa_foto');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('izin', 'public');
        }

        Izin::create([
            'wali_id' => $user->id,
            'siswa_id' => $request->siswa_id,
            'alasan' => $request->alasan,
            'tanggal' => $request->tanggal,
            'foto_bukti' => $fotoPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Izin berhasil diajukan!');
    }

    public function riwayatIzin()
    {
        $user = Auth::user();
        $izin = Izin::where('wali_id', $user->id)
            ->with('siswa')
            ->latest()
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
