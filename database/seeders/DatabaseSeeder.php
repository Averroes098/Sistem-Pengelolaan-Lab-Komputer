<?php

namespace Database\Seeders;

use App\Models\Laboratorium;
use App\Models\Peminjaman;
use App\Models\User;
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
            'nama' => 'Kang Admin',
            'no_telp' => '08123456789',
            'jenis_kelamin' => 'L',
            'email' => 'admin@ngobarengaidil.com',
            'password' => Hash::make('admin123'),
            'level' => 'admin'
        ]);

        // User
        User::create([
            'nim' => '2301020006',
            'nama' => 'Aidil Baihaqi',
            'no_telp' => '081268335349',
            'jenis_kelamin' => 'L',
            'email' => 'abaihaqi@ngobarengaidil.com',
            'password' => Hash::make('aidil123'),
            'level' => 'user'
        ]);

        // Data Laboratorium
        Laboratorium::create([
            'nama' => 'Laboratorium Informatika',
            'status' => 1
        ]);
        Laboratorium::create([
            'nama' => 'Laboratorium Elektro',
            'status' => 1
        ]);
        Laboratorium::create([
            'nama' => 'Laboratorium Perkapalan',
            'status' => 1
        ]);
    }
}
