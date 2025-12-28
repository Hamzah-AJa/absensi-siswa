@extends('layouts.app')

@section('title', 'Input Presensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Input Presensi</h2>
        <p class="text-muted">Absen siswa disini</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('presensi.store') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select class="form-select @error('kelas') is-invalid @enderror" id="kelas" name="kelas" required>
    <option value="">Pilih Kelas</option>
    @foreach($kelasList as $kelas)
        <option value="{{ $kelas }}" {{ (old('kelas') ?: session('presensi_last_kelas')) == $kelas ? 'selected' : '' }}>
            {{ $kelas }}
        </option>
    @endforeach
</select>
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-6 col-md-6 mb-3"> <!-- UBAH col-md-6 → col-lg-6 col-md-6 -->
    <label for="mapel" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
    <select class="form-select @error('mapel') is-invalid @enderror mapel-select" id="mapel" name="mapel" required>
    <option value="">Pilih Mata Pelajaran</option>
    <option value="Pemrograman Berorientasi Objek" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Pemrograman Berorientasi Objek' ? 'selected' : '' }}>Pemrograman Berorientasi Objek</option>
    <option value="Basis Data" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Basis Data' ? 'selected' : '' }}>Basis Data</option>
    <option value="Matematika" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Matematika' ? 'selected' : '' }}>Matematika</option>
    <option value="Pendidikan Agama & Budi Pekerti" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Pendidikan Agama & Budi Pekerti' ? 'selected' : '' }}>Pend. Agama & Budi Pekerti</option>
    <option value="Pemrograman Web" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Pemrograman Web' ? 'selected' : '' }}>Pemrograman Web</option>
    <option value="Pendidikan Kewarganegaraan" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Pendidikan Kewarganegaraan' ? 'selected' : '' }}>Pendidikan Kewarganegaraan</option>
    <option value="Bahasa Jepang" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Bahasa Jepang' ? 'selected' : '' }}>Bahasa Jepang</option>
    <option value="Bahasa Inggris" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Bahasa Inggris' ? 'selected' : '' }}>Bahasa Inggris</option>
    <option value="Bahasa Indonesia" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Bahasa Indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
    <option value="Multimedia" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
    <option value="KIK" {{ (old('mapel') ?: session('presensi_last_mapel')) == 'KIK' ? 'selected' : '' }}>KIK</option>
</select>
    @error('mapel')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-6 col-md-6 mb-3"> <!-- UBAH JUGA INI -->
    <label for="siswa_id" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
    <select class="form-select @error('siswa_id') is-invalid @enderror" id="siswa_id" name="siswa_id" required>
        <option value="">Pilih Siswa</option>
        @foreach($siswaList as $siswa)
            <option value="{{ $siswa->id }}" data-kelas="{{ $siswa->kelas }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                {{ $siswa->nama }} - {{ $siswa->kelas }}
            </option>
        @endforeach
    </select>
    @error('siswa_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


                <div class="col-md-12 mb-4">
                    <label class="form-label d-block mb-3">Keterangan <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <input type="radio" class="btn-check" name="keterangan" id="hadir" 
                                   value="hadir" {{ old('keterangan') == 'hadir' ? 'checked' : '' }} required>
                            <label class="btn btn-outline-success w-100 py-4" for="hadir">
                                <i class="bi bi-check-circle-fill d-block mb-2" style="font-size: 2.5rem;"></i>
                                <h5 class="mb-0">HADIR</h5>
                            </label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="radio" class="btn-check" name="keterangan" id="izin" 
                                   value="izin" {{ old('keterangan') == 'izin' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning w-100 py-4" for="izin">
                                <i class="bi bi-envelope-paper-fill d-block mb-2" style="font-size: 2.5rem;"></i>
                                <h5 class="mb-0">IZIN</h5>
                            </label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="radio" class="btn-check" name="keterangan" id="sakit" 
                                   value="sakit" {{ old('keterangan') == 'sakit' ? 'checked' : '' }}>
                            <label class="btn btn-outline-info w-100 py-4" for="sakit">
                                <i class="bi bi-heart-pulse-fill d-block mb-2" style="font-size: 2.5rem;"></i>
                                <h5 class="mb-0">SAKIT</h5>
                            </label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="radio" class="btn-check" name="keterangan" id="alpa" 
                                   value="alpa" {{ old('keterangan') == 'alpa' ? 'checked' : '' }}>
                            <label class="btn btn-outline-danger w-100 py-4" for="alpa">
                                <i class="bi bi-x-circle-fill d-block mb-2" style="font-size: 2.5rem;"></i>
                                <h5 class="mb-0">ALPA</h5>
                            </label>
                        </div>
                    </div>
                    @error('keterangan')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Submit
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@endsection

@push('scripts')
<script>
    // Filter siswa berdasarkan kelas
    document.getElementById('kelas').addEventListener('change', function() {
        const selectedKelas = this.value;
        const siswaSelect = document.getElementById('siswa_id');
        const options = siswaSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') return;
            
            const siswaKelas = option.getAttribute('data-kelas');
            
            if (selectedKelas === '' || siswaKelas === selectedKelas) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset pilihan siswa
        siswaSelect.value = '';
    });
</script>
@endpush

@push('styles')
<style>
    .btn-check:checked + .btn-outline-success {
        background-color: #28a745;
        color: white;
    }
    .btn-check:checked + .btn-outline-warning {
        background-color: #ffc107;
        color: white;
    }
    .btn-check:checked + .btn-outline-info {
        background-color: #17a2b8;
        color: white;
    }
    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        color: white;
    }
    .btn-outline-success:hover,
    .btn-outline-warning:hover,
    .btn-outline-info:hover,
    .btn-outline-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    /* FIX MAPEL DROPDOWN */
    .mapel-select {
        font-size: 1rem;
        max-height: 200px;
    }
    .mapel-select option {
        font-size: 1rem;
        padding: 5px 10px;
    }
    
    /* Pastikan 2 kolom sejajar */
    @media (min-width: 992px) {
        .col-lg-6:nth-child(odd) { padding-right: 10px; }
        .col-lg-6:nth-child(even) { padding-left: 10px; }
    }
</style>
@endpush