<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function index()
    {
        $siswaList = Siswa::orderBy('nama')->get();
        $kelasList = Siswa::distinct()->pluck('kelas');
        
        // Ambil daftar mapel dari guru yang ada di database
        $mapelList = User::where('role', 'guru')
            ->whereNotNull('mapel')
            ->distinct()
            ->pluck('mapel');
        
        return view('presensi.index', compact('siswaList', 'kelasList', 'mapelList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas' => 'required|string',
            'mapel' => 'required|string',
            'keterangan' => 'required|in:hadir,izin,sakit,alpa',
            'tanggal' => 'required|date',
        ]);

        Presensi::create([
            'guru_id' => Auth::id(),
            'siswa_id' => $request->siswa_id,
            'tanggal' => $request->tanggal,
            'kelas' => $request->kelas,
            'mapel' => $request->mapel,
            'keterangan' => $request->keterangan,
        ]);

        session(['presensi_last_kelas' => $request->kelas]);
        session(['presensi_last_mapel' => $request->mapel]);

        return redirect()->route('presensi.index')
            ->with('success', 'Presensi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $presensi = Presensi::findOrFail($id);
        
        // Cek authorization
        if (Auth::user()->isGuru() && $presensi->guru_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $siswaList = Siswa::orderBy('nama')->get();
        $kelasList = Siswa::distinct()->pluck('kelas');
        
        // Ambil daftar mapel dari guru yang ada di database
        $mapelList = User::where('role', 'guru')
            ->whereNotNull('mapel')
            ->distinct()
            ->pluck('mapel');

        return view('presensi.edit', compact('presensi', 'siswaList', 'kelasList', 'mapelList'));
    }

    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        
        // Cek authorization
        if (Auth::user()->isGuru() && $presensi->guru_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas' => 'required|string',
            'mapel' => 'required|string',
            'keterangan' => 'required|in:hadir,izin,sakit,alpa',
            'tanggal' => 'required|date',
        ]);

        $presensi->update([
            'siswa_id' => $request->siswa_id,
            'tanggal' => $request->tanggal,
            'kelas' => $request->kelas,
            'mapel' => $request->mapel,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Presensi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        
        // Cek authorization
        if (Auth::user()->isGuru() && $presensi->guru_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $presensi->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Presensi berhasil dihapus!');
    }
}