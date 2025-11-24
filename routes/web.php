<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\AlatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ================== AUTH (LOGIN & REGISTER) ==================
Route::middleware('guest')->group(function () {
    // Halaman Utama (Welcome)
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    
    // Login
    Route::get('/login', [AuthController::class, 'login'])->name('login'); // PENTING: Jangan ubah nama ini
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

    // Register
    Route::get('/register', [AuthController::class, 'register'])->name('register.index');
    Route::post('/register', [AuthController::class, 'create'])->name('register.create');
});

// ================== REDIRECT SETELAH LOGIN ==================
Route::middleware('auth')->get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// ================== LOGOUT ==================
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ================== GLOBAL DASHBOARD ==================
Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ================== ADMIN ROUTES ==================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('laboratorium', LaboratoriumController::class)->except(['show'])->names('admin.laboratorium');
    Route::resource('peminjaman', PeminjamanController::class)->except(['show'])->names('admin.peminjaman');
});

// ================== KADEP ROUTES ==================
Route::prefix('kadep')->middleware(['auth', 'kadep'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('kadep.dashboard');
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('kadep.peminjaman.index');
    Route::resource('alat', AlatController::class)->names('kadep.alat');
});

// ================== USER ROUTES (MAHASISWA/DOSEN) ==================
Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    
    // Form Peminjaman
    Route::get('/peminjaman/create/{lab_id}', [PeminjamanController::class, 'createForUser'])
        ->name('peminjaman.create');
        
    // Proses Simpan
    Route::post('/peminjaman/store-user', [PeminjamanController::class, 'storeUser'])
        ->name('peminjaman.storeUser');

    // SOP
    Route::get('/sop', [StafController::class, 'showSop'])->name('user.sop');
    
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ================== PROFILE (UMUM) ==================
Route::middleware('auth')->group(function () {
    Route::patch('/user/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/complete', [ProfileController::class, 'completeProfile'])->name('profile.complete');
});

// ================== STAF ROUTES ==================
Route::prefix('staf')->middleware(['auth', 'staf'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'stafDashboard'])->name('staf.dashboard');

    // Validasi & Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'validasi'])->name('staf.peminjaman');
    Route::post('/peminjaman/approve/{id}', [PeminjamanController::class, 'approve'])->name('staf.peminjaman.approve');
    Route::post('/peminjaman/reject/{id}', [PeminjamanController::class, 'reject'])->name('staf.peminjaman.reject');

    // Pengembalian
    Route::get('/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('staf.pengembalian');
    Route::post('/pengembalian/{id}', [PeminjamanController::class, 'konfirmasiPengembalian'])->name('staf.pengembalian.konfirmasi');

    // Kerusakan & SOP
    Route::get('/kerusakan', [StafController::class, 'kerusakan'])->name('staf.kerusakan');
    Route::post('/kerusakan/input', [StafController::class, 'inputKerusakan'])->name('staf.kerusakan.input');
    Route::get('/sop', [StafController::class, 'sop'])->name('staf.sop');
    Route::post('/sop/upload', [StafController::class, 'uploadSOP'])->name('staf.sop.upload');
});