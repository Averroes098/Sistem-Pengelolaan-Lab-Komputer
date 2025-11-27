<?php

namespace Database\Seeders;

use App\Models\Laboratorium;
use App\Models\User;
use App\Models\Alat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT USERS
        // Gunakan firstOrCreate agar tidak error "Duplicate Entry" jika dijalankan 2x

        // Admin
        User::firstOrCreate(['email' => 'admin@lab.com'], [
            'nim' => '00000000',
            'nama' => 'Admin',
            'no_telp' => '081234567890',
            'jenis_kelamin' => 'L',
            'password' => Hash::make('admin123'),
            'level' => 'admin',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2020',
            'alamat' => 'Jl. Admin No. 1',
            'is_profile_complete' => true,
            'email_verified_at' => now(),
        ]);

        // Staf
        User::firstOrCreate(['email' => 'staf@lab.com'], [
            'nim' => '00000001',
            'nama' => 'Staf',
            'no_telp' => '082234567890',
            'jenis_kelamin' => 'P',
            'password' => Hash::make('staf123'),
            'level' => 'staf',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2021',
            'alamat' => 'Jl. Staf No. 1',
            'is_profile_complete' => true,
            'email_verified_at' => now(),
        ]);

        // Kepala Departemen (Kadep) - SUDAH BENAR
        User::firstOrCreate(['email' => 'kadep@lab.com'], [
            'nim' => '00000002',
            'nama' => 'Kepala Departemen',
            'no_telp' => '082334567890',
            'jenis_kelamin' => 'L',
            'password' => Hash::make('kadep123'),
            'level' => 'kadep',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2018',
            'alamat' => 'Jl. Kadep No. 1',
            'is_profile_complete' => true,
            'email_verified_at' => now(),
        ]);

        // Mahasiswa
        User::firstOrCreate(['email' => 'user@lab.com'], [
            'nim' => '2301020006',
            'nama' => 'Mahasiswa',
            'no_telp' => '083234567890',
            'jenis_kelamin' => 'L',
            'password' => Hash::make('user123'),
            'level' => 'user',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2023',
            'alamat' => 'Jl. Mahasiswa No. 1',
            'is_profile_complete' => true,
            'email_verified_at' => now(),
        ]);

        // 2. BUAT LABORATORIUM (Simpan ke variabel agar ID-nya otomatis terdeteksi)
        $lab1 = Laboratorium::create([
            'nama_lab' => 'Lab Pemrograman',
            'lokasi' => 'Gedung A, Lantai 2',
            'kapasitas' => 30,
            'status' => 'tersedia',
            'keterangan' => 'Lab untuk praktikum pemrograman dasar dan lanjutan',
        ]);

        $lab2 = Laboratorium::create([
            'nama_lab' => 'Lab Sistem Informasi',
            'lokasi' => 'Gedung B, Lantai 1',
            'kapasitas' => 25,
            'status' => 'tersedia',
            'keterangan' => 'Lab untuk praktikum sistem informasi dan database',
        ]);

        $lab3 = Laboratorium::create([
            'nama_lab' => 'Lab Jaringan Komputer',
            'lokasi' => 'Gedung C, Lantai 3',
            'kapasitas' => 20,
            'status' => 'tersedia',
            'keterangan' => 'Lab untuk praktikum jaringan komputer',
        ]);

        // 3. BUAT ALAT (Gunakan variabel $lab1->id, dll)

        // Data Alat untuk Lab Pemrograman
        Alat::create([
            'kode_alat' => 'KOMP-LP-001',
            'lab_id' => $lab1->id, // Mengambil ID otomatis dari $lab1
            'nama_alat' => 'PC Desktop - Intel i5',
            'kategori' => 'Komputer',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'PC untuk praktikum pemrograman',
        ]);

        Alat::create([
            'kode_alat' => 'PRINT-LP-001',
            'lab_id' => $lab1->id,
            'nama_alat' => 'Printer HP LaserJet',
            'kategori' => 'Printer',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Printer untuk printout dokumentasi',
        ]);

        // Data Alat untuk Lab Sistem Informasi
        Alat::create([
            'kode_alat' => 'SERV-LSI-001',
            'lab_id' => $lab2->id, // Mengambil ID otomatis dari $lab2
            'nama_alat' => 'Server Dell PowerEdge',
            'kategori' => 'Server',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Server untuk database praktikum',
        ]);

        // Data Alat untuk Lab Jaringan
        Alat::create([
            'kode_alat' => 'SWIT-LJK-001',
            'lab_id' => $lab3->id, // Mengambil ID otomatis dari $lab3
            'nama_alat' => 'Switch 48 Port',
            'kategori' => 'Switch',
            'kondisi' => 'Baik',
            'status_peminjaman' => 'tersedia',
            'keterangan' => 'Switch untuk praktikum networking',
        ]);
    }
}
