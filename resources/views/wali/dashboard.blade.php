@extends('layouts.wali')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold mb-2">
                    Welcome, <span class="text-primary">{{ Auth::user()->name }}</span> 👋
                </h1>
                <h4 class="text-muted">
                    <i class="bi bi-people-fill me-2"></i>Data Siswa Anda
                </h4>
            </div>

            @if ($siswa->isEmpty())
                <div class="text-center py-5">
                    <div class="card border-0 bg-light shadow-sm mx-auto" style="max-width: 500px;">
                        <div class="card-body p-5">
                            <i class="bi bi-exclamation-circle text-warning" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 mb-0">Belum ada siswa</h5>
                            <p class="text-muted mb-4">Belum ada siswa yang terhubung dengan akun wali ini.</p>
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-people"></i> Lihat Daftar Siswa
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row g-4 justify-content-center">
                    @foreach ($siswa as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 hover-lift">
                                <div class="card-body p-4 d-flex flex-column">
                                    <!-- Header Siswa -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                            <i class="bi bi-person-fill text-primary" style="font-size: 1.5rem;"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0 fw-bold">{{ $item->nama }}</h5>
                                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                                {{ $item->kelas }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Divider -->
                                    <hr class="my-3">

                                    <!-- Presensi Terakhir -->
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold mb-3 text-uppercase text-muted small">
                                            <i class="bi bi-calendar-check me-1"></i>Presensi Terakhir
                                        </h6>
                                        @forelse ($item->presensi->take(3) as $p)
                                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                <div>
                                                    <div class="fw-medium">
                                                        {{ \Carbon\Carbon::parse($p->tanggal)->isoFormat('D MMMM') }}
                                                        @if($p->mapel && $p->keterangan == 'hadir')
                                                            <span class="text-muted small ms-1">• {{ $p->mapel }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($p->keterangan == 'hadir')
                                                    <span class="badge bg-success px-3 py-2">
                                                        <i class="bi bi-check-circle me-1"></i>Hadir
                                                    </span>
                                                @elseif($p->keterangan == 'izin')
                                                    <span class="badge bg-warning text-dark px-3 py-2">
                                                        <i class="bi bi-envelope-paper me-1"></i>Izin
                                                    </span>
                                                @elseif($p->keterangan == 'sakit')
                                                    <span class="badge bg-info px-3 py-2">
                                                        <i class="bi bi-heart-pulse me-1"></i>Sakit
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger px-3 py-2">
                                                        <i class="bi bi-x-circle me-1"></i>Alpa
                                                    </span>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="text-center py-4">
                                                <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mt-2 mb-0">Belum ada presensi</p>
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Action Button -->
                                    <div class="mt-auto pt-3">
                                        <a href="{{ route('wali.izin') }}" class="btn btn-primary w-100">
                                            <i class="bi bi-envelope-paper me-2"></i>Ajukan Izin
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.border-bottom {
    border-bottom: 1px solid #e9ecef !important;
}
</style>
@endsection