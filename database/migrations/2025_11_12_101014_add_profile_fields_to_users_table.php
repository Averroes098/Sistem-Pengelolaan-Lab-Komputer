<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('program_studi')->nullable();
            $table->string('angkatan')->nullable();
            $table->string('alamat')->nullable();
            $table->boolean('is_profile_complete')->default(false);
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'program_studi',
                'angkatan',
                'alamat',
                'is_profile_complete',
            ]);
        });
    }
};
