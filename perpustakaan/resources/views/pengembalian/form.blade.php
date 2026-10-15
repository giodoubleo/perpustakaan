@extends('layouts.app')

@section('judul', 'Pengembalian Buku')
@section('halaman', 'Pengembalian Buku')

@section('konten')

    <x-kop ikon="📥" judul="Konfirmasi Pengembalian" deskripsi="Periksa data transaksi, lalu tentukan tanggal buku dikembalikan.">
        <x-slot:aksi>
            <a href="{{ route('pengembalian.index') }}" class="tombol putih">Kembali ke daftar</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">🎫 Detail Transaksi</div>
        <div class="bungkus-tabel">
            <table>
                <tbody>
                    <tr><th style="width:220px">No. Transaksi</th><td>{{ $peminjaman->kode }}</td></tr>
                    <tr><th>Nama Anggota</th><td>{{ $peminjaman->anggota->nama }} ({{ $peminjaman->anggota->kode }})</td></tr>
                    <tr><th>Judul Buku</th><td>{{ $peminjaman->judul }}</td></tr>
                    <tr><th>Tanggal Pinjam</th><td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td></tr>
                    <tr><th>Batas Pengembalian</th><td>{{ $peminjaman->tanggal_batas->format('d/m/Y') }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">🧮 Form Pengembalian</div>

        <div class="statistik">
            <x-statistik label="Perkiraan Hari Terlambat" :nilai="$hariTerlambat . ' hari'" ikon="⏰" warna="kuning" />
            <x-statistik label="Tarif Denda per Hari" :nilai="'Rp ' . number_format($tarif, 0, ',', '.')" ikon="🧮" warna="biru" />
            <x-statistik label="Total Denda" :nilai="'Rp ' . number_format(max(0, $hariTerlambat) * $tarif, 0, ',', '.')" ikon="💰" warna="merah" />
        </div>

        <form method="POST" action="{{ route('pengembalian.simpan', $peminjaman) }}">
            @csrf
            @method('PUT')

            <div class="baris-form" style="margin-top:18px">
                <div>
                    <label>Tanggal Dikembalikan</label>
                    <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', now()->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="tombol oranye">Simpan Pengembalian</button>
                <a href="{{ route('pengembalian.index') }}" class="tombol putih">Batal</a>
            </div>
        </form>
    </div>

@endsection