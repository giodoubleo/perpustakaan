<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = ['kode', 'isbn', 'judul', 'penulis', 'penerbit', 'tahun', 'kategori', 'stok', 'lokasi'];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function dipinjam(): int
    {
        return $this->peminjaman()->where('status', 'dipinjam')->count();
    }

    public function tersedia(): int
    {
        return max(0, $this->stok - $this->dipinjam());
    }

    public function kategoriList(): string
    {
        return $this->kategori;
    }
}