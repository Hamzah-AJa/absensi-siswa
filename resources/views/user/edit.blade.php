@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Edit User</h2>
        <p class="text-muted">Ubah data {{ $user->name }}</p>
    </div>
    <a href="{{ route('user.manage') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" required>
                                <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="wali" {{ old('role', $user->role) == 'wali' ? 'selected' : '' }}>Wali Murid</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6" id="mapelContainer" style="{{ $user->role == 'guru' ? 'display:block' : 'display:none' }}">
                            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('mapel') is-invalid @enderror" name="mapel" id="mapelField">
                                <option value="">-- Pilih Mapel --</option>
                                <option value="Pemrograman Berorientasi Objek" {{ old('mapel', $user->mapel) == 'Pemrograman Berorientasi Objek' ? 'selected' : '' }}>Pemrograman Berorientasi Objek</option>
                                <option value="Basis Data" {{ old('mapel', $user->mapel) == 'Basis Data' ? 'selected' : '' }}>Basis Data</option>
                                <option value="Matematika" {{ old('mapel', $user->mapel) == 'Matematika' ? 'selected' : '' }}>Matematika</option>
                                <option value="Pendidikan Agama & Budi Pekerti" {{ old('mapel', $user->mapel) == 'Pendidikan Agama & Budi Pekerti' ? 'selected' : '' }}>Pendidikan Agama & Budi Pekerti</option>
                                <option value="Pemrograman Web" {{ old('mapel', $user->mapel) == 'Pemrograman Web' ? 'selected' : '' }}>Pemrograman Web</option>
                                <option value="Pendidikan Kewarganegaraan" {{ old('mapel', $user->mapel) == 'Pendidikan Kewarganegaraan' ? 'selected' : '' }}>Pendidikan Kewarganegaraan</option>
                                <option value="Bahasa Jepang" {{ old('mapel', $user->mapel) == 'Bahasa Jepang' ? 'selected' : '' }}>Bahasa Jepang</option>
                                <option value="Bahasa Inggris" {{ old('mapel', $user->mapel) == 'Bahasa Inggris' ? 'selected' : '' }}>Bahasa Inggris</option>
                                <option value="Bahasa Indonesia" {{ old('mapel', $user->mapel) == 'Bahasa Indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="Multimedia" {{ old('mapel', $user->mapel) == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
                                <option value="KIK" {{ old('mapel', $user->mapel) == 'KIK' ? 'selected' : '' }}>KIK</option>
                            </select>
                            @error('mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Password Baru (kosongkan jika tidak ingin diubah)</label>
                            <input type="password" class="form-control" name="password" placeholder="Kosongkan untuk tidak ubah">
                            <div class="form-text">Minimal 8 karakter jika ingin diubah</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('user.manage') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const mapelContainer = document.getElementById('mapelContainer');
    const mapelField = document.getElementById('mapelField');
    
    if (this.value === 'guru') {
        mapelContainer.style.display = 'block';
        mapelField.required = true;
    } else {
        mapelContainer.style.display = 'none';
        mapelField.required = false;
        mapelField.value = '';
    }
});
</script>
@endsection