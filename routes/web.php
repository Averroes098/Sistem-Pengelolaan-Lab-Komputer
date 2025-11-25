<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\KadepController;
use Illuminate\Support\Facades\Route;

// ================== AUTH (LOGIN & REGISTER) ==================
Route::middleware('guest')->group(function () {
    // Halaman Utama (Welcome)
    Route::get('/', fn() => view('welcome'))->name('welcome');

    // Login
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

    // Register
    Route::get('/register', [AuthController::class, 'register'])->name('register.index');
    Route::post('/register', [AuthController::class, 'create'])->name('register.create');
});

// ================== LOGOUT ==================
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ================== GLOBAL DASHBOARD (Untuk kompatibilitas) ==================
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();
    switch ($user->level) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'kadep':
            return redirect()->route('kadep.dashboard');
        case 'staf':
            return redirect()->route('staf.dashboard');
        default:
            return redirect()->route('dashboard.user');
    }
})->name('dashboard');

// ================== DASHBOARD CONTROLLER ==================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/staf', [DashboardController::class, 'stafDashboard'])->name('dashboard.staf');
    Route::get('/dashboard/user', [UserController::class, 'index'])->name('dashboard.user');
});

// ================== ADMIN ROUTES ==================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('laboratorium', LaboratoriumController::class)
        ->except(['show'])
        ->names('admin.laboratorium');

    // Peminjaman Lab & Alat
    Route::get('/peminjaman/lab', [PeminjamanController::class, 'labIndex'])->name('admin.peminjaman.lab.index');
    Route::get('/peminjaman/lab/create', [PeminjamanController::class, 'labCreate'])->name('admin.peminjaman.lab.create');
    Route::post('/peminjaman/lab/store', [PeminjamanController::class, 'labStore'])->name('admin.peminjaman.lab.store');
    Route::delete('/peminjaman/lab/{id}/destroy', [PeminjamanController::class, 'destroy'])->name('admin.peminjaman.lab.destroy');

    Route::get('/peminjaman/alat', [PeminjamanController::class, 'alatIndex'])->name('admin.peminjaman.alat.index');
    Route::get('/peminjaman/alat/create', [PeminjamanController::class, 'alatCreate'])->name('admin.peminjaman.alat.create');
    Route::post('/peminjaman/alat/store', [PeminjamanController::class, 'alatStore'])->name('admin.peminjaman.alat.store');
});

// ================== KADEP ROUTES ==================
Route::middleware(['auth', 'kadep'])->group(function () {
    Route::get('/dashboard', [KadepController::class, 'dashboard'])->name('kadep.dashboard');

    // Peminjaman untuk Kadep
    Route::get('/kadep/peminjaman', [PeminjamanController::class, 'kadepIndex'])->name('kadep.peminjaman.index');

    // Resource Alat
    Route::resource('/kadep/alat', AlatController::class)->names([
        'index' => 'kadep.alat.index',
        'create' => 'kadep.alat.create',
        'store' => 'kadep.alat.store',
        'show' => 'kadep.alat.show',
        'edit' => 'kadep.alat.edit',
        'update' => 'kadep.alat.update',
        'destroy' => 'kadep.alat.destroy',
    ]);

    // Lab untuk Kadep
    Route::prefix('kadep/lab')->name('kadep.peminjaman.lab.')->group(function() {
        Route::get('/', [PeminjamanController::class, 'labIndex'])->name('index');
        Route::get('/create', [PeminjamanController::class, 'labCreate'])->name('create');
        Route::post('/store', [PeminjamanController::class, 'labStore'])->name('store');
        Route::get('/{id}/edit', [PeminjamanController::class, 'editLab'])->name('edit');
        Route::put('/{id}', [PeminjamanController::class, 'updateLab'])->name('update');
        Route::delete('/{id}', [PeminjamanController::class, 'destroyLab'])->name('destroy');
    });

    // Laporan Kerusakan
    Route::get('/kadep/kerusakan', [KadepController::class, 'kerusakanIndex'])->name('kadep.kerusakan.index');
    Route::post('/kadep/kerusakan/confirm/{id}', [KadepController::class, 'confirmReport'])->name('kadep.kerusakan.confirm');
});

// ================== USER ROUTES ==================
Route::prefix('user')->middleware(['auth', 'user'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('dashboard.user');

    // Form Peminjaman
    Route::get('/peminjaman/create/{lab_id}', [PeminjamanController::class, 'createForUser'])
        ->name('peminjaman.create');
    Route::post('/peminjaman/store-user', [PeminjamanController::class, 'storeUser'])
        ->name('peminjaman.storeUser');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.user')->middleware('auth');

    // SOP & Profil
    Route::get('/sop', [StafController::class, 'showSop'])->name('user.sop');
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

    // Laporan Peminjaman
    Route::get('/laporan/peminjaman', [PeminjamanController::class, 'laporanPeminjaman'])->name('staf.laporan.peminjaman');
});