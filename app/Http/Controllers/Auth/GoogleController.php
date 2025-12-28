<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

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
        $user = Auth::user(); // User yang sudah login
        
        // JIKA ADA USER LOGIN → LINK AKUN
        if ($user) {
            if ($user->email === $googleUser->email) {
                $user->update(['google_id' => $googleUser->id]);
                return redirect()->route('user.profile')
                    ->with('success', 'Akun Google berhasil ditautkan!');
            } else {
                return redirect()->route('user.profile')
                    ->with('error', 'Email Google tidak cocok dengan akun!');
            }
        }
        
        // JIKA BELUM LOGIN → LOGIN NORMAL
        $existingUser = User::where('google_id', $googleUser->id)
                           ->orWhere('email', $googleUser->email)
                           ->first();
                           
        if ($existingUser) {
            Auth::login($existingUser);
        } else {
            $existingUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'role' => 'guru',
                'password' => bcrypt(Str::random(16)),
            ]);
            Auth::login($existingUser);
        }
        
        $existingUser->update(['last_login' => now()]);
        return redirect()->route('user.profile')
            ->with('success', 'Login Google berhasil!');
            
    } catch (\Exception $e) {
        return redirect()->route('user.profile')
            ->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    public function unlinkGoogle()
    {
        $user = Auth::user();
        $user->update(['google_id' => null]);

        return back()->with('success', 'Akun Google berhasil diputus!');
    }
}