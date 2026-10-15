@extends('layouts.app')

@section('judul', 'Dashboard')
@section('halaman', 'Halo, ' . $anggota->nama)

@section('konten')

    <div class="statistik">
        <x-statistik label="Kode Anggota" :nilai="$anggota->kode" ikon="🪪" warna="biru" />
        <x-statistik label="Buku Sedang Dipinjam" :nilai="$aktif->count() . ' / ' . $batas" ikon="📤" warna="kuning" />
        <x-statistik label="Total Riwayat Peminjaman" :nilai="$anggota->peminjaman()->count()" ikon="🗂️" warna="hijau" />
        <x-statistik label="Denda Belum Lunas" :nilai="'Rp ' . number_format($denda, 0, ',', '.')" ikon="🧮" warna="merah" />
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">📚 Buku yang Sedang Saya Pinjam</div>
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aktif as $trx)
                        <tr>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $trx->tanggal_batas->format('d/m/Y') }}</td>
                            <td>
                                @if ($trx->terlambat())
                                    <span class="label-status merah">Terlambat {{ $trx->hariTerlambatSekarang() }} hari</span>
                                @else
                                    <span class="label-status kuning">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="kosong">Anda tidak sedang meminjaman buku.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">🕒 Riwayat Terakhir</div>
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Judul</th>
                        <th>Tanggal Kembali</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $trx)
                        <tr>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $trx->denda > 0 ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="kosong">Belum ada riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('katalog') }}" class="tombol oranye">Lihat Katalog Buku</a>
            <a href="{{ route('riwayat') }}" class="tombol putih">Riwayat Lengkap</a>
        </div>
    </div>

@endsection