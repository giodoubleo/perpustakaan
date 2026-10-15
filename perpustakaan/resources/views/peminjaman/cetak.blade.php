<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Transaksi {{ $peminjaman->kode }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="cetak">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px">
        <div>
            <h2 style="margin-bottom:2px">BUKTI TRANSAKSI</h2>
            <div style="color:#7b7367;font-size:13px">{{ config('app.name') }}</div>
        </div>
        <div style="text-align:right;font-size:13px;color:#7b7367">
            No. <b>{{ $peminjaman->kode }}</b><br>
            Dicetak: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="bungkus-tabel">
        <table>
            <tbody>
                <tr><th style="width:190px">Nomor Transaksi</th><td>{{ $peminjaman->kode }}</td></tr>
                <tr><th>Nama Anggota</th><td>{{ $peminjaman->anggota->nama }} ({{ $peminjaman->anggota->kode }})</td></tr>
                <tr><th>Kode Buku</th><td>{{ $peminjaman->buku->kode }}</td></tr>
                <tr><th>Judul Buku</th><td>{{ $peminjaman->judul }}</td></tr>
                <tr><th>Tanggal Pinjam</th><td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td></tr>
                <tr><th>Batas Pengembalian</th><td>{{ $peminjaman->tanggal_batas->format('d/m/Y') }}</td></tr>
                <tr><th>Tanggal Dikembalikan</th><td>{{ $peminjaman->tanggal_kembali?->format('d/m/Y') ?? 'Belum dikembalikan' }}</td></tr>
                <tr><th>Status</th><td>{{ $peminjaman->status === 'selesai' ? 'Selesai' : 'Sedang Dipinjam' }}</td></tr>
                <tr><th>Hari Terlambat</th><td>{{ $peminjaman->hari_terlambat }} hari</td></tr>
                <tr><th>Denda</th><td>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</td></tr>
                <tr><th>Petugas</th><td>{{ $peminjaman->kasir->name ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    <p style="margin-top:36px;display:flex;justify-content:space-between">
        <span>Tanda tangan Peminjam</span>
        <span>Tanda tangan Petugas</span>
    </p>
    <p style="display:flex;justify-content:space-between;margin-top:34px;color:#7b7367">
        <span>(....................................)</span>
        <span>(....................................)</span>
    </p>

    <div class="tanpa-cetak" style="margin-top:34px;display:flex;gap:10px">
        <a href="{{ route('peminjaman.index') }}" class="tombol oranye">Kembali</a>
        <button onclick="window.print()" class="tombol hijau">Cetak Halaman</button>
    </div>

</div>
</body>
</html>