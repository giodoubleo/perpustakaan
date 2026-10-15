@extends('layouts.app')

@section('judul', 'Pengembalian')
@section('halaman', 'Pengembalian Buku')

@section('konten')

    <x-kop ikon="📥" judul="Pengembalian Buku" deskripsi="Denda dihitung otomatis dari selisih tanggal kembali dan batas pengembalian." />

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">📤 Buku yang Sedang Dipinjam ({{ $aktif->count() }})</div>
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Anggota</th>
                        <th>Judul Buku</th>
                        <th>Batas Kembali</th>
                        <th>Keterlambatan</th>
                        <th>Estimasi Denda</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aktif as $trx)
                        <tr>
                            <td><b>{{ $trx->kode }}</b></td>
                            <td>{{ $trx->anggota->nama }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_batas->format('d/m/Y') }}</td>
                            <td>
                                @if ($trx->terlambat())
                                    <span class="label-status merah">{{ $trx->hariTerlambatSekarang() }} hari</span>
                                @else
                                    <span class="label-status hijau">Tepat waktu</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($trx->hitungDenda($tarif), 0, ',', '.') }}</td>
                            <td style="text-align:right">
                                <a href="{{ route('pengembalian.form', $trx) }}" class="tombol oranye kecil">Kembalikan</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <x-kosong pesan="Tidak ada buku yang sedang dipinjam." ikon="✅" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">🕒 Riwayat Pengembalian Terakhir</div>
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Anggota</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Kembali</th>
                        <th>Terlambat</th>
                        <th>Denda</th>
                        <th>Pembayaran Denda</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($selesai as $trx)
                        <tr>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->anggota->nama }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_kembali?->format('d/m/Y') }}</td>
                            <td>{{ $trx->hari_terlambat }} hari</td>
                            <td>Rp {{ number_format($trx->denda, 0, ',', '.') }}</td>
                            <td>
                                @if ($trx->denda === 0)
                                    <span class="label-status abu-lm">Tidak ada denda</span>
                                @elseif ($trx->denda_lunas)
                                    <span class="label-status hijau">Lunas</span>
                                @else
                                    <form method="POST" action="{{ route('pengembalian.lunas', $trx) }}"
                                          data-konfirmasi="Tandai denda {{ $trx->kode }} sudah lunas?">
                                        @csrf
                                        <button type="submit" class="tombol oranye kecil">Tandai Lunas</button>
                                    </form>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <a href="{{ route('peminjaman.cetak', $trx) }}" class="tombol abu kecil">Cetak</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8">
                            <x-kosong pesan="Belum ada pengembalian." ikon="📭" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection