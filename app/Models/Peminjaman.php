<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali_target',
        'tanggal_kembali',
        'status',
        'total_denda',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function kembalikan()
    {
        $now = now();
        // Denda dihitung jika pengembalian melewati target
        $target = \Carbon\Carbon::parse($this->tanggal_kembali_target);
        $denda = 0;
        
        if ($now->greaterThan($target)) {
            $hariTerlambat = $target->startOfDay()->diffInDays($now->startOfDay());
            $globalDenda = \App\Models\Setting::get('denda_per_hari', 1000);
            $denda = $hariTerlambat * $globalDenda;
        }

        $this->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => $now,
            'total_denda' => $denda
        ]);
        $this->book->tambahStok();
    }
}
