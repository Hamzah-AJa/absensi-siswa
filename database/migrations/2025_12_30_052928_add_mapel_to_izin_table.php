<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('izin', function (Blueprint $table) {
        $table->string('mapel')->nullable()->after('keterangan');
        $table->foreignId('presensi_id')->nullable()->constrained('presensi')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izin', function (Blueprint $table) {
            //
        });
    }
};
