@extends('layouts.app')

@section('title', 'Laporan Presensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Laporan Presensi</h2>
        <p class="text-muted">Lihat dan export laporan absensi siswa</p>
    </div>
    <button type="button" class="btn btn-success" onclick="exportPDF()">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </button>
</div>

<!-- Filter Laporan -->
<div class="card mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" 
                       value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" 
                       value="{{ request('tanggal_akhir') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
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
                <label class="form-label">Mata Pelajaran</label>
                <select name="mapel" class="form-select">
                    <option value="">Semua Mapel</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel }}" {{ request('mapel') == $mapel ? 'selected' : '' }}>
                            {{ $mapel }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Statistik Total -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card hadir text-center">
            <div class="card-body">
                <h3 class="text-success mb-1">{{ $laporan->sum('hadir') ?? 0 }}</h3>
                <p class="mb-0 text-muted">Total Hadir</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center" style="border-left-color: #ffc107;">
            <div class="card-body">
                <h3 class="text-warning mb-1">{{ $laporan->sum('izin') ?? 0 }}</h3>
                <p class="mb-0 text-muted">Total Izin</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center" style="border-left-color: #17a2b8;">
            <div class="card-body">
                <h3 class="text-info mb-1">{{ $laporan->sum('sakit') ?? 0 }}</h3>
                <p class="mb-0 text-muted">Total Sakit</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center" style="border-left-color: #dc3545;">
            <div class="card-body">
                <h3 class="text-danger mb-1">{{ $laporan->sum('alpa') ?? 0 }}</h3>
                <p class="mb-0 text-muted">Total Alpa</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Detail Laporan per Siswa</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Sakit</th>
                        <th>Alpa</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $data)
                    <tr>
                        <td><strong>{{ $data['nama'] }}</strong></td>
                        <td><span class="badge bg-primary">{{ $data['kelas'] }}</span></td>
                        <td>{{ $data['mapel'] }}</td>
                        <td><span class="badge bg-success">{{ $data['hadir'] }}</span></td>
                        <td><span class="badge bg-warning">{{ $data['izin'] }}</span></td>
                        <td><span class="badge bg-info">{{ $data['sakit'] }}</span></td>
                        <td><span class="badge bg-danger">{{ $data['alpa'] }}</span></td>
                        <td><strong>{{ array_sum(array_slice($data, 3)) }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-file-earmark-text" style="font-size: 3rem;"></i>
                            <p class="mt-2">Tidak ada data laporan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function exportPDF() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }
    window.open('/laporan/pdf?' + params.toString(), '_blank');
}

document.getElementById('filterForm').addEventListener('change', function() {
    const formData = new FormData(this);
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    window.location.href = '/laporan?' + params.toString();
});
</script>
@endsection
