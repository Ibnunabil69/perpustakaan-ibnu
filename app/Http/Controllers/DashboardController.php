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
        $peminjamanAktif = Peminjaman::whereIn('status', ['disetujui', 'menunggu_kembali', 'dipinjam'])->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $terlambat = Peminjaman::where('status', 'disetujui')
            ->where('tanggal_kembali_target', '<', now())
            ->count();

        $pendingCount = Peminjaman::where('status', 'menunggu')->count();
        $totalDendaCollected = Peminjaman::where('status', 'dikembalikan')->sum('total_denda');
        $completedThisMonth = Peminjaman::where('status', 'dikembalikan')
            ->whereMonth('tanggal_kembali', now()->month)
            ->whereYear('tanggal_kembali', now()->year)
            ->count();

        $recentPeminjaman = Peminjaman::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $topBooks = Book::orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        // Buku populer (paling sering dipinjam)
        $popularBooks = Book::withCount('peminjaman')
            ->orderBy('peminjaman_count', 'desc')
            ->limit(5)
            ->get();

        // Data untuk Grafik Peminjaman (7 Hari Terakhir)
        $peminjamanStats = Peminjaman::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->translatedFormat('d M');
            $stat = $peminjamanStats->firstWhere('date', $date);
            $chartData[] = $stat ? $stat->count : 0;
        }

        // Data untuk Grafik Kategori
        $categoryStats = \App\Models\Category::withCount('books')->get();
        $catLabels = $categoryStats->pluck('name')->toArray();
        $catData = $categoryStats->pluck('books_count')->toArray();

        return view('dashboard.admin', compact(
            'totalBuku', 
            'peminjamanAktif', 
            'totalSiswa', 
            'terlambat', 
            'pendingCount',
            'totalDendaCollected',
            'completedThisMonth',
            'recentPeminjaman', 
            'topBooks',
            'popularBooks',
            'chartLabels',
            'chartData',
            'catLabels',
            'catData'
        ));
    }

    public function indexSiswa()
    {
        $user = Auth::user();
        
        // Ringkasan data untuk dashboard siswa
        $activeLoansCount = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'disetujui', 'menunggu_kembali'])
            ->count();

        $totalLoansCount = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dikembalikan')
            ->count();

        $overdueLoansCount = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'disetujui', 'menunggu_kembali'])
            ->where('tanggal_kembali_target', '<', now())
            ->count();

        $totalDenda = Peminjaman::where('user_id', $user->id)->sum('total_denda');

        // Koleksi Buku Teranyar (Limit 4)
        $teranyar = Book::with('category')->orderBy('created_at', 'desc')->limit(4)->get();

        return view('dashboard.siswa', compact(
            'activeLoansCount',
            'totalLoansCount',
            'overdueLoansCount',
            'totalDenda',
            'teranyar'
        ));
    }
}
