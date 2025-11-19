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
            'no_telp' => '081234567890',
            'jenis_kelamin' => 'L',
            'email' => 'admin@lab.com',
            'password' => Hash::make('admin123'),
            'level' => 'admin',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2020',
            'alamat' => 'Jl. Admin No. 1',
            'is_profile_complete' => true,
            'email_verified_at' => now(),
        ]);

        // Staf
        User::create([
            'nim' => '00000001',
            'nama' => 'Staf',
            'no_telp' => '082234567890',
            'jenis_kelamin' => 'P',
            'email' => 'staf@lab.com',
            'password' => Hash::make('staf123'),
            'level' => 'admin',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2021',
            'alamat' => 'Jl. Staf No. 1',
            'is_profile_complete' => true,
            'email_verified_at' => now(),
        ]);

        // Mahasiswa
        User::create([
            'nim' => '2301020006',
            'nama' => 'Mahasiswa',
            'no_telp' => '083234567890',
            'jenis_kelamin' => 'L',
            'email' => 'user@lab.com',
            'password' => Hash::make('user123'),
            'level' => 'user',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2023',
            'alamat' => 'Jl. Mahasiswa No. 1',
            'is_profile_complete' => true,
            'email_verified_at' => now(),
        ]);

        // Mahasiswa tanpa profil lengkap (untuk testing profile completion)
        User::create([
            'nim' => null,
            'nama' => 'User Baru',
            'no_telp' => null,
            'jenis_kelamin' => null,
            'email' => 'newuser@lab.com',
            'password' => Hash::make('password123'),
            'level' => 'user',
            'program_studi' => null,
            'angkatan' => null,
            'alamat' => null,
            'is_profile_complete' => false,
        ]);

        // Data Laboratorium
        Laboratorium::create([
            'nama_lab' => 'Lab Pemrograman',
            'lokasi' => 'Gedung A, Lantai 2',
            'kapasitas' => 30,
            'status' => 'tersedia',
            'photo_lab' => null,
            'keterangan' => 'Lab untuk praktikum pemrograman dasar dan lanjutan',
        ]);
        Laboratorium::create([
            'nama_lab' => 'Lab Sistem Informasi',
            'lokasi' => 'Gedung B, Lantai 1',
            'kapasitas' => 25,
            'status' => 'tersedia',
            'photo_lab' => null,
            'keterangan' => 'Lab untuk praktikum sistem informasi dan database',
        ]);
        Laboratorium::create([
            'nama_lab' => 'Lab Jaringan Komputer',
            'lokasi' => 'Gedung C, Lantai 3',
            'kapasitas' => 20,
            'status' => 'tersedia',
            'photo_lab' => null,
            'keterangan' => 'Lab untuk praktikum jaringan komputer',
        ]);

        // Data Alat untuk Lab Pemrograman
        Alat::create([
            'kode_alat' => 'KOMP-LP-001',
            'lab_id' => 1,
            'nama_alat' => 'PC Desktop - Intel i5',
            'kategori' => 'Komputer',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'PC untuk praktikum pemrograman',
        ]);
        Alat::create([
            'kode_alat' => 'PRINT-LP-001',
            'lab_id' => 1,
            'nama_alat' => 'Printer HP LaserJet',
            'kategori' => 'Printer',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Printer untuk printout dokumentasi',
        ]);
        Alat::create([
            'kode_alat' => 'KEYB-LP-001',
            'lab_id' => 1,
            'nama_alat' => 'Keyboard Mekanik',
            'kategori' => 'Perangkat Keras',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Keyboard gaming untuk lab',
        ]);

        // Data Alat untuk Lab Sistem Informasi
        Alat::create([
            'kode_alat' => 'SERV-LSI-001',
            'lab_id' => 2,
            'nama_alat' => 'Server Dell PowerEdge',
            'kategori' => 'Server',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Server untuk database praktikum',
        ]);
        Alat::create([
            'kode_alat' => 'ROUT-LSI-001',
            'lab_id' => 2,
            'nama_alat' => 'Router Cisco',
            'kategori' => 'Router',
            'kondisi' => 'Rusak',
            'status_peminjaman' => 'tidak_tersedia',
            'keterangan' => 'Router sedang dalam perbaikan',
        ]);

        // Data Alat untuk Lab Jaringan
        Alat::create([
            'kode_alat' => 'SWIT-LJK-001',
            'lab_id' => 3,
            'nama_alat' => 'Switch 48 Port',
            'kategori' => 'Switch',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Switch untuk praktikum networking',
        ]);
        Alat::create([
            'kode_alat' => 'KABE-LJK-001',
            'lab_id' => 3,
            'nama_alat' => 'Kabel Cat6 Ethernet',
            'kategori' => 'Networking',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Kabel untuk koneksi networking',
        ]);
    }
}
