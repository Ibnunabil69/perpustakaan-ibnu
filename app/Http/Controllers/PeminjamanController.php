<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\Setting;
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
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());
            
        return view('peminjaman.index', compact('peminjamans', 'search', 'status', 'sortBy', 'sortDir', 'perPage'));
    }

    // Admin: Konfirmasi Pengembalian (Langsung)
    public function adminKembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if (!in_array($peminjaman->status, ['disetujui', 'menunggu_kembali'])) {
            return back()->with('error', 'Transaksi tidak dalam status yang dapat dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->kembalikan();
        });

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    // Admin: Setujui Pengajuan Pengembalian
    public function approveKembali($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu_kembali') {
            return back()->with('error', 'Bukan pengajuan pengembalian.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->kembalikan();
        });

        return back()->with('success', 'Pengembalian buku disetujui.');
    }

    public function approve(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $berhasil = DB::transaction(function () use ($peminjaman, $request) {
            $book = Book::lockForUpdate()->find($peminjaman->book_id);

            if (!$book || $book->stok <= 0) {
                return false;
            }

            $book->decrement('stok');
            $peminjaman->update([
                'status' => 'disetujui',
                'tanggal_kembali_target' => $request->tanggal_kembali_target ?? $peminjaman->tanggal_kembali_target,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return true;
        });

        if ($berhasil) {
            return back()->with('success', 'Pengajuan peminjaman disetujui.');
        }

        return back()->with('error', 'Stok buku habis, tidak dapat menyetujui.');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $peminjaman->update([
            'status' => 'ditolak',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    // Admin: Hapus Peminjaman
    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Keamanan: Hanya izinkan hapus jika status masih menunggu atau sudah ditolak
        if (!in_array($peminjaman->status, ['menunggu', 'ditolak'])) {
            return back()->with('error', 'Gagal: Transaksi yang sudah disetujui atau selesai tidak boleh dihapus demi keamanan data buku dan audit.');
        }

        $peminjaman->delete();

        return back()->with('success', 'Data transaksi berhasil dihapus.');
    }

    // Siswa: Riwayat Sendiri
    public function riwayat(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = $request->query('per_page', 10);
        $sortBy = $request->query('sort', 'created_at');
        $sortDir = $request->query('dir', 'desc');

        $sortBy = in_array($sortBy, ['created_at', 'tanggal_pinjam', 'tanggal_kembali', 'status']) ? $sortBy : 'created_at';
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';
        $perPage = in_array((int)$perPage, [10, 25, 50]) ? (int)$perPage : 10;

        $peminjamans = Peminjaman::with('book')
            ->where('user_id', Auth::id())
            ->when($search, function ($query, $search) {
                $query->whereHas('book', function($sub) use ($search) {
                    $sub->where('judul', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());

        return view('peminjaman.riwayat', compact('peminjamans', 'search', 'status', 'sortBy', 'sortDir', 'perPage'));
    }

    public function create($id)
    {
        // UX: Cek limit sebelum menampilkan form
        $jumlahAktif = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->count();

        if ($jumlahAktif >= 3) {
            return redirect()->route('siswa.peminjaman.riwayat')
                ->with('error', 'Gagal: Anda telah mencapai batas maksimal 3 buku yang dipinjam/diajukan.');
        }

        $book = Book::findOrFail($id);
        return view('peminjaman.create', compact('book'));
    }

    // Siswa: Pinjam Buku (Store Pengajuan)
    public function pinjam(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali' => ['required', 'date', 'after:today'],
        ], [
            'tanggal_kembali.required' => 'Tanggal pengembalian wajib diisi.',
            'tanggal_kembali.date' => 'Format tanggal tidak valid.',
            'tanggal_kembali.after' => 'Tanggal pengembalian harus setelah hari ini.',
        ]);

        $user_id = Auth::id();
        
        $jumlahAktif = Peminjaman::where('user_id', $user_id)->whereIn('status', ['menunggu', 'disetujui'])->count();
        if ($jumlahAktif >= 3) {
            return redirect()->route('siswa.peminjaman.riwayat')->with('error', 'Anda telah mencapai batas maksimal 3 buku (termasuk yang sedang diajukan).');
        }

        $book = Book::findOrFail($id);

        $sudahPinjam = Peminjaman::where('user_id', $user_id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($sudahPinjam) {
            return redirect()->route('siswa.peminjaman.riwayat')->with('error', 'Anda sudah mengajukan atau sedang meminjam buku ini.');
        }

        Peminjaman::create([
            'user_id'                => $user_id,
            'book_id'                => $book->id,
            'tanggal_pinjam'         => now(),
            'tanggal_kembali_target' => $request->tanggal_kembali,
            'status'                 => 'menunggu',
        ]);

        return redirect()->route('siswa.peminjaman.riwayat')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    // Siswa: Ajukan Pengembalian
    public function siswaKembalikan($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())->findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Buku tidak dalam status dipinjam.');
        }

        $peminjaman->update(['status' => 'menunggu_kembali']);

        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim.');
    }

    public function laporanDenda(Request $request)
    {
        $today = now()->format('Y-m-d');
        $from = $request->get('from');
        $to = $request->get('to');

        $query = Peminjaman::with(['user', 'book'])
            ->where('total_denda', '>', 0)
            ->where('status', 'dikembalikan');

        if ($from) $query->whereDate('tanggal_kembali', '>=', $from);
        if ($to) $query->whereDate('tanggal_kembali', '<=', $to);

        $peminjamans = $query->orderBy('tanggal_kembali', 'desc')->paginate(10);

        // Summary data
        $totalQuery = Peminjaman::where('status', 'dikembalikan');
        if ($from) $totalQuery->whereDate('tanggal_kembali', '>=', $from);
        if ($to) $totalQuery->whereDate('tanggal_kembali', '<=', $to);
        
        $totalDenda = $totalQuery->sum('total_denda');
        $totalTransactions = $totalQuery->where('total_denda', '>', 0)->count();
        $dendaPerHari = Setting::get('denda_per_hari', 1000);

        // Hitung peminjaman aktif untuk proteksi UI
        $activeLoansCount = Peminjaman::whereIn('status', ['disetujui', 'menunggu_kembali'])->count();

        return view('admin.laporan.denda', compact('peminjamans', 'totalDenda', 'totalTransactions', 'from', 'to', 'today', 'dendaPerHari', 'activeLoansCount'));
    }

    public function updateDendaSetting(Request $request)
    {
        $request->validate([
            'denda_per_hari' => 'required|numeric|min:0',
        ]);

        // Proteksi: Cek apakah ada peminjaman aktif
        $activeLoans = Peminjaman::whereIn('status', ['disetujui', 'menunggu_kembali'])->count();
        if ($activeLoans > 0) {
            return back()->with('error', 'Tidak dapat mengubah denda karena masih ada ' . $activeLoans . ' peminjaman aktif.');
        }

        Setting::updateOrCreate(
            ['key' => 'denda_per_hari'],
            ['value' => $request->denda_per_hari]
        );

        return back()->with('success', 'Pengaturan denda berhasil diperbarui.');
    }

    public function laporanDendaPrint(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $query = Peminjaman::with(['user', 'book'])
            ->where('total_denda', '>', 0)
            ->where('status', 'dikembalikan');

        if ($from) {
            $query->whereDate('tanggal_kembali', '>=', $from);
        }
        if ($to) {
            $query->whereDate('tanggal_kembali', '<=', $to);
        }

        $peminjamans = $query->orderBy('tanggal_kembali', 'desc')->get();
        $totalDenda = $peminjamans->sum('total_denda');

        return view('admin.laporan.cetak-denda', compact('peminjamans', 'totalDenda', 'from', 'to'));
    }
    public function batal($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())->findOrFail($id);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Gagal: Pengajuan yang sudah diproses tidak dapat dibatalkan.');
        }

        $peminjaman->delete();

        return back()->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}
