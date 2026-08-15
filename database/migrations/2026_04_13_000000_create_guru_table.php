<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('nip')
                ->unique()
                ->comment('Nomor Induk Pegawai');

            $table->string('nama_guru');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('ttl')
                ->comment('Tempat Tanggal Lahir');

            $table->string('no_hp')
                ->nullable();

            $table->text('alamat')
                ->nullable();

            $table->string('gelar_pendidikan')
                ->nullable();

            $table->string('bidang_keahlian')
                ->nullable();

            $table->enum('status', ['Aktif', 'Tidak Aktif'])
                ->default('Aktif');

            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};