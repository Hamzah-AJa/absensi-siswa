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
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                   name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" 
                                   placeholder="Contoh: 081234567890">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format: 08xxxxxxxxxx</div>
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

                        <div class="col-md-12" id="mapelContainer" style="{{ $user->role == 'guru' ? 'display:block' : 'display:none' }}">
                            <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select @error('mapel') is-invalid @enderror" name="mapel" id="mapelField">
                                <option value="">-- Pilih Mapel --</option>
                                <option value="Pemrograman Berorientasi Objek" {{ old('mapel', is_array($user->mapel) ? (in_array('Pemrograman Berorientasi Objek', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Pemrograman Berorientasi Objek' ? 'selected' : '')) }}>Pemrograman Berorientasi Objek</option>
                                <option value="Basis Data" {{ old('mapel', is_array($user->mapel) ? (in_array('Basis Data', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Basis Data' ? 'selected' : '')) }}>Basis Data</option>
                                <option value="Matematika" {{ old('mapel', is_array($user->mapel) ? (in_array('Matematika', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Matematika' ? 'selected' : '')) }}>Matematika</option>
                                <option value="Pendidikan Agama & Budi Pekerti" {{ old('mapel', is_array($user->mapel) ? (in_array('Pendidikan Agama & Budi Pekerti', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Pendidikan Agama & Budi Pekerti' ? 'selected' : '')) }}>Pendidikan Agama & Budi Pekerti</option>
                                <option value="Pemrograman Web" {{ old('mapel', is_array($user->mapel) ? (in_array('Pemrograman Web', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Pemrograman Web' ? 'selected' : '')) }}>Pemrograman Web</option>
                                <option value="Pendidikan Kewarganegaraan" {{ old('mapel', is_array($user->mapel) ? (in_array('Pendidikan Kewarganegaraan', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Pendidikan Kewarganegaraan' ? 'selected' : '')) }}>Pendidikan Kewarganegaraan</option>
                                <option value="Bahasa Jepang" {{ old('mapel', is_array($user->mapel) ? (in_array('Bahasa Jepang', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Bahasa Jepang' ? 'selected' : '')) }}>Bahasa Jepang</option>
                                <option value="Bahasa Inggris" {{ old('mapel', is_array($user->mapel) ? (in_array('Bahasa Inggris', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Bahasa Inggris' ? 'selected' : '')) }}>Bahasa Inggris</option>
                                <option value="Bahasa Indonesia" {{ old('mapel', is_array($user->mapel) ? (in_array('Bahasa Indonesia', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Bahasa Indonesia' ? 'selected' : '')) }}>Bahasa Indonesia</option>
                                <option value="Multimedia" {{ old('mapel', is_array($user->mapel) ? (in_array('Multimedia', $user->mapel) ? 'selected' : '') : ($user->mapel == 'Multimedia' ? 'selected' : '')) }}>Multimedia</option>
                                <option value="KIK" {{ old('mapel', is_array($user->mapel) ? (in_array('KIK', $user->mapel) ? 'selected' : '') : ($user->mapel == 'KIK' ? 'selected' : '')) }}>KIK</option>
                            </select>
                            @error('mapel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pilih mata pelajaran yang diampu</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Password Baru (kosongkan jika tidak ingin diubah)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" placeholder="Kosongkan untuk tidak ubah">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const mapelContainer = document.getElementById('mapelContainer');
    const mapelField = document.getElementById('mapelField');
    
    roleSelect.addEventListener('change', function() {
        if (this.value === 'guru') {
            mapelContainer.style.display = 'block';
            mapelField.required = true;
        } else {
            mapelContainer.style.display = 'none';
            mapelField.required = false;
            mapelField.value = '';
        }
    });
});
</script>
@endpush