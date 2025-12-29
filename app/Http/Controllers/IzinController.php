<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function konfirmasi(Request $request, $id)
    {
        $izin = Izin::findOrFail($id);
        
        // FIX: Ganti hasRole() dengan isAdmin() & isGuru() yang sudah ada
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isGuru()) {
            return back()->with('error', 'Tidak memiliki akses!');
        }
        
        $izin->update(['status' => 'approved']);
        
        return back()->with('success', 'Izin ' . $izin->siswa->nama . ' berhasil dikonfirmasi!');
    }
}