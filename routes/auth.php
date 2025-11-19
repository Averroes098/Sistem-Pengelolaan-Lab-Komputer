<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Auth Routes (Custom)
|--------------------------------------------------------------------------
|
| File ini berisi route khusus untuk autentikasi (login, register, logout)
| yang menggunakan AuthController buatan sendiri. 
| File ini menggantikan auth bawaan Laravel Breeze/Jetstream.
|
*/

// Pastikan route ini hanya bisa diakses oleh tamu (belum login)
Route::middleware('guest')->group(function () {
    // Halaman login
    Route::get('/', [AuthController::class, 'login'])->name('login.index');
    Route::post('/', [AuthController::class, 'authenticate'])->name('login.authenticate');

    // Halaman register
    Route::get('/register', [AuthController::class, 'register'])->name('register.index');
    Route::post('/register', [AuthController::class, 'create'])->name('register.create');
});

// Logout hanya untuk user yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
