<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaliController;
use App\Http\Controllers\IzinController;  // ✅ TAMBAH
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\WaliRegisterController;
use Illuminate\Support\Facades\Auth;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes (Login & Logout)
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        
        if (Auth::user()->isWali()) {
            return redirect()->route('wali.dashboard');
        }
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->middleware('guest');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/register-wali', [WaliRegisterController::class, 'showRegistrationForm'])
    ->name('register.wali')
    ->middleware('guest');
    
Route::post('/register-wali', [WaliRegisterController::class, 'register'])
    ->name('register.wali.submit')
    ->middleware('guest');
    
// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Routes yang memerlukan authentication
Route::middleware(['auth'])->group(function () {

    // ✅ IZIN ROUTES (Konfirmasi + Tolak)
    Route::post('/izin/{izin}/konfirmasi', [IzinController::class, 'konfirmasi'])->name('izin.konfirmasi');
    Route::post('/izin/{izin}/tolak', [IzinController::class, 'tolak'])->name('izin.tolak');
    
    // Dashboard - Untuk Guru dan Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('role:guru,admin');

    // Presensi - Untuk Guru dan Admin
    Route::middleware('role:guru,admin')->group(function () {
        Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
        Route::get('/presensi/{id}/edit', [PresensiController::class, 'edit'])->name('presensi.edit');
        Route::put('/presensi/{id}', [PresensiController::class, 'update'])->name('presensi.update');
        Route::delete('/presensi/{id}', [PresensiController::class, 'destroy'])->name('presensi.destroy');
    });

    // Data Siswa - Untuk Guru dan Admin
    Route::middleware('role:guru,admin')->group(function () {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    });

    // Laporan - Untuk Guru dan Admin
    Route::middleware('role:guru,admin')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.pdf');
    });

    // Profile - Untuk Guru dan Admin
    Route::middleware('role:guru,admin')->group(function () {
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('user.password');
        Route::post('/profile/photo', [UserController::class, 'updatePhoto'])->name('user.update.photo');
        Route::post('/profile/unlink-google', [GoogleController::class, 'unlinkGoogle'])->name('google.unlink');
    });

    // User Management - Khusus Admin
    Route::middleware(['auth', 'role:admin'])->prefix('users')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'manageUsers'])->name('manage');
        Route::get('/create', [UserController::class, 'createUser'])->name('create');
        Route::post('/', [UserController::class, 'storeUser'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'editUser'])->name('edit');
        Route::put('/{id}', [UserController::class, 'updateUser'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroyUser'])->name('destroy');
    });

    // ✅ Wali Routes (HANYA SATU - bersihkan duplikat)
    Route::middleware(['role:wali'])->prefix('wali')->name('wali.')->group(function () {
        Route::get('/dashboard', [WaliController::class, 'dashboard'])->name('dashboard');
        Route::get('/izin', [WaliController::class, 'izinForm'])->name('izin');
        Route::post('/izin', [WaliController::class, 'submitIzin'])->name('izin.submit');
        Route::get('/izin/riwayat', [WaliController::class, 'riwayatIzin'])->name('izin.riwayat');
        Route::get('/profile', [WaliController::class, 'profile'])->name('profile');
        Route::put('/profile', [WaliController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [WaliController::class, 'updatePassword'])->name('password');
        Route::post('/profile/unlink-google', [GoogleController::class, 'unlinkGoogle'])->name('google.unlink');
    });
});