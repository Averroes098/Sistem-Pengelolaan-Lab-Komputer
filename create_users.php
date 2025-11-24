<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;

// Delete existing users
User::whereIn('email', ['admin@lab.com', 'staf@lab.com', 'user@lab.com', 'kadep@lab.com'])->delete();

// Create Admin
$admin = User::create([
    'nama' => 'Administrator',
    'email' => 'admin@lab.com',
    'password' => bcrypt('admin123'),
    'level' => 'admin',
    'nim' => 'ADM001',
    'no_telp' => '081234567890',
    'jenis_kelamin' => 'L',
    'program_studi' => 'Admin',
    'angkatan' => 2020,
    'alamat' => 'Lab Building',
    'is_profile_complete' => 1
]);
echo "✓ Admin created: admin@lab.com / admin123\n";

// Create Staf
$staf = User::create([
    'nama' => 'Staff Lab',
    'email' => 'staf@lab.com',
    'password' => bcrypt('staf123'),
    'level' => 'staf',
    'nim' => 'STF001',
    'no_telp' => '081234567891',
    'jenis_kelamin' => 'P',
    'program_studi' => 'Staff',
    'angkatan' => 2021,
    'alamat' => 'Lab Building',
    'is_profile_complete' => 1
]);
echo "✓ Staf created: staf@lab.com / staf123\n";

// Create Kadep
$kadep = User::create([
    'nama' => 'Kepala Departemen',
    'email' => 'kadep@lab.com',
    'password' => bcrypt('kadep123'),
    'level' => 'kadep',
    'nim' => 'KDP001',
    'no_telp' => '081234567892',
    'jenis_kelamin' => 'L',
    'program_studi' => 'Teknik Informatika',
    'angkatan' => 2018,
    'alamat' => 'Lab Building',
    'is_profile_complete' => 1
]);
echo "✓ Kadep created: kadep@lab.com / kadep123\n";

// Create User
$user = User::create([
    'nama' => 'User Mahasiswa',
    'email' => 'user@lab.com',
    'password' => bcrypt('user123'),
    'level' => 'user',
    'nim' => 'NIM123',
    'no_telp' => '081234567892',
    'jenis_kelamin' => 'L',
    'program_studi' => 'Teknik Informatika',
    'angkatan' => 2021,
    'alamat' => 'Jl. Campus No. 1',
    'is_profile_complete' => 1
]);
echo "✓ User created: user@lab.com / user123\n";

echo "\n✓ All users created successfully!\n";
