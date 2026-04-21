<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('siswa.dashboard');
    }

    public function indexAdmin()
    {
        $totalBuku = Book::count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $totalSiswa = User::where('role', 'siswa')->count();

        return view('dashboard.admin', compact('totalBuku', 'totalPeminjamanAktif', 'totalSiswa'));
    }

    public function indexSiswa()
    {
        $user = Auth::user();
        $bukuDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        return view('dashboard.siswa', compact('bukuDipinjam'));
    }
}
