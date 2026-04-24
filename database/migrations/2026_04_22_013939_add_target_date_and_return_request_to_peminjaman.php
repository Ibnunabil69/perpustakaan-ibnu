<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add target date column
        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'tanggal_kembali_target')) {
                $table->date('tanggal_kembali_target')->nullable()->after('tanggal_pinjam');
            }
        });

        // 2. Update status enum to include 'menunggu_kembali'
        // MySQL requires raw statement for modifying ENUM efficiently
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('menunggu', 'disetujui', 'ditolak', 'menunggu_kembali', 'dikembalikan') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('tanggal_kembali_target');
        });

        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('menunggu', 'disetujui', 'ditolak', 'dikembalikan') NOT NULL DEFAULT 'menunggu'");
    }
};
