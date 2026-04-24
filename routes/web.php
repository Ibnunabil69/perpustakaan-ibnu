<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $books = \App\Models\Book::with('category')->latest()->take(4)->get();
    return view('landing', compact('books'));
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
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'edit', 'show']);
        Route::resource('anggota', \App\Http\Controllers\Admin\UserController::class);
        Route::get('/peminjaman', [PeminjamanController::class, 'indexAdmin'])->name('peminjaman.index');
        Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::patch('/peminjaman/{id}/approve-kembali', [PeminjamanController::class, 'approveKembali'])->name('peminjaman.approve_kembali');
        Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::patch('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'adminKembalikan'])->name('peminjaman.kembali');
        Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
        Route::get('/laporan-denda', [PeminjamanController::class, 'laporanDenda'])->name('laporan.denda');
        Route::get('/laporan-denda/cetak', [PeminjamanController::class, 'laporanDendaPrint'])->name('laporan.denda.print');
        Route::post('/settings/update-denda', [PeminjamanController::class, 'updateDendaSetting'])->name('settings.update_denda');
    });

    // Siswa Routes
    Route::middleware('siswa')->prefix('siswa')->as('siswa.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexSiswa'])->name('dashboard');
        Route::get('/books', [BookController::class, 'indexSiswa'])->name('books');
        Route::get('/books/{id}/pinjam', [PeminjamanController::class, 'create'])->name('books.pinjam.form');
        Route::post('/books/{id}/pinjam', [PeminjamanController::class, 'pinjam'])->name('books.pinjam');
        Route::get('/peminjaman', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
        Route::patch('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'siswaKembalikan'])->name('peminjaman.kembalikan');
        Route::delete('/peminjaman/{id}/batal', [PeminjamanController::class, 'batal'])->name('peminjaman.batal');
    });
});

require __DIR__.'/auth.php';
