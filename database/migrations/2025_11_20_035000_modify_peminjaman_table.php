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
        Schema::table('peminjaman', function (Blueprint $table) {
            // Jika belum ada alat_id, tambahkan
            if (!Schema::hasColumn('peminjaman', 'alat_id')) {
                $table->foreignId('alat_id')->nullable()->constrained('alat')->onDelete('set null')->after('lab_id');
            }
            
            // Tambah durasi peminjaman dalam jam
            $table->integer('durasi_jam')->default(1)->after('tgl_kembali');
            
            // Tambah kolom kondisi alat saat dikembalikan
            $table->enum('kondisi_pengembalian', ['Baik', 'Rusak', 'Hilang'])->nullable()->after('status_pengembalian');
            
            // Tambah keterangan kerusakan jika ada
            $table->text('catatan_kerusakan')->nullable()->after('kondisi_pengembalian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman', 'alat_id')) {
                $table->dropForeignKeyIfExists(['alat_id']);
                $table->dropColumn('alat_id');
            }
            $table->dropColumn(['durasi_jam', 'kondisi_pengembalian', 'catatan_kerusakan']);
        });
    }
};
