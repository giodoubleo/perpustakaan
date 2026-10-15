<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    public $timestamps = false;

    protected $fillable = ['batas_pinjam', 'masa_pinjam', 'tarif_denda'];

    protected $casts = [
        'batas_pinjam' => 'integer',
        'masa_pinjam' => 'integer',
        'tarif_denda' => 'integer',
    ];

    public static function aktif(): self
    {
        return static::first() ?? static::create([
            'batas_pinjam' => 3,
            'masa_pinjam' => 7,
            'tarif_denda' => 1000,
        ]);
    }
}