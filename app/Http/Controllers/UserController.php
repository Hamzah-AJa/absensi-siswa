<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EmailVerificationCode;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Tampilkan profil user (Guru/Admin)
     */
    public function profile()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }
        
        // ADMIN → MANAGE USERS
        if ($user->role == 'admin') {
            return view('user.profile', compact('user'));
        }
        
        // WALI → WALI PROFILE  
        if (method_exists($user, 'isWali') && $user->isWali()) {
            return redirect()->route('wali.profile');
        }
        
        // GURU → USER PROFILE
        return view('user.profile', compact('user'));
    }

    // ✅ FIXED: sendEmailVerification - SESSION DIRECT (NO MAIL)
public function sendEmailVerification(Request $request)
{
    try {
        $user = Auth::user();
        
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false, 
                'message' => 'Email sudah diverifikasi!'
            ]);
        }
        
        // ✅ GENERATE KODE & SIMPAN SESSION + DB
        $code = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expires = now()->addMinutes(10);
        
        // DB + SESSION
        \DB::transaction(function () use ($user, $code, $expires) {
            $user->forceFill([
                'email_verification_code' => $code,
                'email_verification_expires_at' => $expires
            ])->save();
            $user->refresh();
        });
        
        // ✅ SIMPAN DI SESSION (TAMPIL DI HALAMAN)
        session([
            'email_verification_code' => $code,
            'email_verification_sent' => true,
            'email_verification_email' => $user->email
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Kode verifikasi TERSIMPAN!',
            'code' => config('app.env') === 'local' ? $code : null,
            'show_code' => true
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}

// verifyEmail - SESUAI DB
public function verifyEmail(Request $request)
{
    $request->validate(['verification_code' => 'required|digits:6']);
    
    $user = Auth::user()->fresh();
    
    if ($user->email_verified_at) {
        return response()->json(['success' => false, 'message' => 'Sudah diverifikasi']);
    }
    
    if ($user->email_verification_code !== $request->verification_code || 
        now()->gt($user->email_verification_expires_at)) {
        return response()->json(['success' => false, 'message' => 'Kode salah/kadaluarsa']);
    }
    
    $user->update([
        'email_verified_at' => now(),
        'email_verification_code' => null,
        'email_verification_expires_at' => null
    ]);
    
    // CLEAR SESSION
    session()->forget(['email_verification_code', 'email_verification_sent']);
    
    return response()->json(['success' => true, 'message' => 'VERIFIKASI BERHASIL! 🎉']);
}

    /**
     * Update profil user (nama, email, no_telepon)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('no_telepon')) {
            $data['no_telepon'] = $request->no_telepon;
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diupdate!');
    }

    /**
     * Update foto profil - FIXED
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        
        try {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            // Upload foto baru
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            
            // Update database - FORCE SAVE
            $user->forceFill(['profile_photo' => $path])->save();
            
            return redirect()->back()->with('success', 'Foto profil berhasil diupdate!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['profile_photo' => 'Gagal upload foto: ' . $e->getMessage()]);
        }
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    // ========================================
    // ADMIN USER MANAGEMENT
    // ========================================

    public function manageUsers()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $guru = User::where('role', 'guru')->get();
        $wali = User::where('role', 'wali')->get();
        $totalGuru = $guru->count();
        $totalWali = $wali->count();
        $total = $totalGuru + $totalWali;

        return view('user.manage', compact('guru', 'wali', 'totalGuru', 'totalWali', 'total'));
    }

    public function createUser()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('user.create');
    }

    public function storeUser(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:guru,wali',
            'mapel' => 'required_if:role,guru',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_telepon' => $request->no_telepon,
        ];

        if ($request->role == 'guru' && $request->filled('mapel')) {
            // Convert comma-separated string to array
            $mapelArray = array_map('trim', explode(',', $request->mapel));
            $data['mapel'] = $mapelArray;
        }

        User::create($data);

        return redirect()->route('user.manage')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function editUser($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:guru,wali',
            'mapel' => 'required_if:role,guru',
            'no_telepon' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_telepon' => $request->no_telepon,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->role == 'guru' && $request->filled('mapel')) {
            $mapelArray = array_map('trim', explode(',', $request->mapel));
            $data['mapel'] = $mapelArray;
        }

        $user->update($data);

        return redirect()->route('user.manage')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroyUser($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = User::findOrFail($id);
        
        if ($user->id == Auth::id()) {
            return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri']);
        }

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->delete();

        return redirect()->route('user.manage')
            ->with('success', 'User berhasil dihapus!');
    }
}