<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAnggota()) {
            return $this->anggota($request);
        }

        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $terlambat = Peminjaman::where('status', 'dipinjam')->whereDate('tanggal_batas', '<', today())->count();
        $dendaBelumLunas = (int) Peminjaman::where('denda_lunas', false)->sum('denda');

        $statistik = [
            ['label' => 'Total Buku', 'nilai' => Buku::count(), 'warna' => 'biru'],
            ['label' => 'Total Anggota', 'nilai' => Anggota::count(), 'warna' => 'hijau'],
            ['label' => 'Sedang Dipinjam', 'nilai' => $totalDipinjam, 'warna' => 'kuning'],
            ['label' => 'Buku Terlambat', 'nilai' => $terlambat, 'warna' => 'merah'],
            ['label' => 'Denda Belum Lunas', 'nilai' => 'Rp ' . number_format($dendaBelumLunas, 0, ',', '.'), 'warna' => 'ungu'],
            ['label' => 'Total Transaksi', 'nilai' => Peminjaman::count(), 'warna' => 'abu'],
        ];

        $grafikHarian = collect(range(6, 0))
            ->map(function ($mundur) {
                $tanggal = today()->subDays($mundur);

                return [
                    'label' => $tanggal->translatedFormat('d M'),
                    'nilai' => Peminjaman::whereDate('tanggal_pinjam', $tanggal)->count(),
                ];
            });

        $grafikKategori = Buku::selectRaw('kategori, SUM(stok) as total')->groupBy('kategori')->orderByDesc('total')->get();

        $terbaru = Peminjaman::with(['anggota', 'buku'])->latest('id')->limit(5)->get();

        return view('dashboard', compact('statistik', 'grafikHarian', 'grafikKategori', 'terbaru'));
    }

    private function anggota(Request $request): View
    {
        $anggota = $request->user()->anggota;

        if (! $anggota) {
            return view('dashboard.kosong', ['pesan' => 'Akun Anda belum terhubung dengan data anggota.']);
        }

        $aktif = $anggota->peminjamanAktif()->with('buku')->get();
        $riwayat = $anggota->peminjaman()->with('buku')->latest('id')->limit(5)->get();
        $denda = (int) $anggota->peminjaman()->where('denda_lunas', false)->sum('denda');
        $batas = \App\Models\Pengaturan::aktif()->batas_pinjam;

        return view('dashboard.anggota', compact('anggota', 'aktif', 'riwayat', 'denda', 'batas'));
    }
}