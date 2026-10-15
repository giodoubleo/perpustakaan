<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('landing');
})->name('beranda');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'formLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.proses');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:anggota')->group(function () {
        Route::get('katalog', [PeminjamanController::class, 'katalog'])->name('katalog');
        Route::get('riwayat', [PeminjamanController::class, 'riwayat'])->name('riwayat');
    });

    Route::middleware('role:admin,staff')->group(function () {
        Route::get('buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('buku/tambah', [BukuController::class, 'form'])->name('buku.tambah');
        Route::post('buku', [BukuController::class, 'simpan'])->name('buku.simpan');
        Route::get('buku/{buku}/ubah', [BukuController::class, 'form'])->name('buku.ubah');
        Route::put('buku/{buku}', [BukuController::class, 'ubah'])->name('buku.update');
        Route::delete('buku/{buku}', [BukuController::class, 'hapus'])->name('buku.hapus');

        Route::get('anggota', [AnggotaController::class, 'index'])->name('anggota.index');
        Route::get('anggota/tambah', [AnggotaController::class, 'form'])->name('anggota.tambah');
        Route::post('anggota', [AnggotaController::class, 'simpan'])->name('anggota.simpan');
        Route::get('anggota/{anggota}/ubah', [AnggotaController::class, 'form'])->name('anggota.ubah');
        Route::put('anggota/{anggota}', [AnggotaController::class, 'ubah'])->name('anggota.update');
        Route::delete('anggota/{anggota}', [AnggotaController::class, 'hapus'])->name('anggota.hapus');

        Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('peminjaman/tambah', [PeminjamanController::class, 'form'])->name('peminjaman.tambah');
        Route::post('peminjaman', [PeminjamanController::class, 'simpan'])->name('peminjaman.simpan');
        Route::get('peminjaman/{peminjaman}/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
        Route::post('peminjaman/{peminjaman}/batal', [PeminjamanController::class, 'batal'])->name('peminjaman.batal');

        Route::get('pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('pengembalian/{peminjaman}', [PengembalianController::class, 'form'])->name('pengembalian.form');
        Route::put('pengembalian/{peminjaman}', [PengembalianController::class, 'simpan'])->name('pengembalian.simpan');
        Route::post('pengembalian/{peminjaman}/lunas', [PengembalianController::class, 'lunas'])->name('pengembalian.lunas');

        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('pengguna', [UserController::class, 'index'])->name('user.index');
        Route::get('pengguna/tambah', [UserController::class, 'form'])->name('user.tambah');
        Route::post('pengguna', [UserController::class, 'simpan'])->name('user.simpan');
        Route::get('pengguna/{user}/ubah', [UserController::class, 'form'])->name('user.ubah');
        Route::put('pengguna/{user}', [UserController::class, 'ubah'])->name('user.update');
        Route::delete('pengguna/{user}', [UserController::class, 'hapus'])->name('user.hapus');

        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
        Route::post('pengaturan', [PengaturanController::class, 'simpan'])->name('pengaturan.simpan');
    });
});