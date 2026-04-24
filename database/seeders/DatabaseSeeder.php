<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Category
        $catFiksi = Category::create(['name' => 'Fiksi', 'slug' => 'fiksi']);
        $catTekno = Category::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $catSains = Category::create(['name' => 'Sains', 'slug' => 'sains']);

        // Admin user
        User::create([
            'name' => 'Admin Perpus',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Siswa users
        User::create([
            'name' => 'Siswa Satu',
            'phone' => '081234567890',
            'email' => 'siswa1@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
        User::create([
            'name' => 'Siswa Dua',
            'phone' => '081234567891',
            'email' => 'siswa2@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
        User::create([
            'name' => 'Siswa Tiga',
            'phone' => '081234567892',
            'email' => 'siswa3@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        // Books
        Book::create([
            'category_id' => $catTekno->id,
            'judul' => 'Belajar Laravel 12',
            'penulis' => 'Taylor Otwell',
            'penerbit' => 'Laravel Press',
            'tahun_terbit' => 2025,
            'stok' => 5
        ]);
        Book::create([
            'category_id' => $catTekno->id,
            'judul' => 'Mastering Tailwind CSS',
            'penulis' => 'Adam Wathan',
            'penerbit' => 'Tailwind Labs',
            'tahun_terbit' => 2024,
            'stok' => 3
        ]);
        Book::create([
            'category_id' => $catFiksi->id,
            'judul' => 'Seni Berpikir Positif',
            'penulis' => 'Ibrahim Elfiky',
            'penerbit' => 'Zaman',
            'tahun_terbit' => 2018,
            'stok' => 10
        ]);
        Book::create([
            'category_id' => $catTekno->id,
            'judul' => 'Pemrograman Web Mudah',
            'penulis' => 'Budi Raharjo',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2021,
            'stok' => 0
        ]);
        Book::create([
            'category_id' => $catSains->id,
            'judul' => 'Sejarah Dunia Klasik',
            'penulis' => 'John Doe',
            'penerbit' => 'Gramedia',
            'tahun_terbit' => 2015,
            'stok' => 2
        ]);
    }
}
