<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode', 'tanggal_pinjam', 'tanggal_batas', 'tanggal_kembali',
        'anggota_id', 'buku_id', 'kasir_id', 'judul',
        'status', 'hari_terlambat', 'denda', 'denda_lunas',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_batas' => 'date',
        'tanggal_kembali' => 'date',
        'hari_terlambat' => 'integer',
        'denda' => 'integer',
        'denda_lunas' => 'boolean',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function isDipinjam(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function hariTerlambatSekarang(): int
    {
        return $this->hariTerlambatPada($this->tanggal_kembali ?? today());
    }

    public function hariTerlambatPada($tanggal): int
    {
        return max(0, $this->tanggal_batas->diffInDays($tanggal, false));
    }

    public function terlambat(): bool
    {
        return $this->status === 'dipinjam' && $this->tanggal_batas->isPast();
    }

    public function hitungDenda(int $tarifPerHari): int
    {
        return $this->hariTerlambatSekarang() * $tarifPerHari;
    }
}