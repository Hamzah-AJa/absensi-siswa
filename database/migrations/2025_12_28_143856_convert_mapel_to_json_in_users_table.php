<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Step 1: Tambah kolom sementara
        Schema::table('users', function (Blueprint $table) {
            $table->json('mapel_new')->nullable()->after('mapel');
        });

        // Step 2: Convert data lama ke format JSON
        $users = DB::table('users')->whereNotNull('mapel')->get();
        
        foreach ($users as $user) {
            $mapelValue = $user->mapel;
            
            // Jika sudah berisi data, convert ke array
            if (!empty($mapelValue)) {
                // Jika mengandung koma, split. Jika tidak, jadikan array dengan 1 element
                if (strpos($mapelValue, ',') !== false) {
                    $mapelArray = array_map('trim', explode(',', $mapelValue));
                } else {
                    $mapelArray = [$mapelValue];
                }
                
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['mapel_new' => json_encode($mapelArray)]);
            }
        }

        // Step 3: Hapus kolom lama
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mapel');
        });

        // Step 4: Rename kolom baru
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('mapel_new', 'mapel');
        });
    }

    public function down()
    {
        // Step 1: Tambah kolom string sementara
        Schema::table('users', function (Blueprint $table) {
            $table->string('mapel_old')->nullable()->after('mapel');
        });

        // Step 2: Convert JSON ke string
        $users = DB::table('users')->whereNotNull('mapel')->get();
        
        foreach ($users as $user) {
            $mapelArray = json_decode($user->mapel, true);
            
            if (is_array($mapelArray)) {
                $mapelString = implode(', ', $mapelArray);
                
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['mapel_old' => $mapelString]);
            }
        }

        // Step 3: Hapus kolom JSON
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mapel');
        });

        // Step 4: Rename kolom lama
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('mapel_old', 'mapel');
        });
    }
};