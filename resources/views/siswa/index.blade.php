@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Data Siswa</h2>
        <p class="text-muted">Kelola data siswa</p>
    </div>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Siswa
    </a>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('siswa.index') }}" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIS..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="kelas" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search"></i> Cari
                </button>
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Siswa -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $item)
                    <tr>
                        <td>{{ $item->nis }}</td>
                        <td>{{ $item->nama }}</td>
                        <td><span class="badge bg-primary">{{ $item->kelas }}</span></td>
                        <td>{{ $item->no_telepon }}</td>
                        <td>{{ Str::limit($item->alamat, 30) }}</td>
                        <td>
                            <a href="{{ route('siswa.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            @if(Auth::user()->isAdmin())
                            <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Yakin ingin menghapus siswa ini?')">
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
                            <p class="mt-2">Tidak ada data siswa</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $siswa->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection