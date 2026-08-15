<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            $table->dropForeign('absensi_harian_jadwal_id_foreign');
            $table->dropColumn('jadwal_id');
        });
    }

    public function down(): void
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            $table->foreignId('jadwal_id')
                ->nullable()
                ->constrained('jadwal_pelajaran')
                ->nullOnDelete();
        });
    }
};