<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika kolom role belum ada, tambahkan terlebih dahulu.
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', [
                    'admin',
                    'guru',
                    'wali_kelas',
                    'siswa',
                    'orang_tua'
                ])->default('siswa');
            });
        } else {
            // Jika role sudah ada, perluas enum sementara.
            DB::statement("
                ALTER TABLE users
                MODIFY role ENUM(
                    'admin',
                    'guru',
                    'wali_kelas',
                    'siswa',
                    'orang_tua'
                ) NOT NULL DEFAULT 'siswa'
            ");
        }

        // Ubah akun siswa menjadi orang tua.
        DB::table('users')
            ->where('role', 'siswa')
            ->update(['role' => 'orang_tua']);

        // Hapus pilihan siswa dari enum.
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'admin',
                'guru',
                'wali_kelas',
                'orang_tua'
            ) NOT NULL DEFAULT 'orang_tua'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'admin',
                'guru',
                'wali_kelas',
                'siswa',
                'orang_tua'
            ) NOT NULL DEFAULT 'orang_tua'
        ");

        DB::table('users')
            ->where('role', 'orang_tua')
            ->update(['role' => 'siswa']);

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'admin',
                'guru',
                'wali_kelas',
                'siswa'
            ) NOT NULL DEFAULT 'siswa'
        ");
    }
};