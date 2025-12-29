@extends('layouts.wali')

@section('title', 'Riwayat Izin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Riwayat Izin</h2>
        <p class="text-muted">Lihat semua pengajuan izin Anda</p>
    </div>
    <a href="{{ route('wali.izin') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Ajukan Izin Baru
    </a>
</div>

<!-- Tabel Riwayat Izin -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
    <tr>
        <th>Tanggal Pengajuan</th>
        <th>Siswa</th>
        <th>Keterangan</th>  <!-- ✅ TAMBAH -->
        <th>Tanggal Izin</th>
        <th>Alasan</th>
        <th>Foto Bukti</th>
        <th>Status</th>
    </tr>
</thead>
               <tbody>
    @forelse($izin as $i)
    <tr>
        <td>{{ $i->created_at->format('d/m/Y') }}</td>
        <td>
            <strong>{{ $i->siswa->nama }}</strong><br>
            <small class="text-muted">{{ $i->siswa->kelas }}</small>
        </td>
        <td>
    @if($i->keterangan == 'sakit')
        <span class="badge bg-danger">Sakit</span>
    @else
        <span class="badge bg-warning">Izin</span>
    @endif
</td>
        <td>  <!-- ✅ PERIODE -->
            {{ $i->tanggal_mulai ?? '-' }} 
            @if($i->tanggal_selesai && $i->tanggal_selesai != $i->tanggal_mulai)
                <br><small class="text-muted">s/d {{ $i->tanggal_selesai ?? '-' }}</small>
            @endif
        </td>
        <td>{{ Str::limit($i->alasan, 50) }}</td>  <!-- ✅ ALASAN -->
        <td>  <!-- ✅ FOTO -->
            @if($i->foto_bukti)
                <a href="{{ asset('storage/' . $i->foto_bukti) }}" target="_blank" class="btn btn-sm btn-info">
                    <i class="bi bi-image"></i> Lihat
                </a>
            @else
                <span class="text-muted">Tidak ada</span>
            @endif
        </td>
        <td>  <!-- ✅ STATUS -->
            @if($i->status == 'pending')
                <span class="badge bg-warning"><i class="bi bi-clock"></i> Menunggu</span>
            @elseif($i->status == 'approved')
                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Disetujui</span>
            @else
                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
            @endif
        </td>
    </tr>
    @empty
<tr>
    <td colspan="7" class="text-center py-4 text-muted">  <!-- ← PINDAH KE SINI -->
        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
        <p class="mt-2">Belum ada riwayat izin</p>
        <a href="{{ route('wali.izin') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajukan Izin Pertama
        </a>
    </td>
</tr>
    @endforelse
</tbody>
            </table>
        </div>
        {{ $izin->links() }}
    </div>
</div>
@endsection