<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::with(['siswa', 'guru']);
        
        // Jika guru, hanya tampilkan presensi miliknya
        if (Auth::user()->isGuru()) {
            $query->where('guru_id', Auth::id());
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter mapel
        if ($request->filled('mapel')) {
            $query->where('mapel', $request->mapel);
        }

        // Hitung total per siswa
        $presensiData = $query->get();
        
        $laporan = $presensiData->groupBy('siswa_id')->map(function($group) {
            $siswa = $group->first()->siswa;
            return [
                'nama' => $siswa->nama,
                'kelas' => $group->first()->kelas,
                'mapel' => $group->first()->mapel,
                'hadir' => $group->where('keterangan', 'hadir')->count(),
                'izin' => $group->where('keterangan', 'izin')->count(),
                'sakit' => $group->where('keterangan', 'sakit')->count(),
                'alpa' => $group->where('keterangan', 'alpa')->count(),
            ];
        });

        $kelasList = Presensi::distinct()->pluck('kelas');
        $mapelList = Presensi::distinct()->pluck('mapel');

        $mapelList = Presensi::whereNotNull('mapel')
    ->where('mapel', '!=', '')
    ->whereNot(function($query) {
        $query->where('mapel', 'LIKE', '%izin%')
              ->orWhere('mapel', 'LIKE', '%sakit%');
    })
    ->distinct()
    ->pluck('mapel');

        return view('laporan.index', compact('laporan', 'kelasList', 'mapelList'));
    }

    public function exportPDF(Request $request)
{
    $query = Presensi::with(['siswa', 'guru']);
    
    if (Auth::user()->isGuru()) {
        $query->where('guru_id', Auth::id());
    }

    if ($request->filled('tanggal_mulai')) {
        $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
    }
    if ($request->filled('tanggal_akhir')) {
        $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
    }
    if ($request->filled('kelas')) {
        $query->where('kelas', $request->kelas);
    }
    if ($request->filled('mapel')) {
        $query->where('mapel', $request->mapel);
    }

    $presensiData = $query->get();
    
    $laporan = $presensiData->groupBy('siswa_id')->map(function($group) {
        $siswa = $group->first()->siswa;
        return [
            'nama' => $siswa->nama,
            'kelas' => $group->first()->kelas,
            'mapel' => $group->first()->mapel,
            'hadir' => $group->where('keterangan', 'hadir')->count(),
            'izin' => $group->where('keterangan', 'izin')->count(),
            'sakit' => $group->where('keterangan', 'sakit')->count(),
            'alpa' => $group->where('keterangan', 'alpa')->count(),
            'total' => $group->count()
        ];
    });

    // TAMBAH FILTER INFO
    $filterInfo = [
        'kelas' => $request->kelas ?? '-',
        'mapel' => $request->mapel ?? '-',
        'tanggal_mulai' => $request->tanggal_mulai ?? '-',
        'tanggal_akhir' => $request->tanggal_akhir ?? '-'
    ];

    $pdf = PDF::loadView('laporan.pdf', compact('laporan', 'filterInfo'));
    return $pdf->download('laporan-presensi.pdf');
}
}