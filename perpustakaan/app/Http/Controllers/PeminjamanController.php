<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Pengaturan;
use App\Models\Peminjaman;
use App\Support\Kode;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'dipinjam');
        $cari = trim($request->query('cari', ''));

        $daftar = Peminjaman::with(['anggota', 'buku'])
            ->when($status !== 'semua', fn ($q) => $q->where('status', $status))
            ->when($cari, fn ($q) => $q->where(fn ($inner) => $inner
                ->where('kode', 'like', "%$cari%")
                ->orWhere('judul', 'like', "%$cari%")
                ->orWhereHas('anggota', fn ($a) => $a->where('nama', 'like', "%$cari%"))))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', compact('daftar', 'status', 'cari'));
    }

    public function form(): View
    {
        $pengaturan = Pengaturan::aktif();
        $anggotaAktif = Anggota::orderBy('nama')->get();
        $bukuTersedia = Buku::get()->filter(fn ($buku) => $buku->tersedia() > 0)->sortBy('judul')->values();

        return view('peminjaman.form', compact('pengaturan', 'anggotaAktif', 'bukuTersedia'));
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'anggota_id' => ['required', 'exists:anggota,id'],
            'buku_id' => ['required', 'exists:buku,id'],
        ]);

        $pengaturan = Pengaturan::aktif();
        $anggota = Anggota::findOrFail($data['anggota_id']);
        $buku = Buku::findOrFail($data['buku_id']);

        if ($anggota->status !== 'aktif') {
            return back()->with('error', 'Anggota berstatus tidak aktif, tidak boleh melakukan peminjaman.');
        }

        if ($buku->tersedia() <= 0) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        if ($anggota->totalTerlambat() > 0) {
            return back()->with('error', 'Anggota memiliki buku yang terlambat. Selesaikan pengembalian terlebih dahulu.');
        }

        $aktif = $anggota->peminjamanAktif()->count();

        if ($aktif >= $pengaturan->batas_pinjam) {
            return back()->with('error', "Anggota sudah memiliki $aktif buku. Batas maksimal {$pengaturan->batas_pinjam} buku.");
        }

        Peminjaman::create([
            'kode' => Kode::next('PMJ', Peminjaman::class),
            'tanggal_pinjam' => today(),
            'tanggal_batas' => today()->addDays($pengaturan->masa_pinjam),
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'kasir_id' => $request->user()->id,
            'judul' => $buku->judul,
            'status' => 'dipinjam',
            'hari_terlambat' => 0,
            'denda' => 0,
            'denda_lunas' => true,
        ]);

        return redirect()->route('peminjaman.index')->with('sukses', 'Peminjaman berhasil dicatat.');
    }

    public function cetak(Peminjaman $peminjaman): View
    {
        $peminjaman->load(['anggota', 'buku', 'kasir']);

        return view('peminjaman.cetak', compact('peminjaman'));
    }

    public function batal(Peminjaman $peminjaman)
    {
        if (! $peminjaman->isDipinjam()) {
            return back()->with('error', 'Transaksi ini sudah selesai.');
        }

        $peminjaman->update([
            'status' => 'selesai',
            'tanggal_kembali' => today(),
            'hari_terlambat' => 0,
            'denda' => 0,
        ]);

        return back()->with('sukses', 'Transaksi peminjaman dibatalkan.');
    }

    public function katalog(Request $request): View
    {
        $cari = trim($request->query('cari', ''));

        $daftar = Buku::when($cari, fn ($q) => $q->where(fn ($inner) => $inner
            ->where('judul', 'like', "%$cari%")
            ->orWhere('penulis', 'like', "%$cari%")
            ->orWhere('kategori', 'like', "%$cari%")))
            ->orderBy('judul')
            ->paginate(12)
            ->withQueryString();

        $daftar->getCollection()->each(function ($buku) {
            $buku->setAttribute('jumlah_tersedia', $buku->tersedia());
        });

        return view('peminjaman.katalog', compact('daftar', 'cari'));
    }

    public function riwayat(Request $request): View
    {
        $anggota = $request->user()->anggota;

        abort_if(! $anggota, 403);

        $riwayat = $anggota->peminjaman()->with('buku')->latest('id')->paginate(10);

        return view('peminjaman.riwayat', compact('riwayat'));
    }
}