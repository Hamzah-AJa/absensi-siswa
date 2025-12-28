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
    Schema::create('izin', function (Blueprint $table) {
        $table->id();
        $table->foreignId('wali_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
        $table->text('alasan');
        $table->date('tanggal');
        $table->string('foto_bukti')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin');
    }
};
