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
        // Ubah enum level untuk menambah 'kadep'
        DB::statement("ALTER TABLE users MODIFY level ENUM('admin', 'staf', 'user', 'kadep') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembali ke enum sebelumnya
        DB::statement("ALTER TABLE users MODIFY level ENUM('admin', 'staf', 'user') DEFAULT 'user'");
    }
};
