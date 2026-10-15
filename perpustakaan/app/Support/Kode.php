<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class Kode
{
    public static function next(string $prefix, string $model): string
    {
        $terakhir = $model::where('kode', 'like', $prefix . '-%')
            ->orderByDesc('kode')
            ->value('kode');

        $urutan = $terakhir ? ((int) substr($terakhir, strlen($prefix) + 1)) + 1 : 1;

        return sprintf('%s-%04d', $prefix, $urutan);
    }

    public static function dariModel(Model $model, string $prefix): string
    {
        return self::next($prefix, get_class($model));
    }
}