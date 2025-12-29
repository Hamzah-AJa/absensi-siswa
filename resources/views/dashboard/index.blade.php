@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Dashboard</h2>
        <p class="text-muted">Selamat Datang, {{ Auth::user()->name }}</p>
    </div>
    <div>
        <span class="text-muted">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</span>
    </div>
</div>

<!-- Statistik Hari Ini -->
<h5 class="mb-3">Presensi Siswa Hari Ini</h5>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card hadir">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">HADIR</h6>
                        <h2 class="mb-0">{{ $hadir }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card izin">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">IZIN</h6>
                        <h2 class="mb-0">{{ $izin }}</h2>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-envelope-paper" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card sakit">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">SAKIT</h6>
                        <h2 class="mb-0">{{ $sakit }}</h2>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-heart-pulse" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card alpa">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">ALPA</h6>
                        <h2 class="mb-0">{{ $alpa }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter Kelas</label>
                <select name="kelas" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter Mapel</label>
                <select name="mapel" class="form-select">
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel }}" {{ request('mapel') == $mapel ? 'selected' : '' }}>
                            {{ $mapel }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Presensi Hari Ini -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Presensi Hari Ini</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataHariIni as $presensi)
                    <tr>
                        <td>{{ $presensi->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $presensi->siswa->nama }}</td>
                        <td>{{ $presensi->kelas }}</td>
                        <td>{{ $presensi->mapel }}</td>
                        <td>
                            @if($presensi->keterangan == 'hadir')
                                <span class="badge bg-success">Hadir</span>
                            @elseif($presensi->keterangan == 'izin')
                                <span class="badge bg-warning">Izin</span>
                            @elseif($presensi->keterangan == 'sakit')
                                <span class="badge bg-info">Sakit</span>
                            @else
                                <span class="badge bg-danger">Alpa</span>
                            @endif
                        </td>
                        <td>
                            @if(Auth::user()->isAdmin() || $presensi->guru_id == Auth::id())
                                <a href="{{ route('presensi.edit', $presensi->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('presensi.destroy', $presensi->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2">Belum ada data presensi hari ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $dataHariIni->appends(request()->query())->links() }}
    </div>
</div>

<!-- Tabel Izin Pending - SELALU TAMPIL -->
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-envelope-paper-heart text-warning me-2"></i>
            Izin Pending
        </h5>
    </div>
    <div class="card-body">
        @php
            $izinPending = \App\Models\Izin::where('status', 'pending')
                ->with('siswa')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        @endphp
        
        @if($izinPending->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th>Alasan</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($izinPending as $izin)
                        <tr>
                            <td><strong>{{ $izin->siswa->nama }}</strong></td>
                            <td>{{ $izin->siswa->kelas }}</td>
                            <td>
                                @if($izin->keterangan == 'sakit')
                                    <span class="badge bg-danger">Sakit</span>
                                @else
                                    <span class="badge bg-warning text-dark">Izin</span>
                                @endif
                            </td>
                            <td>
                                {{ $izin->tanggal_mulai->format('d/m/Y') }}
                                @if($izin->tanggal_mulai->ne($izin->tanggal_selesai))
                                    <br><small class="text-muted">s/d {{ $izin->tanggal_selesai->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td>{{ Str::limit($izin->alasan, 40) }}</td>
                            <td>
                                @if($izin->foto_bukti)
                                    <a href="{{ asset('storage/' . $izin->foto_bukti) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('izin.konfirmasi', $izin->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            onclick="return confirm('Konfirmasi izin {{ $izin->siswa->nama }}?')">
                                        <i class="bi bi-check-lg"></i> Konfirmasi
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                <p class="mt-3 text-muted">Tidak ada izin pending</p>
            </div>
        @endif
    </div>
</div>

<!-- Tabel Presensi Minggu Ini -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Presensi Total Selama 1 Minggu</h5>
        <small class="text-muted">Data akan direset setiap hari Senin</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dataMingguIni as $presensi)
                    <tr>
                        <td>{{ $presensi->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $presensi->siswa->nama }}</td>
                        <td>{{ $presensi->kelas }}</td>
                        <td>{{ $presensi->mapel }}</td>
                        <td>
                            @if($presensi->keterangan == 'hadir')
                                <span class="badge bg-success">Hadir</span>
                            @elseif($presensi->keterangan == 'izin')
                                <span class="badge bg-warning">Izin</span>
                            @elseif($presensi->keterangan == 'sakit')
                                <span class="badge bg-info">Sakit</span>
                            @else
                                <span class="badge bg-danger">Alpa</span>
                            @endif
                        </td>
                        <td>
                            @if(Auth::user()->isAdmin() || $presensi->guru_id == Auth::id())
                                <a href="{{ route('presensi.edit', $presensi->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('presensi.destroy', $presensi->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2">Belum ada data presensi minggu ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $dataMingguIni->appends(request()->query())->links() }}
    </div>
</div>
@endsection