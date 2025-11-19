<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'alat_id')) {
                $table->unsignedBigInteger('alat_id')->nullable()->after('lab_id');
                $table->foreign('alat_id')->references('id')->on('alat')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman', 'alat_id')) {
                $table->dropForeign([ 'alat_id' ]);
                $table->dropColumn('alat_id');
            }
        });
    }
};
