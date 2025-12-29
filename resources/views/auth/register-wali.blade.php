<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Wali Murid - Sistem Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .search-container {
            position: relative;
        }
        .search-input {
            padding-right: 45px;
        }
        .search-clear {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            font-size: 1.2rem;
        }
        .search-clear:hover {
            color: #495057;
        }
        .siswa-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .no-results {
            color: #6c757d;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-2">Daftar Sebagai Wali Murid</h3>
                        <p class="text-center text-muted mb-4">Isi data di bawah untuk membuat akun</p>
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.wali') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="no_telepon" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                           id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Pilih Siswa yang Diwali <span class="text-danger">*</span></label>
                                    <small class="d-block text-muted mb-2">Cari dan centang siswa yang ingin Anda wali (bisa lebih dari 1)</small>
                                    
                                    <!-- ✅ SEARCH INPUT -->
                                    <div class="search-container mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-search"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control search-input" 
                                                   id="siswaSearch" 
                                                   placeholder="Cari nama siswa atau kelas..."
                                                   autocomplete="off">
                                            <button class="btn btn-outline-secondary search-clear" type="button" id="clearSearch" style="display: none;">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="border rounded p-3 siswa-list" id="siswaList">
                                        @forelse($siswaList as $siswa)
                                        <div class="form-check mb-2 siswa-item" 
                                             data-nama="{{ strtolower($siswa->nama) }}" 
                                             data-kelas="{{ strtolower($siswa->kelas) }}">
                                            <input class="form-check-input" type="checkbox" name="siswa_ids[]" 
                                                   value="{{ $siswa->id }}" id="siswa_{{ $siswa->id }}"
                                                   {{ in_array($siswa->id, old('siswa_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="siswa_{{ $siswa->id }}">
                                                <strong>{{ $siswa->nama }}</strong> - {{ $siswa->kelas }} (NIS: {{ $siswa->nis }})
                                            </label>
                                        </div>
                                        @empty
                                        <div class="no-results">
                                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                            <p>Belum ada data siswa</p>
                                        </div>
                                        @endforelse
                                    </div>
                                    @error('siswa_ids')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-person-plus"></i> Daftar
                                </button>
                                <a href="{{ route('login') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ✅ SEARCH FUNCTIONALITY
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('siswaSearch');
            const clearBtn = document.getElementById('clearSearch');
            const siswaItems = document.querySelectorAll('.siswa-item');
            const siswaList = document.getElementById('siswaList');
            const noResults = siswaList.querySelector('.no-results');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                siswaItems.forEach(item => {
                    const nama = item.dataset.nama;
                    const kelas = item.dataset.kelas;
                    const matches = nama.includes(query) || kelas.includes(query);

                    if (matches) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide clear button
                if (query) {
                    clearBtn.style.display = 'block';
                } else {
                    clearBtn.style.display = 'none';
                    siswaItems.forEach(item => item.style.display = 'block');
                }

                // Hide no-results if siswaList was empty originally
                if (noResults) {
                    noResults.style.display = 'none';
                }
            });

            // Clear search
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                siswaItems.forEach(item => item.style.display = 'block');
                clearBtn.style.display = 'none';
                searchInput.focus();
            });

            // Clear on Escape key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    siswaItems.forEach(item => item.style.display = 'block');
                    clearBtn.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>