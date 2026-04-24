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
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'denda_per_hari')) {
                $table->integer('denda_per_hari')->default(0)->after('stok');
            }
        });

        // 1. Temporarily allow new enum values + keep old ones
        DB::statement("ALTER TABLE peminjaman MODIFY status ENUM('dipinjam', 'dikembalikan', 'menunggu', 'disetujui', 'ditolak') NOT NULL DEFAULT 'menunggu'");

        // 2. Map existing data
        DB::table('peminjaman')->where('status', 'dipinjam')->update(['status' => 'disetujui']);

        Schema::table('peminjaman', function (Blueprint $table) {
            // 3. Finalize structure
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dikembalikan'])->default('menunggu')->change();
            $table->integer('total_denda')->default(0)->after('status');
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('total_denda');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('denda_per_hari');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['total_denda', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at']);
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam')->change();
        });
    }

};
