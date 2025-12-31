<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ✅ 1. UBAH KOLOM MAPEL JADI NULLABLE
        Schema::table('presensi', function (Blueprint $table) {
            $table->string('mapel')->nullable()->change();
        });
        
        // ✅ 2. CLEAN DATA IZIN yg MAPEL-nya "izin sakit"
        DB::table('presensi')
            ->where('keterangan', 'izin')
            ->where(function($query) {
                $query->where('mapel', 'LIKE', '%izin%')
                      ->orWhere('mapel', 'LIKE', '%sakit%');
            })
            ->update(['mapel' => null]);
    }

    public function down()
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->string('mapel')->nullable(false)->change();
        });
    }
};