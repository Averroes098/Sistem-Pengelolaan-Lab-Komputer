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
        Schema::table('alat', function (Blueprint $table) {
            // Tambah lab_id foreign key
            $table->foreignId('lab_id')->constrained('laboratorium')->onDelete('cascade')->after('kode_alat');
            
            // Ubah kategori menjadi enum
            $table->dropColumn('kategori');
            $table->enum('kategori', [
                'Komputer',
                'Printer',
                'Router',
                'Switch',
                'Server',
                'Networking',
                'Perangkat Lunak',
                'Perangkat Keras',
                'Lainnya'
            ])->default('Lainnya')->after('nama_alat');
            
            // Hapus lokasi jika ada
            if (Schema::hasColumn('alat', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
            
            // Ubah jumlah menjadi status_peminjaman
            $table->dropColumn('jumlah');
            $table->enum('status_peminjaman', ['tersedia', 'tidak_tersedia'])->default('tersedia')->after('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['lab_id']);
            $table->dropColumn('lab_id');
            
            $table->dropColumn('kategori');
            $table->string('kategori')->nullable()->after('nama_alat');
            
            $table->dropColumn('status_peminjaman');
            $table->integer('jumlah')->default(1)->after('kategori');
        });
    }
};
