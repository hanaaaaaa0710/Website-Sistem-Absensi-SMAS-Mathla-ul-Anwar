<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan orang_tua sementara tanpa menghapus siswa.
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

        // Ubah seluruh akun siswa menjadi akun orang tua.
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