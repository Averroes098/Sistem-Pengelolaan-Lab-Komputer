<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laboratorium', function (Blueprint $table) {
            // Tambah kolom status hanya jika belum ada
            if (!Schema::hasColumn('laboratorium', 'status')) {
                $table->enum('status', ['tersedia', 'tidak_tersedia'])->default('tersedia')->after('kapasitas');
            }
            
            // Tambah kolom untuk foto lab
            if (!Schema::hasColumn('laboratorium', 'photo_lab')) {
                $table->string('photo_lab')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratorium', function (Blueprint $table) {
            $table->dropColumn(['status', 'photo_lab']);
        });
    }
};
