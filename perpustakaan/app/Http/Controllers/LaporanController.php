<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $dari = $request->query('dari') ?: today()->startOfMonth()->toDateString();
        $sampai = $request->query('sampai') ?: today()->toDateString();

        $transaksi = Peminjaman::with(['anggota', 'buku'])
            ->whereBetween('tanggal_pinjam', [$dari, $sampai])
            ->orderByDesc('tanggal_pinjam')
            ->get();

        $bukuTerlaris = Buku::query()
            ->select('buku.*')
            ->selectSub(Peminjaman::selectRaw('count(*)')
                ->whereColumn('buku_id', 'buku.id')
                ->whereBetween('tanggal_pinjam', [$dari, $sampai]), 'jumlah')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get()
            ->filter(fn ($buku) => $buku->jumlah > 0)
            ->values();

        $anggotaTeraktif = Anggota::query()
            ->select('anggota.*')
            ->selectSub(Peminjaman::selectRaw('count(*)')
                ->whereColumn('anggota_id', 'anggota.id')
                ->whereBetween('tanggal_pinjam', [$dari, $sampai]), 'jumlah')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get()
            ->filter(fn ($anggota) => $anggota->jumlah > 0)
            ->values();

        $terlambat = Peminjaman::with(['anggota', 'buku'])
            ->whereDate('tanggal_batas', '<', today())
            ->where('status', 'dipinjam')
            ->get();

        $tarif = \App\Models\Pengaturan::aktif()->tarif_denda;

        $rekap = [
            ['label' => 'Total Transaksi', 'nilai' => $transaksi->count()],
            ['label' => 'Sudah Selesai', 'nilai' => $transaksi->where('status', 'selesai')->count()],
            ['label' => 'Masih Dipinjam', 'nilai' => $transaksi->where('status', 'dipinjam')->count()],
            ['label' => 'Total Hari Terlambat', 'nilai' => $transaksi->sum('hari_terlambat')],
            ['label' => 'Total Denda', 'nilai' => 'Rp ' . number_format($transaksi->sum('denda'), 0, ',', '.')],
            ['label' => 'Denda Belum Lunas', 'nilai' => 'Rp ' . number_format((int) $transaksi->where('denda_lunas', false)->sum('denda'), 0, ',', '.')],
        ];

        return view('laporan.index', compact('transaksi', 'bukuTerlaris', 'anggotaTeraktif', 'terlambat', 'rekap', 'dari', 'sampai', 'tarif'));
    }
}