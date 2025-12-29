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
        $table->date('tanggal_mulai')->after('tanggal');  // baru
        $table->date('tanggal_selesai')->after('tanggal_mulai');  // baru
        $table->dropColumn('tanggal');  // hapus yang lama
    });
}

public function down()
{
    Schema::table('izin', function (Blueprint $table) {
        $table->date('tanggal');
        $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
    });
}

};
