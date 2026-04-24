<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // View Siswa
    public function indexSiswa(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $sortBy = $request->query('sort', 'judul');
        $sortDir = $request->query('dir', 'asc');

        // Whitelist sortable columns
        $perPage = in_array((int) $perPage, [10, 25, 50]) ? (int) $perPage : 12; // Default 12 untuk grid
        $categoryId = $request->query('category_id');

        $books = Book::with('category')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());

        $categories = Category::orderBy('name')->get();

        // UX: Hitung jumlah pinjaman aktif siswa
        $activeLoansCount = \App\Models\Peminjaman::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->count();

        return view('books.index_siswa', compact('books', 'search', 'sortBy', 'sortDir', 'perPage', 'activeLoansCount', 'categories', 'categoryId'));
    }

    // View Admin
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $sortBy = $request->query('sort', 'judul');
        $sortDir = $request->query('dir', 'asc');

        $sortBy = in_array($sortBy, ['judul', 'penulis', 'tahun_terbit', 'stok', 'created_at']) ? $sortBy : 'judul';
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';
        $perPage = in_array((int) $perPage, [10, 25, 50]) ? (int) $perPage : 10;
        $stockStatus = $request->query('stock_status');
        $categoryId = $request->query('category_id');

        $books = Book::withCount(['peminjaman as active_loans_count' => function ($query) {
                $query->whereIn('status', ['menunggu', 'disetujui', 'menunggu_kembali']);
            }])
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%");
            })
            ->with('category')
            ->when($stockStatus === 'available', function ($query) {
                $query->where('stok', '>', 0);
            })
            ->when($stockStatus === 'out_of_stock', function ($query) {
                $query->where('stok', 0);
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'search', 'sortBy', 'sortDir', 'perPage', 'stockStatus', 'categories', 'categoryId'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $messages = [
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.max' => 'Judul buku maksimal 255 karakter.',
            'cover.image' => 'Sampul harus berupa gambar.',
            'cover.mimes' => 'Format sampul harus JPG, JPEG, atau PNG.',
            'cover.max' => 'Ukuran sampul maksimal 2MB.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min' => 'Tahun terbit minimal tahun 1900.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun depan.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
        ];

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'judul' => ['required', 'string', 'max:255'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stok' => ['required', 'integer', 'min:0', 'max:10000'],
        ], $messages);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($validated);
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }


    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $messages = [
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'judul.required' => 'Judul buku wajib diisi.',
            'judul.max' => 'Judul buku maksimal 255 karakter.',
            'cover.image' => 'Sampul harus berupa gambar.',
            'cover.mimes' => 'Format sampul harus JPG, JPEG, atau PNG.',
            'cover.max' => 'Ukuran sampul maksimal 2MB.',
            'penulis.required' => 'Nama penulis wajib diisi.',
            'penerbit.required' => 'Nama penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min' => 'Tahun terbit minimal tahun 1900.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun depan.',
            'stok.required' => 'Stok buku wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
        ];

        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'judul' => ['required', 'string', 'max:255'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'deskripsi' => ['nullable', 'string'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stok' => ['required', 'integer', 'min:0', 'max:10000'],
        ], $messages);

        if ($request->hasFile('cover')) {
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($validated);
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }


    public function destroy(Book $book)
    {
        // Cek apakah ada peminjaman yang sedang berlangsung atau menunggu persetujuan
        $hasActiveLoans = $book->peminjaman()
            ->whereIn('status', ['menunggu', 'disetujui', 'menunggu_kembali'])
            ->exists();

        if ($hasActiveLoans) {
            return redirect()->route('admin.books.index')->with('error', 'Gagal dihapus! Buku ini memiliki transaksi aktif (dipinjam/menunggu persetujuan).');
        }

        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
