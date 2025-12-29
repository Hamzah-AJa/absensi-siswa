<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@absensi.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'mapel' => null,
            ]
        );

        // Guru
        User::updateOrCreate(
            ['email' => 'joko@guru.com'],
            [
                'name' => 'Joko Susilo',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'mapel' => ['Web dan Multimedia'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'siti@guru.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'mapel' => ['Matematika', 'Fisika'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi@guru.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'mapel' => ['Bahasa Indonesia', 'Bahasa Inggris'],
            ]
        );

        User::updateOrCreate(
            ['email' => 'ani@guru.com'],
            [
                'name' => 'Ani Wijaya',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'mapel' => ['Pemrograman Dasar', 'Basis Data', 'Sistem Komputer'],
            ]
        );

        // Wali Murid
        User::updateOrCreate(
            ['email' => 'ahmad@wali.com'],
            [
                'name' => 'Ahmad Wahyudi',
                'password' => Hash::make('password'),
                'role' => 'wali',
                'mapel' => null,
            ]
        );
    }
}
