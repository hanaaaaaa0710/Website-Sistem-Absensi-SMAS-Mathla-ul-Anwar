<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha']);
            $table->string('keterangan')->nullable();
            $table->enum('status_notifikasi', ['Berhasil', 'Gagal', 'Menunggu'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('siswa_id')
                ->references('id')
                ->on('siswa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};