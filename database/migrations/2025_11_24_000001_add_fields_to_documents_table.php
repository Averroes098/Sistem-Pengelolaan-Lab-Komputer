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
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('alat_id')->nullable()->after('lab_id')->constrained('alat')->nullOnDelete();
            $table->enum('status', ['pending', 'confirmed'])->default('pending')->after('file_path');
            $table->foreignId('confirmed_by')->nullable()->after('status')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'confirmed_by')) {
                $table->dropConstrainedForeignId('confirmed_by');
            }
            if (Schema::hasColumn('documents', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('documents', 'alat_id')) {
                $table->dropConstrainedForeignId('alat_id');
            }
        });
    }
};
