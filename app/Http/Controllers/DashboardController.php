<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Izin;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isWali()) {
            return redirect()->route('wali.dashboard');
        }

        // Filter untuk admin atau guru
        $query = Presensi::with(['siswa', 'guru']);
        
        // Jika guru, hanya tampilkan presensi miliknya
        if ($user->isGuru()) {
            $query->where('guru_id', $user->id);
        }

        // Filter hari ini
        $hariIni = Carbon::today();
        $presensiHariIni = (clone $query)->whereDate('tanggal', $hariIni);

        // Hitung statistik hari ini
        $hadir = (clone $presensiHariIni)->where('keterangan', 'hadir')->count();
        $izin = (clone $presensiHariIni)->where('keterangan', 'izin')->count();
        $sakit = (clone $presensiHariIni)->where('keterangan', 'sakit')->count();
        $alpa = (clone $presensiHariIni)->where('keterangan', 'alpa')->count();

        // Filter kelas dan mapel jika ada
        if ($request->filled('kelas')) {
            $presensiHariIni->where('kelas', $request->kelas);
        }
        if ($request->filled('mapel')) {
            $presensiHariIni->where('mapel', $request->mapel);
        }

        // Data presensi hari ini dengan pagination
        $dataHariIni = $presensiHariIni->latest()->paginate(10, ['*'], 'hari_ini');

        // Presensi seminggu (Senin s/d hari ini)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Reset counter jika hari Senin
        if (Carbon::now()->isMonday()) {
            $startOfWeek = Carbon::today();
        }

        $presensiMingguIni = (clone $query)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);

        if ($request->filled('kelas')) {
            $presensiMingguIni->where('kelas', $request->kelas);
        }
        if ($request->filled('mapel')) {
            $presensiMingguIni->where('mapel', $request->mapel);
        }

        $dataMingguIni = $presensiMingguIni->latest()->paginate(10, ['*'], 'minggu');

        // Data untuk filter dropdown
        $kelasList = Presensi::distinct()->pluck('kelas');
        $mapelList = Presensi::distinct()->pluck('mapel');

        return view('dashboard.index', compact(
            'hadir', 'izin', 'sakit', 'alpa',
            'dataHariIni', 'dataMingguIni',
            'kelasList', 'mapelList'
        ));

        // IZIN PENDING - TAMBAH INI
    $izinPending = Izin::where('status', 'pending')
        ->with('siswa')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    return view('dashboard', compact(
        'hadir', 'izin', 'sakit', 'alpa',
        'kelasList', 'mapelList',
        'dataHariIni', 'dataMingguIni',
        'izinPending'  // ← TAMBAH INI
    ));
    }
}