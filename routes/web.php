<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StafController;
// use App\Http\Controllers\StafController; // Opsional, jika logika sudah pindah ke PeminjamanController
use Illuminate\Support\Facades\Route;

// ================== AUTH (LOGIN & REGISTER) ==================
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'authenticate'])->name('login.authenticate');

    Route::get('/register', [AuthController::class, 'register'])->name('register.index');
    Route::post('/register', [AuthController::class, 'create'])->name('register.create');
});

// ================== LOGOUT ==================
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ================== GLOBAL DASHBOARD ==================
// Route ini diakses oleh semua role (Admin/Staf/User) setelah login.
// DashboardController@index yang akan menentukan tampilan mana yang dimuat.
Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


// ================== ADMIN ROUTES ==================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard Admin (Opsional, karena sudah ada route global dashboard)
    // Dashboard Admin — tambahkan route khusus sehingga helper `route('admin.dashboard')` valid
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('laboratorium', LaboratoriumController::class)->except(['show']);
    
    // Resource peminjaman untuk admin (CRUD lengkap)
    Route::resource('peminjaman', PeminjamanController::class)->except(['show']);
});


// ================== USER ROUTES (MAHASISWA/DOSEN) ==================
Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    
    // Dashboard user
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    
    // --- FlowChart Peminjaman Baru ---
    // 1. Form Peminjaman (Klik dari Dashboard User)
    Route::get('/peminjaman/create/{lab_id}', [PeminjamanController::class, 'createForUser'])
        ->name('peminjaman.create');
        
    // 2. Proses Simpan Peminjaman User
    Route::post('/peminjaman/store-user', [PeminjamanController::class, 'storeUser'])
        ->name('peminjaman.storeUser');

    // --- Profil User ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ================== STAF ROUTES ==================
Route::prefix('staf')->middleware(['auth', 'staf'])->group(function () {
    
    // Dashboard khusus staf
    Route::get('/dashboard', [DashboardController::class, 'stafDashboard'])
        ->name('staf.dashboard');

    // --- Validasi Peminjaman ---
    Route::get('/peminjaman', [PeminjamanController::class, 'validasi'])
        ->name('staf.peminjaman');

    Route::post('/peminjaman/approve/{id}', [PeminjamanController::class, 'approve'])
        ->name('staf.peminjaman.approve');

    Route::post('/peminjaman/reject/{id}', [PeminjamanController::class, 'reject'])
        ->name('staf.peminjaman.reject');

    // --- Pengembalian ---
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])
        ->name('staf.pengembalian');
        
    Route::post('/pengembalian/{id}', [PeminjamanController::class, 'konfirmasiPengembalian'])
        ->name('staf.pengembalian.konfirmasi');

    // --- Kerusakan & SOP ---
    Route::get('/kerusakan', [StafController::class, 'kerusakan'])->name('staf.kerusakan');
    Route::post('/kerusakan/input', [StafController::class, 'inputKerusakan'])->name('staf.kerusakan.input');
    Route::get('/sop', [StafController::class, 'sop'])->name('staf.sop');
    Route::post('/sop/upload', [StafController::class, 'uploadSOP'])->name('staf.sop.upload');
});