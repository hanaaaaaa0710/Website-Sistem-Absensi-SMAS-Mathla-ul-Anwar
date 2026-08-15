<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE siswa
            MODIFY nama_ortu VARCHAR(40) NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE siswa
            MODIFY nama_ortu VARCHAR(25) NULL
        ");
    }
};