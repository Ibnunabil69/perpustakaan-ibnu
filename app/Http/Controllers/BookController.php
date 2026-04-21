<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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
        $sortBy = in_array($sortBy, ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok']) ? $sortBy : 'judul';
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'asc';
        $perPage = in_array((int)$perPage, [10, 25, 50]) ? (int)$perPage : 10;

        $books = Book::when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());
            
        return view('books.index_siswa', compact('books', 'search', 'sortBy', 'sortDir', 'perPage'));
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
        $perPage = in_array((int)$perPage, [10, 25, 50]) ? (int)$perPage : 10;

        $books = Book::when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->appends($request->query());
            
        return view('books.index', compact('books', 'search', 'sortBy', 'sortDir', 'perPage'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:255'],
            'penerbit'     => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stok'         => ['required', 'integer', 'min:0', 'max:10000'],
        ], [
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.max'             => 'Judul buku maksimal 255 karakter.',
            'penulis.required'      => 'Nama penulis wajib diisi.',
            'penulis.max'           => 'Nama penulis maksimal 255 karakter.',
            'penerbit.required'     => 'Nama penerbit wajib diisi.',
            'penerbit.max'          => 'Nama penerbit maksimal 255 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min'      => 'Tahun terbit tidak boleh kurang dari 1900.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh lebih dari ' . (date('Y') + 1) . '.',
            'stok.required'         => 'Jumlah stok wajib diisi.',
            'stok.integer'          => 'Stok harus berupa angka.',
            'stok.min'              => 'Stok tidak boleh kurang dari 0.',
            'stok.max'              => 'Stok maksimal 10.000.',
        ]);

        Book::create($validated);
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'penulis'      => ['required', 'string', 'max:255'],
            'penerbit'     => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'stok'         => ['required', 'integer', 'min:0', 'max:10000'],
        ], [
            'judul.required'        => 'Judul buku wajib diisi.',
            'judul.max'             => 'Judul buku maksimal 255 karakter.',
            'penulis.required'      => 'Nama penulis wajib diisi.',
            'penulis.max'           => 'Nama penulis maksimal 255 karakter.',
            'penerbit.required'     => 'Nama penerbit wajib diisi.',
            'penerbit.max'          => 'Nama penerbit maksimal 255 karakter.',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
            'tahun_terbit.integer'  => 'Tahun terbit harus berupa angka.',
            'tahun_terbit.min'      => 'Tahun terbit tidak boleh kurang dari 1900.',
            'tahun_terbit.max'      => 'Tahun terbit tidak boleh lebih dari ' . (date('Y') + 1) . '.',
            'stok.required'         => 'Jumlah stok wajib diisi.',
            'stok.integer'          => 'Stok harus berupa angka.',
            'stok.min'              => 'Stok tidak boleh kurang dari 0.',
            'stok.max'              => 'Stok maksimal 10.000.',
        ]);

        $book->update($validated);
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->peminjaman()->where('status', 'dipinjam')->exists()) {
            return redirect()->route('admin.books.index')->with('error', 'Gagal dihapus: Buku sedang dipinjam.');
        }
        
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
