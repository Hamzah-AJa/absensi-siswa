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
                                    <span class="me-2">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->isoFormat('D MMMM') }}
                                        @if($p->mapel && $p->keterangan == 'hadir')
                                            - {{ $p->mapel }}
                                        @endif
                                    </span>
                                    @if($p->keterangan == 'hadir')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Hadir
                                        </span>
                                    @elseif($p->keterangan == 'izin')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-envelope-paper me-1"></i>Izin
                                        </span>
                                    @elseif($p->keterangan == 'sakit')
                                        <span class="badge bg-info">
                                            <i class="bi bi-heart-pulse me-1"></i>Sakit
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Alpa
                                        </span>
                                    @endif
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
<<<<<<< HEAD
            </div>
        @endforeach
    </div>
@endif
@endsection
=======
            @endforeach
        </div>
    @endif
@endsection
>>>>>>> 0735a598a38dbb95bd8f7970b9f904a005197915
