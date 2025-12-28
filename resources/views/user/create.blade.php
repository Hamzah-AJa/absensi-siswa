@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Tambah User Baru</h2>
        <p class="text-muted">Buat akun guru atau wali murid</p>
    </div>
    <a href="{{ route('user.manage') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form User Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.store') }}">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" required>
                                <option value="">Pilih Role</option>
                                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="wali" {{ old('role') == 'wali' ? 'selected' : '' }}>Wali Murid</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran <span id="mapelLabel" class="text-muted">(Hanya untuk Guru)</span></label>
                            <select class="form-select @error('mapel') is-invalid @enderror" name="mapel" id="mapelField">
                                <option value="">-- Pilih Mapel --</option>
                                <option value="Pemrograman Berorientasi Objek" {{ old('mapel') == 'Pemrograman Berorientasi Objek' ? 'selected' : '' }}>Pemrograman Berorientasi Objek</option>
                                <option value="Basis Data" {{ old('mapel') == 'Basis Data' ? 'selected' : '' }}>Basis Data</option>
                                <option value="Matematika" {{ old('mapel') == 'Matematika' ? 'selected' : '' }}>Matematika</option>
                                <option value="Pendidikan Agama & Budi Pekerti" {{ old('mapel') == 'Pendidikan Agama & Budi Pekerti' ? 'selected' : '' }}>Pendidikan Agama & Budi Pekerti</option>
                                <option value="Pemrograman Web" {{ old('mapel') == 'Pemrograman Web' ? 'selected' : '' }}>Pemrograman Web</option>
                                <option value="Pendidikan Kewarganegaraan" {{ old('mapel') == 'Pendidikan Kewarganegaraan' ? 'selected' : '' }}>Pendidikan Kewarganegaraan</option>
                                <option value="Bahasa Jepang" {{ old('mapel') == 'Bahasa Jepang' ? 'selected' : '' }}>Bahasa Jepang</option>
                                <option value="Bahasa Inggris" {{ old('mapel') == 'Bahasa Inggris' ? 'selected' : '' }}>Bahasa Inggris</option>
                                <option value="Bahasa Indonesia" {{ old('mapel') == 'Bahasa Indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="Multimedia" {{ old('mapel') == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
                                <option value="KIK" {{ old('mapel') == 'KIK' ? 'selected' : '' }}>KIK</option>
                            </select>
                            @error('mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('user.manage') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const mapelField = document.getElementById('mapelField');
    const mapelLabel = document.getElementById('mapelLabel');
    
    if (this.value === 'guru') {
        mapelField.required = true;
        mapelLabel.classList.remove('text-muted');
        mapelLabel.classList.add('text-danger');
        mapelLabel.textContent = '* Mata Pelajaran (Wajib)';
        mapelField.closest('.col-md-6').style.display = 'block';
    } else {
        mapelField.required = false;
        mapelLabel.classList.remove('text-danger');
        mapelLabel.classList.add('text-muted');
        mapelLabel.textContent = '(Hanya untuk Guru)';
        mapelField.value = '';
        mapelField.closest('.col-md-6').style.display = 'none';
    }
});
</script>
@endsection