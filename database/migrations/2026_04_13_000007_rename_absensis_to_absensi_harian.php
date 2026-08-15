<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('absensi_harian') && Schema::hasTable('absensis')) {
            Schema::rename('absensis', 'absensi_harian');
        }

        if (Schema::hasTable('absensi_harian')) {
            Schema::table('absensi_harian', function (Blueprint $table) {
                if (!Schema::hasColumn('absensi_harian', 'metode_absensi')) {
                    $table->enum('metode_absensi', ['Manual'])
                        ->default('Manual')
                        ->after('jam_masuk');
                }

                if (!Schema::hasColumn('absensi_harian', 'bukti_izin')) {
                    $table->string('bukti_izin')
                        ->nullable()
                        ->after('keterangan');
                }

                if (!Schema::hasColumn('absensi_harian', 'created_by')) {
                    $table->foreignId('created_by')
                        ->nullable()
                        ->after('bukti_izin')
                        ->constrained('users')
                        ->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        // dikosongkan saja biar aman
    }
};