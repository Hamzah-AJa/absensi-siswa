<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Izin;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class WaliController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $siswa = Siswa::where('wali_id', $user->id)->get();
        
        // Ambil presensi siswa yang di-wali (7 hari terakhir)
        $siswaIds = $siswa->pluck('id');
        $presensi = Presensi::whereIn('siswa_id', $siswaIds)
            ->where('tanggal', '>=', Carbon::now()->subDays(7))
            ->with('siswa')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Hitung statistik per siswa
        $statistik = [];
        foreach ($siswa as $s) {
            $statistik[$s->id] = [
                'nama' => $s->nama,
                'kelas' => $s->kelas,
                'hadir' => $presensi->where('siswa_id', $s->id)->where('keterangan', 'hadir')->count(),
                'izin' => $presensi->where('siswa_id', $s->id)->where('keterangan', 'izin')->count(),
                'sakit' => $presensi->where('siswa_id', $s->id)->where('keterangan', 'sakit')->count(),
                'alpa' => $presensi->where('siswa_id', $s->id)->where('keterangan', 'alpa')->count(),
            ];
        }

        return view('wali.dashboard', compact('siswa', 'presensi', 'statistik'));
    }

    public function izinForm()
    {
        $user = Auth::user();
        $siswa = Siswa::where('wali_id', $user->id)->get();

        // Reset counter setiap hari Senin
        if (Carbon::now()->isMonday() && $user->izin_tanpa_foto > 0) {
            $user->update(['izin_tanpa_foto' => 0]);
        }

        $sisaKuota = 2 - $user->izin_tanpa_foto;

        return view('wali.izin', compact('siswa', 'sisaKuota'));
    }

    public function submitIzin(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'alasan' => 'required|string',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek apakah siswa milik wali ini
        $siswa = Siswa::where('id', $request->siswa_id)
            ->where('wali_id', $user->id)
            ->first();

        if (!$siswa) {
            return back()->withErrors(['siswa_id' => 'Siswa tidak valid']);
        }

        // Reset counter jika hari Senin
        if (Carbon::now()->isMonday() && $user->izin_tanpa_foto > 0) {
            $user->update(['izin_tanpa_foto' => 0]);
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
        $siswa = Siswa::where('wali_id', $user->id)->get();
        
        return view('wali.profile', compact('user', 'siswa'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'required|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
        ]);

        return back()->with('success', 'Profil berhasil diupdate!');
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