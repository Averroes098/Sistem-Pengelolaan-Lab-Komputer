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
            // tambahkan kolom status
            $table->boolean('status')->default(1)->after('kapasitas'); 
            // 1 = tersedia, 0 = dipinjam
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratorium', function (Blueprint $table) {
            // hapus kolom status jika rollback
            $table->dropColumn('status');
        });
    }
};
