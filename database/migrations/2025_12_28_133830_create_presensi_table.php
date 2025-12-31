<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->string('kelas');
            $table->string('mapel')->nullable();
            $table->date('tanggal');
            $table->enum('keterangan', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->timestamps();
            
            $table->index(['siswa_id', 'tanggal']);
            $table->index(['kelas', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('presensi');
    }
};