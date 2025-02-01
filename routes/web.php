<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsenController;
use Illuminate\Support\Facades\Auth;

// Authentication
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');


Route::get('/list-kelas', [KelasController::class, 'showListKelas'])->middleware('auth')->name('list-kelas');


Route::middleware(['auth'])->group(function () {
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('create-siswa.submit');
    Route::put('/edit-siswa/{id}', [SiswaController::class, 'update'])->name('edit-siswa.submit');
    Route::delete('/delete-siswa/{id}', [SiswaController::class, 'destroy'])->name('delete-siswa');

    Route::post('/tambah-kelas', [KelasController::class, 'addKelas'])->name('create-kelas');

    Route::get('/absen/{kelas}', [AbsenController::class, 'listAbsen'])->name('absen');
    Route::get('/absen/{kelas}/create', [AbsenController::class, 'showCreateForm'])->name('absen.create.form');
    Route::post('/absen/{kelas}/create', [AbsenController::class, 'storeAbsen'])->name('absen.create');
    Route::get('/get-siswa-by-kelas/{kelas}', [AbsenController::class, 'getSiswaByKelas']);
    Route::get('/absen/{kelas}/download/{tanggal}', [AbsenController::class, 'downloadAbsen'])
    ->name('absen.download');
});
