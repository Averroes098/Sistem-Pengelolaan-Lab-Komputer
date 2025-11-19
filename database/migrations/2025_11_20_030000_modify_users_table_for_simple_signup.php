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
        Schema::table('users', function (Blueprint $table) {
            // Ubah existing columns menjadi nullable untuk sign up yang simple
            $table->string('nim')->nullable()->change();
            $table->string('no_telp', 16)->nullable()->change();
            $table->enum('jenis_kelamin', ['L','P'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->change();
            $table->string('no_telp', 16)->change();
            $table->enum('jenis_kelamin', ['L','P'])->change();
        });
    }
};
