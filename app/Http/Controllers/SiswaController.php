<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $siswa = $query->paginate(10);
        $kelasList = Siswa::distinct()->pluck('kelas');

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function create()
    {
        $waliList = User::where('role', 'wali')->get();
        return view('siswa.create', compact('waliList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string',
            'no_telepon' => 'required|string',
            'alamat' => 'required|string',
            'wali_id' => 'nullable|exists:users,id',
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $waliList = User::where('role', 'wali')->get();
        
        return view('siswa.edit', compact('siswa', 'waliList'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $id,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string',
            'no_telepon' => 'required|string',
            'alamat' => 'required|string',
            'wali_id' => 'nullable|exists:users,id',
        ]);

        $siswa->update($request->all());

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diupdate!');
    }

    public function destroy($id)
    {
        // Hanya admin yang bisa menghapus
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus!');
    }
}