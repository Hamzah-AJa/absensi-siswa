@extends('layouts.wali')

@section('title', 'Dashboard')

@section('content')
    <h3 class="mb-2">
        Welcome, <strong>{{ Auth::user()->name }}</strong> 👋
    </h3>
    <h4 class="mb-4">
        <i class="bi bi-people-fill me-2"></i>Data Siswa
    </h4>

    @if ($siswa->isEmpty())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-circle"></i>
            Belum ada siswa yang terhubung dengan akun wali ini.
        </div>
    @else
        <div class="row">
            @foreach ($siswa as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $item->nama }}</h5>
                            <p class="text-muted mb-2">Kelas {{ $item->kelas }}</p>

                            <hr>

                            <strong>Presensi Terakhir</strong>
                            <ul class="mt-2 mb-3">
                                @forelse ($item->presensi->take(3) as $p)
                                    <li>
                                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                        - <span class="badge bg-secondary">{{ ucfirst($p->keterangan) }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted">Belum ada presensi</li>
                                @endforelse
                            </ul>

                            <a href="{{ route('wali.izin') }}" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-envelope-paper"></i> Ajukan Izin
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
