<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    public function run()
    {
        $mapelList = [
            'Pemrograman Berorientasi Objek',
            'Basis Data',
            'Matematika',
            'Pendidikan Agama & Budi Pekerti',
            'Pemrograman Web',
            'Pendidikan Kewarganegaraan',
            'Bahasa Jepang',
            'Bahasa Inggris',
            'Bahasa Indonesia',
            'Multimedia',
            'KIK'
        ];

        foreach($mapelList as $index => $mapel) {
            User::updateOrCreate(
                ['id' => $index + 1], // Asumsi guru id 1-11
                ['mapel' => $mapel, 'role' => 'guru']
            );
        }
    }
}
