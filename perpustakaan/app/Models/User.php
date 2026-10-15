<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'telepon',
        'status',
        'anggota_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function peminjamanDilayani()
    {
        return $this->hasMany(Peminjaman::class, 'kasir_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    public function initials(): string
    {
        $parts = explode(' ', trim($this->name));

        return strtoupper(substr($parts[0], 0, 1) . (end($parts) === $parts[0] ? '' : substr(end($parts), 0, 1)));
    }
}