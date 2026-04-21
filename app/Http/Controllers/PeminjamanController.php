<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Admin: Lihat Semua Peminjaman
    public function indexAdmin(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = $request->query('per_page', 10);
        $sortBy = $request->query('sort', 'created_at');
        $sortDir = $request->query('dir', 'desc');

        $sortBy = in_array($sortBy, ['created_at', 'tanggal_pinjam', 'tanggal_kembali', 'status']) ? $sortBy : 'created_at';
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';
        $perPage = in_array((int)$perPage, [10, 25, 50]) ? (int)$perPage : 10;

        $peminjamans = Peminjaman::with(['user', 'book'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('book', function($sub) use ($search) {
                        $sub->where('judul', 'like', "%{$search}%");
                    });
                });
            })
            ->when($status && in_array($status, ['dipinjam', 'dikembalikan']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());
            
        return view('peminjaman.index', compact('peminjamans', 'search', 'status', 'sortBy', 'sortDir', 'perPage'));
    }

    // Admin: Konfirmasi Pengembalian
    public function adminKembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->kembalikan();
        });

        return back()->with('success', 'Pengembalian buku dikonfirmasi, stok telah ditambah.');
    }

    // Admin: Hapus Peminjaman
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status === 'dipinjam') {
                $peminjaman->book->tambahStok();
            }
            $peminjaman->delete();
        });

        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // Siswa: Riwayat Sendiri
    public function riwayat(Request $request)
    {
        $status = $request->query('status');
        $perPage = $request->query('per_page', 10);
        $sortBy = $request->query('sort', 'created_at');
        $sortDir = $request->query('dir', 'desc');

        $sortBy = in_array($sortBy, ['created_at', 'tanggal_pinjam', 'tanggal_kembali', 'status']) ? $sortBy : 'created_at';
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';
        $perPage = in_array((int)$perPage, [10, 25, 50]) ? (int)$perPage : 10;

        $peminjamans = Peminjaman::with('book')
            ->where('user_id', Auth::id())
            ->when($status && in_array($status, ['dipinjam', 'dikembalikan']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());

        return view('peminjaman.riwayat', compact('peminjamans', 'status', 'sortBy', 'sortDir', 'perPage'));
    }

    // Siswa: Pinjam Buku
    public function pinjam(Request $request, $id)
    {
        $user_id = Auth::id();
        
        $jumlahDipinjam = Peminjaman::where('user_id', $user_id)->where('status', 'dipinjam')->count();
        if ($jumlahDipinjam >= 3) {
            return back()->with('error', 'Anda telah mencapai batas maksimal 3 buku yang sedang dipinjam.');
        }

        $book = Book::findOrFail($id);

        $sudahPinjam = Peminjaman::where('user_id', $user_id)
            ->where('book_id', $book->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
        }

        $berhasil = DB::transaction(function () use ($book, $user_id) {
            $book = Book::lockForUpdate()->find($book->id);

            if (!$book || !$book->kurangStok()) {
                return false;
            }

            Peminjaman::create([
                'user_id'        => $user_id,
                'book_id'        => $book->id,
                'tanggal_pinjam' => now(),
                'status'         => 'dipinjam',
            ]);

            return true;
        });

        if ($berhasil) {
            return back()->with('success', 'Buku berhasil dipinjam.');
        }

        return back()->with('error', 'Stok buku habis.');
    }

    // Siswa: Kembalikan Sendiri
    public function siswaKembalikan($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->kembalikan();
        });

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }
}
