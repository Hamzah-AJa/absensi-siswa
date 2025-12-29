@extends('layouts.wali')

@section('title', 'Profil Wali Murid')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Info Akun -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>
                    Informasi Akun
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        @if(auth()->user()->avatar || auth()->user()->profile_photo)
                            <img src="{{ auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : (auth()->user()->profile_photo ? Storage::url(auth()->user()->profile_photo) : asset('images/default-avatar.png')) }}" 
                                 alt="Avatar" class="rounded-circle img-fluid" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px; font-size: 3rem; color: #6c757d;">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </div>
                        @endif
                        
                        <h6 class="mt-2 mb-0">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">Wali Murid</small>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="fw-bold text-muted small mb-1">Email</label>
                                <p class="mb-0">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold text-muted small mb-1">Role</label>
                                <span class="badge bg-primary fs-6 px-3 py-2">WALI MURID</span>
                            </div>
                            @if(auth()->user()->provider)
                            <div class="col-12">
                                <label class="fw-bold text-muted small mb-1">Terhubung dengan</label>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-google text-danger fs-4 me-2"></i>
                                    <span>{{ ucfirst(auth()->user()->provider) }}</span>
                                    @if(auth()->user()->provider_id)
                                    <form action="{{ route('wali.google.unlink') }}" method="POST" class="ms-3" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Putuskan tautan Google?')">
                                            <i class="bi bi-unlink me-1"></i>Putuskan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Ubah Email -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-envelope me-2"></i>Ubah Email
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('wali.profile.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label">Email Baru</label>
                                <input type="email" name="email" class="form-control" 
                                       value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini (Konfirmasi)</label>
                                <input type="password" name="current_password" class="form-control" required>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ubah Password -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-lock me-2"></i>Ubah Password
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('wali.password') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control" required>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required minlength="8">
                                @error('new_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-key me-2"></i>Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(!auth()->user()->provider)
        <!-- Tautkan Google -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-google text-danger me-2"></i>Tautkan Akun Google
                </h6>
            </div>
            <div class="card-body text-center">
                <p class="mb-4">Hubungkan akun Google untuk login lebih mudah dan sinkronisasi data.</p>
                <a href="{{ route('google.redirect') }}" class="btn btn-outline-danger btn-lg">
                    <i class="bi bi-google me-2"></i>Hubungkan Google
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection