@extends('layouts.wali')

@section('title', 'Profil - {{ auth()->user()->name }}')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Banner Profil Baru - DISAMA DENGAN user/profile.blade -->
        <div class="col-12">
            <div class="card shadow-lg border-0 overflow-hidden">
                <!-- Banner Header dengan Gradient -->
                <div class="banner-gradient p-5" style="background: linear-gradient(135deg, #e84c93 0%, #b24dd8 50%, #5e7af5 100%); min-height: 200px; position: relative;">
                    <div class="row align-items-center h-100">
                        <!-- Avatar dengan Edit Button -->
                        <div class="col-auto position-relative">
                            @if(auth()->user()->profile_photo && Storage::disk('public')->exists(auth()->user()->profile_photo))
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                                     class="rounded-circle shadow-lg border border-5 border-white" 
                                     style="width: 180px; height: 180px; object-fit: cover;"
                                     alt="{{ auth()->user()->name }}" id="profilePhoto">
                            @elseif(auth()->user()->avatar && Storage::disk('public')->exists(auth()->user()->avatar))
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
                                     class="rounded-circle shadow-lg border border-5 border-white" 
                                     style="width: 180px; height: 180px; object-fit: cover;"
                                     alt="{{ auth()->user()->name }}" id="profilePhoto">
                            @else
                                <div class="bg-white bg-opacity-20 text-white rounded-circle shadow-lg border border-5 border-white d-flex align-items-center justify-content-center" 
                                     style="width: 180px; height: 180px; font-size: 4rem; font-weight: bold;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <!-- Tombol Edit Foto -->
                            <button class="btn btn-light btn-sm rounded-circle shadow position-absolute" 
                                    style="width: 40px; height: 40px; bottom: 10px; right: 10px;" 
                                    data-bs-toggle="modal" data-bs-target="#photoModal" title="Ganti Foto Profil">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>

                        <!-- Info User -->
                        <div class="col">
                            <h1 class="text-white fw-bold mb-2" style="font-size: 2.5rem;">{{ auth()->user()->name }}</h1>
                            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                <span class="badge bg-white text-dark px-3 py-2 fs-6 fw-semibold rounded-pill">
                                    WALI MURID
                                </span>
                                @if(auth()->user()->google_id)
                                    <span class="badge bg-white text-danger px-3 py-2 fs-6 rounded-pill">
                                        <i class="bi bi-google me-1"></i>Google
                                    </span>
                                @endif
                            </div>
                            <p class="text-white mb-0 opacity-75">
                                <i class="bi bi-calendar-check me-2"></i>Dibuat: {{ auth()->user()->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card Body dengan Info Detail -->
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Email Card -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">
                                        <i class="bi bi-envelope me-2"></i>Email
                                    </h6>
                                    <h5 class="mb-0">{{ auth()->user()->email }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Telepon Card -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">
                                        <i class="bi bi-telephone me-2"></i>No. Telepon
                                    </h6>
                                    <h5 class="mb-0">{{ auth()->user()->no_telepon }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- GOOGLE CONNECTION CARD -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    @if(auth()->user()->google_id)
                                        {{-- SUDAH TERHUBUNG --}}
                                        <h6 class="card-title text-muted mb-3">
                                            <i class="bi bi-google me-2 text-danger"></i>Akun Google
                                        </h6>
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <span class="badge bg-success fs-6 px-4 py-2 rounded-pill">
                                                ✅ Terhubung
                                            </span>
                                            <form method="POST" action="{{ route('wali.google.unlink') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-1" 
                                                        onclick="return confirm('Yakin hapus tautan Google?')">
                                                    <i class="bi bi-unlink me-1"></i>Putuskan
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        {{-- BELUM TERHUBUNG --}}
                                        <h6 class="card-title text-muted mb-3">
                                            <i class="bi bi-google me-2 text-danger"></i>Tautkan Akun Google
                                        </h6>
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <span class="badge bg-warning fs-6 px-4 py-2 rounded-pill">
                                                ⚠️ Belum terhubung
                                            </span>
                                            <a href="{{ route('google.redirect') }}" class="btn btn-outline-primary btn-sm px-3 py-1">
                                                <i class="bi bi-link-45deg me-1"></i>Tautkan
                                            </a>
                                        </div>
                                        <small class="text-muted">
                                            Hubungkan akun Google untuk login lebih cepat & aman
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Update Profile & Password -->
        <div class="col-12 mt-4">
            <div class="row g-4">
                <!-- Ubah Profile -->
                <div class="col-lg-6">
                    <div class="card shadow border-0 h-100">
                        <div class="card-header bg-gradient-primary text-white">
                            <h6 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('wali.profile.update') }}">
                                @csrf @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">No. Telepon</label>
                                    <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" 
                                           value="{{ old('no_telepon', Auth::user()->no_telepon) }}" placeholder="081234567890">
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-check-circle me-2"></i>Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Ubah Password -->
                <div class="col-lg-6">
                    <div class="card shadow border-0 h-100">
                        <div class="card-header bg-gradient-success text-white">
                            <h6 class="mb-0"><i class="bi bi-lock me-2"></i>Ubah Password</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('wali.password') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required minlength="8">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password_confirmation" class="form-control @error('new_password_confirmation') is-invalid @enderror" required>
                                    @error('new_password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-key me-2"></i>Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ganti Foto Profil -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('user.update.photo') }}" enctype="multipart/form-data" id="photoForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Foto Baru</label>
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                               name="profile_photo" id="photoInput" accept="image/*" required>
                        @error('profile_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format JPG, PNG. Maks 2MB</div>
                    </div>
                    <div class="photo-preview mb-3">
                        <img id="preview" class="img-thumbnail rounded-circle" 
                             style="width: 100px; height: 100px; object-fit: cover; display: none;">
                        <small class="text-muted">Preview foto</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">Upload Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success Alert -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show position-fixed" 
     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Error Alert -->
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show position-fixed" 
     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@endsection

@push('styles')
<style>
.banner-gradient {
    background: linear-gradient(135deg, #e84c93 0%, #b24dd8 50%, #5e7af5 100%);
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}
.card { transition: all 0.3s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview foto upload
    const photoInput = document.getElementById('photoInput');
    const preview = document.getElementById('preview');
    
    if (photoInput && preview) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran file (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB!');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            if (bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});
</script>
@endpush