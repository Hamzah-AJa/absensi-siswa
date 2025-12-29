<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class WaliRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $siswaList = Siswa::orderBy('nama')->get();
        return view('auth.register-wali', compact('siswaList'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_telepon' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id',
        ], [
            'siswa_ids.required' => 'Pilih minimal 1 siswa yang akan diwali',
            'siswa_ids.min' => 'Pilih minimal 1 siswa yang akan diwali',
        ]);

        // Create user wali
        $wali = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
            'role' => 'wali',
        ]);

        // Update siswa dengan wali_id
        Siswa::whereIn('id', $request->siswa_ids)->update([
            'wali_id' => $wali->id
        ]);

        // Auto login
        Auth::login($wali);

        return redirect()->route('wali.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di Portal Wali Murid.');
    }
}