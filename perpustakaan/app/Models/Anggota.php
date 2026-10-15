<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = ['kode', 'nama', 'nik', 'gender', 'telepon', 'email', 'alamat', 'tanggal_daftar', 'status'];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function peminjamanAktif()
    {
        return $this->hasMany(Peminjaman::class)->where('status', 'dipinjam');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function totalTerlambat(): int
    {
        return $this->peminjamanAktif()->whereDate('tanggal_batas', '<', today())->count();
    }

    public function totalDenda(): int
    {
        return (int) $this->peminjaman()->sum('denda');
    }

    public function initials(): string
    {
        $parts = explode(' ', trim($this->nama));

        return strtoupper(substr($parts[0], 0, 1) . (end($parts) === $parts[0] ? '' : substr(end($parts), 0, 1)));
    }
}