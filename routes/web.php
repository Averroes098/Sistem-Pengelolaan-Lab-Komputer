<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Laboratorium;
use Illuminate\Support\Facades\Route;

Route::get('/',[AuthController::class,'login'])->name('login.index');
Route::post('/',[AuthController::class,'authenticate'])->name('login.authenticate');
Route::get('/register',[AuthController::class,'register'])->name('register.index');
Route::post('/register',[AuthController::class,'create'])->name('register.create');
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

Route::prefix('admin')->middleware(['auth','admin'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::controller(LaboratoriumController::class)->group(function() {
        Route::get('/data-laboratorium', 'index')->name('laboratorium.index');
        Route::get('/tambah-laboratorium', 'create')->name('laboratorium.create');
        Route::post('/tambah-laboratorium', 'store')->name('laboratorium.store');
        Route::get('/edit-laboratorium/{id}', 'edit')->name('laboratorium.edit');
        Route::post('/edit-laboratorium/{id}', 'update')->name('laboratorium.update');
        Route::get('/hapus-laboratorium/{id}', 'destroy')->name('laboratorium.destroy');
    }); 

    Route::controller(PeminjamanController::class)->group(function() {
        Route::get('/data-peminjaman', 'index')->name('peminjaman.index');
        Route::get('/tambah-peminjaman', 'create')->name('peminjaman.create');
        Route::post('/tambah-peminjaman', 'store')->name('peminjaman.store');
        Route::get('/edit-peminjaman/{id}', 'edit')->name('peminjaman.edit');
        Route::post('/edit-peminjaman/{id}', 'update')->name('peminjaman.update');
        Route::get('/hapus-peminjaman/{id}', 'destroy')->name('peminjaman.destroy');
    });
});

Route::middleware(['auth','user'])->group(function() {
    Route::controller(UserController::class)->group(function() {
        Route::get('/user','index')->name('user.index');
        Route::post('/user','pinjam')->name('user.pinjam');
    });
});


