<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = Auth::user(); // User yang sudah login (untuk link akun)
            
            // ✅ JIKA ADA USER LOGIN → LINK AKUN (WALI/GURU/ADMIN)
            if ($user) {
                if ($user->email === $googleUser->getEmail()) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar()
                    ]);
                    
                    // ✅ REDIRECT SESUAI ROLE - FIX WALi
                    if ($user->role === 'wali' || method_exists($user, 'isWali') && $user->isWali()) {
                        return redirect()->route('wali.profile')
                            ->with('success', 'Akun Google berhasil ditautkan!');
                    }
                    
                    return redirect()->route('user.profile')
                        ->with('success', 'Akun Google berhasil ditautkan!');
                } else {
                    // ✅ REDIRECT SESUAI ROLE - ERROR WALi
                    if ($user->role === 'wali' || method_exists($user, 'isWali') && $user->isWali()) {
                        return redirect()->route('wali.profile')
                            ->with('error', 'Email Google tidak cocok dengan akun!');
                    }
                    return redirect()->route('user.profile')
                        ->with('error', 'Email Google tidak cocok dengan akun!');
                }
            }
            
            // ✅ JIKA BELUM LOGIN → LOGIN/CREATE NORMAL (GURU default)
            $existingUser = User::where('google_id', $googleUser->getId())
                              ->orWhere('email', $googleUser->getEmail())
                              ->first();
                              
            if ($existingUser) {
                Auth::login($existingUser);
            } else {
                $existingUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'role' => 'guru', // Default guru (tidak ubah)
                    'password' => bcrypt(Str::random(16)),
                ]);
                Auth::login($existingUser);
            }
            
            $existingUser->update(['last_login' => now()]);
            
            // ✅ REDIRECT SESUAI ROLE - DASHBOARD
            if ($existingUser->role === 'wali' || method_exists($existingUser, 'isWali') && $existingUser->isWali()) {
                return redirect()->route('wali.dashboard')->with('success', 'Login Google berhasil!');
            }
            
            return redirect()->route('dashboard')->with('success', 'Login Google berhasil!');
            
        } catch (\Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage());
            
            // ✅ ERROR REDIRECT KE LOGIN (bukan user.profile)
            return redirect('/login')->with('error', 'Google login gagal!');
        }
    }

    public function unlinkGoogle(Request $request)
    {
        $user = Auth::user();
        $user->update(['google_id' => null]);

        return back()->with('success', 'Akun Google berhasil diputus!');
    }
}