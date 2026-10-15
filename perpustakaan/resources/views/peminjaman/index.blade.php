@extends('layouts.app')

@section('judul', 'Peminjaman')
@section('halaman', 'Peminjaman Buku')

@section('konten')

    <x-kop ikon="📤" judul="Peminjaman Buku" deskripsi="Nomor transaksi dibuat otomatis, contoh PMJ-0001.">
        <x-slot:aksi>
            <a href="{{ route('peminjaman.tambah') }}" class="tombol oranye">+ Catat Peminjaman</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <form method="GET" action="{{ route('peminjaman.index') }}" class="baris-alat">
            <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari no. transaksi / judul / nama anggota">
            <select name="status">
                <option value="dipinjam" @selected($status === 'dipinjam')>Sedang Dipinjam</option>
                <option value="selesai" @selected($status === 'selesai')>Sudah Selesai</option>
                <option value="semua" @selected($status === 'semua')>Semua</option>
            </select>
            <button type="submit" class="tombol oranye">Cari</button>
            <a href="{{ route('peminjaman.index') }}" class="tombol putih">Reset</a>
        </form>

        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Petugas</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $trx)
                        <tr>
                            <td><b>{{ $trx->kode }}</b></td>
                            <td>{{ $trx->anggota->nama }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $trx->tanggal_batas->format('d/m/Y') }}</td>
                            <td>
                                @if ($trx->status === 'dipinjam')
                                    <span class="label-status {{ $trx->terlambat() ? 'merah' : 'kuning' }}">
                                        {{ $trx->terlambat() ? 'Terlambat ' . $trx->hariTerlambatSekarang() . ' hari' : 'Dipinjam' }}
                                    </span>
                                @else
                                    <span class="label-status hijau">Selesai</span>
                                @endif
                            </td>
                            <td>{{ $trx->denda > 0 ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}</td>
                            <td>{{ $trx->kasir->name ?? '-' }}</td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('peminjaman.cetak', $trx) }}" class="tombol abu kecil">Cetak</a>
                                @if ($trx->status === 'dipinjam')
                                    <a href="{{ route('pengembalian.form', $trx) }}" class="tombol oranye kecil">Kembalikan</a>
                                    <form method="POST" action="{{ route('peminjaman.batal', $trx) }}" style="display:inline"
                                          data-konfirmasi="Batalkan transaksi {{ $trx->kode }}?">
                                        @csrf
                                        <button type="submit" class="tombol merah kecil">Batal</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9">
                            <x-kosong pesan="Data peminjaman tidak ditemukan." ikon="📭" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px">{{ $daftar->links() }}</div>
    </div>

@endsection