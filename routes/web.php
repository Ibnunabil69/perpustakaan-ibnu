<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes (Common)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->as('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard');
        Route::resource('books', BookController::class);
        Route::resource('anggota', \App\Http\Controllers\Admin\UserController::class);
        Route::get('/peminjaman', [PeminjamanController::class, 'indexAdmin'])->name('peminjaman.index');
        Route::patch('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'adminKembalikan'])->name('peminjaman.kembali');
        Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    });

    // Siswa Routes
    Route::middleware('siswa')->prefix('siswa')->as('siswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexSiswa'])->name('dashboard');
        Route::get('/books', [BookController::class, 'indexSiswa'])->name('books');
        Route::post('/books/{id}/pinjam', [PeminjamanController::class, 'pinjam'])->name('books.pinjam');
        Route::get('/peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
        Route::patch('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'siswaKembalikan'])->name('peminjaman.kembalikan');
    });
});

require __DIR__.'/auth.php';
