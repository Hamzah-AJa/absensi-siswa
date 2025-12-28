<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        return redirect()->route('user.manage');
    }
    
    // WALI → WALI PROFILE  
    if (method_exists($user, 'isWali') && $user->isWali()) {
        return redirect()->route('wali.profile');
    }
    
    // GURU → USER PROFILE
    return view('user.profile', compact('user'));
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

        return view('user.manage', compact('guru', 'wali'));
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
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'mapel' => $request->role == 'guru' ? $request->mapel : null,
        ]);

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
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'mapel' => $request->role == 'guru' ? $request->mapel : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
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