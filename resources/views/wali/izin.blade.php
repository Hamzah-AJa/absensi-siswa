@extends('layouts.wali')

@section('title', 'Ajukan Izin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-envelope-paper"></i> Ajukan Izin/Sakit</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('wali.izin.submit') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama }} ({{ $s->kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
    <label class="form-label">Jenis Keterangan <span class="text-danger">*</span></label>
    <select name="keterangan" class="form-select" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="izin" {{ old('keterangan') == 'izin' ? 'selected' : '' }}>Izin</option>
        <option value="sakit" {{ old('keterangan') == 'sakit' ? 'selected' : '' }}>Sakit</option>
    </select>
    @error('keterangan')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>


                    {{-- TANGGAL MULAI & SELASAI --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="tanggal_mulai" 
                                       class="form-control" 
                                       value="{{ old('tanggal_mulai') }}"
                                       required>
                                @error('tanggal_mulai')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="tanggal_selesai" 
                                       class="form-control" 
                                       value="{{ old('tanggal_selesai') }}"
                                       required>
                                @error('tanggal_selesai')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan <span class="text-danger">*</span></label>
                        <textarea name="alasan" 
                                  class="form-control" 
                                  rows="4" 
                                  placeholder="Jelaskan alasan izin/sakit..."
                                  required>{{ old('alasan') }}</textarea>
                        @error('alasan')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Bukti (Opsional)</label>
                        <input type="file" 
                               name="foto_bukti" 
                               class="form-control" 
                               accept="image/*">
                        <div class="form-text">Opsional untuk izin/sakit</div>
                        @error('foto_bukti')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send me-2"></i> Ajukan Izin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection