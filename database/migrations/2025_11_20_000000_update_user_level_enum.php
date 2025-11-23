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
        // Ubah enum level untuk menambah 'staf'
        DB::statement("ALTER TABLE users MODIFY level ENUM('admin', 'staf', 'user') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop kolom dan buat ulang untuk avoid data truncation error
        DB::statement("ALTER TABLE users DROP COLUMN level");
        DB::statement("ALTER TABLE users ADD COLUMN level ENUM('user','admin') DEFAULT 'user' AFTER email");
    }
};
