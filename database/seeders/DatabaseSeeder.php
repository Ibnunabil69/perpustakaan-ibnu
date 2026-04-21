<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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
            'email' => 'siswa1@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
        User::create([
            'name' => 'Siswa Dua',
            'email' => 'siswa2@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
        User::create([
            'name' => 'Siswa Tiga',
            'email' => 'siswa3@test.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        // Books
        Book::create([
            'judul' => 'Belajar Laravel 12',
            'penulis' => 'Taylor Otwell',
            'penerbit' => 'Laravel Press',
            'tahun_terbit' => 2025,
            'stok' => 5
        ]);
        Book::create([
            'judul' => 'Mastering Tailwind CSS',
            'penulis' => 'Adam Wathan',
            'penerbit' => 'Tailwind Labs',
            'tahun_terbit' => 2024,
            'stok' => 3
        ]);
        Book::create([
            'judul' => 'Seni Berpikir Positif',
            'penulis' => 'Ibrahim Elfiky',
            'penerbit' => 'Zaman',
            'tahun_terbit' => 2018,
            'stok' => 10
        ]);
        Book::create([
            'judul' => 'Pemrograman Web Mudah',
            'penulis' => 'Budi Raharjo',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2021,
            'stok' => 0
        ]);
        Book::create([
            'judul' => 'Sejarah Dunia Klasik',
            'penulis' => 'John Doe',
            'penerbit' => 'Gramedia',
            'tahun_terbit' => 2015,
            'stok' => 2
        ]);
    }
}
