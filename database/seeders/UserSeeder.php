<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Hapus semua user dulu (opsional)
        User::truncate();

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@absensi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'mapel' => null,
        ]);

        // Guru dengan satu mapel
        User::create([
            'name' => 'Joko Susilo',
            'email' => 'joko@guru.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'mapel' => ['Web dan Multimedia'],
        ]);

        // Guru dengan beberapa mapel
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@guru.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'mapel' => ['Matematika', 'Fisika'],
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@guru.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'mapel' => ['Bahasa Indonesia', 'Bahasa Inggris'],
        ]);

        User::create([
            'name' => 'Ani Wijaya',
            'email' => 'ani@guru.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
            'mapel' => ['Pemrograman Dasar', 'Basis Data', 'Sistem Komputer'],
        ]);

        // Wali Murid
        User::create([
            'name' => 'Ahmad Wahyudi',
            'email' => 'ahmad@wali.com',
            'password' => Hash::make('password'),
            'role' => 'wali',
            'mapel' => null,
        ]);
    }
}