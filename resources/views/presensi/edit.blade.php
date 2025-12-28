@extends('layouts.app')

@section('title', 'Edit Presensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Edit Presensi</h2>
        <p class="text-muted">Update data presensi siswa</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('presensi.update', $presensi->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" name="tanggal" value="{{ old('tanggal', $presensi->tanggal->format('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select class="form-select @error('kelas') is-invalid @enderror" id="kelas" name="kelas" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ old('kelas', $presensi->kelas) == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
    <label for="mapel" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
    <select class="form-select @error('mapel') is-invalid @enderror" id="mapel" name="mapel" required>
        <option value="">Pilih Mata Pelajaran</option>
        @foreach($mapelList as $mapel)
            <option value="{{ $mapel }}" {{ old('mapel', $presensi->mapel) == $mapel ? 'selected' : '' }}>
                {{ $mapel }}
            </option>
        @endforeach
    </select>
    @error('mapel')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                <div class="col-md-6 mb-3">
                    <label for="siswa_id" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                    <select class="form-select @error('siswa_id') is-invalid @enderror" id="siswa_id" name="siswa_id" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($siswaList as $siswa)
                            <option value="{{ $siswa->id }}" data-kelas="{{ $siswa->kelas }}" {{ old('siswa_id', $presensi->siswa_id) == $siswa->id ? 'selected' : '' }}>
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
                                   value="hadir" {{ old('keterangan', $presensi->keterangan) == 'hadir' ? 'checked' : '' }} required>
                            <label class="btn btn-outline-success w-100 py-4" for="hadir">
                                <i class="bi bi-check-circle-fill d-block mb-2" style="font-size: 2.5rem;"></i>
                                <h5 class="mb-0">HADIR</h5>
                            </label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="radio" class="btn-check" name="keterangan" id="izin" 
                                   value="izin" {{ old('keterangan', $presensi->keterangan) == 'izin' ? 'checked' : '' }}>
                            <label class="btn btn-outline-warning w-100 py-4" for="izin">
                                <i class="bi bi-envelope-paper-fill d-block mb-2" style="font-size: 2.5rem;"></i>
                                <h5 class="mb-0">IZIN</h5>
                            </label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="radio" class="btn-check" name="keterangan" id="sakit" 
                                   value="sakit" {{ old('keterangan', $presensi->keterangan) == 'sakit' ? 'checked' : '' }}>
                            <label class="btn btn-outline-info w-100 py-4" for="sakit">
                                <i class="bi bi-heart-pulse-fill d-block mb-2" style="font-size: 2.5rem;"></i>
                                <h5 class="mb-0">SAKIT</h5>
                            </label>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input type="radio" class="btn-check" name="keterangan" id="alpa" 
                                   value="alpa" {{ old('keterangan', $presensi->keterangan) == 'alpa' ? 'checked' : '' }}>
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
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

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
</style>
@endpush