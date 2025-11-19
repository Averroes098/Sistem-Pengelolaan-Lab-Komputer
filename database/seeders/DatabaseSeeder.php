<?php

namespace Database\Seeders;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'nim' => '00000000',
            'nama' => 'Admin',
            'email' => 'admin@lab.com',
            'password' => Hash::make('admin123'),
            'level' => 'admin'
        ]);

        // Staf
        User::create([
            'nim' => '00000001',
            'nama' => 'Staf',
            'email' => 'staf@lab.com',
            'password' => Hash::make('staf123'),
            'level' => 'staf'
        ]);

        // Mahasiswa
        User::create([
            'nim' => '2301020006',
            'nama' => 'Mahasiswa',
            'email' => 'user@lab.com',
            'password' => Hash::make('user123'),
            'level' => 'user'
        ]);

        // Data Laboratorium
        Laboratorium::create([
            'nama_lab' => 'Laboratorium Informatika',
        ]);
        Laboratorium::create([
            'nama_lab' => 'Laboratorium Elektro',
        ]);
        Laboratorium::create([
            'nama_lab' => 'Laboratorium Perkapalan',
        ]);

        // Data Alat contoh
        Alat::create([
            'kode_alat' => 'A-001',
            'nama_alat' => 'Komputer Desktop',
            'kategori' => 'Komputer',
            'jumlah' => 20,
            'kondisi' => 'Baik',
        ]);
        Alat::create([
            'kode_alat' => 'A-002',
            'nama_alat' => 'Proyektor',
            'kategori' => 'AV',
            'jumlah' => 3,
            'kondisi' => 'Baik',
        ]);
        Alat::create([
            'kode_alat' => 'A-003',
            'nama_alat' => 'Multimeter',
            'kategori' => 'Elektronika',
            'jumlah' => 10,
            'kondisi' => 'Perbaikan',
        ]);
    }
}
