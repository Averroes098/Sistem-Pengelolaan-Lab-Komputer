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
        // Memastikan enum 'level' memiliki semua nilai yang benar.
        DB::statement("ALTER TABLE users MODIFY level ENUM('user', 'admin', 'staf', 'kadep') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ke status sebelumnya, yang seharusnya juga memiliki semua nilai.
        // Untuk keamanan, kita definisikan ulang ke state yang stabil.
        DB::statement("ALTER TABLE users MODIFY level ENUM('user', 'admin', 'staf', 'kadep') DEFAULT 'user'");
    }
};
