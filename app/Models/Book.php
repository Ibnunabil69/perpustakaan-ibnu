<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok'];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function kurangStok()
    {
        if ($this->stok > 0) {
            $this->decrement('stok');
            return true;
        }
        return false;
    }

    public function tambahStok()
    {
        $this->increment('stok');
    }
}
