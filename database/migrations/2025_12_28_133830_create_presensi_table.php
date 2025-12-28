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
    Schema::create('presensi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
        $table->date('tanggal');
        $table->string('kelas');
        $table->string('mapel');
        $table->enum('keterangan', ['hadir', 'izin', 'sakit', 'alpa']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
