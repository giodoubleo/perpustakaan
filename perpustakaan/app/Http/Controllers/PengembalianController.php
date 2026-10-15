<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    public function index(): View
    {
        $tarif = Pengaturan::aktif()->tarif_denda;

        $aktif = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_batas')
            ->get();

        $selesai = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'selesai')
            ->latest('tanggal_kembali')
            ->limit(20)
            ->get();

        return view('pengembalian.index', compact('aktif', 'selesai', 'tarif'));
    }

    public function form(Peminjaman $peminjaman): View
    {
        abort_if($peminjaman->status !== 'dipinjam', 404);

        $peminjaman->load(['anggota', 'buku']);
        $tarif = Pengaturan::aktif()->tarif_denda;
        $hariTerlambat = $peminjaman->hariTerlambatSekarang();

        return view('pengembalian.form', compact('peminjaman', 'tarif', 'hariTerlambat'));
    }

    public function simpan(Request $request, Peminjaman $peminjaman)
    {
        $request->validate(['tanggal_kembali' => ['required', 'date']]);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Transaksi ini sudah selesai.');
        }

        $tarif = Pengaturan::aktif()->tarif_denda;
        $tanggalKembali = \Illuminate\Support\Carbon::parse($request->input('tanggal_kembali'));

        $terlambat = $peminjaman->hariTerlambatPada($tanggalKembali);
        $denda = $terlambat * $tarif;

        $peminjaman->update([
            'status' => 'selesai',
            'tanggal_kembali' => $tanggalKembali,
            'hari_terlambat' => $terlambat,
            'denda' => $denda,
            'denda_lunas' => $denda === 0,
        ]);

        return redirect()->route('pengembalian.index')->with('sukses', 'Pengembalian berhasil dicatat.');
    }

    public function lunas(Peminjaman $peminjaman)
    {
        $peminjaman->update(['denda_lunas' => true]);

        return back()->with('sukses', 'Denda ditandai sudah lunas.');
    }
}