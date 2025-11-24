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
            // HAPUS atau KOMENTARI baris lama ini:
            // $table->boolean('status')->default(1)->after('kapasitas'); 

            // GANTI dengan baris ini (Tipe ENUM):
            $table->enum('status', ['tersedia', 'tidak_tersedia'])->default('tersedia')->after('kapasitas');
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
