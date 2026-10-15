@extends('layouts.app')

@section('judul', 'Dashboard')
@section('halaman', 'Dashboard')

@section('konten')

    @php
        $ikon = ['📚', '👥', '📤', '⏰', '🧮', '🗂️'];
    @endphp

    <div class="statistik tiga">
        @foreach ($statistik as $index => $item)
            <x-statistik :label="$item['label']" :nilai="$item['nilai']" :ikon="$ikon[$index] ?? '📘'" :warna="$item['warna']" />
        @endforeach
    </div>

    <div class="dua-kolom">
        <div class="kartu animasi-muncul">
            <div class="kartu-judul">📊 Peminjaman 7 Hari Terakhir</div>
            @php $puncak = max(1, collect($grafikHarian)->max('nilai')); @endphp
            <div class="grafik">
                @foreach ($grafikHarian as $hari)
                    <div class="batang">
                        <div class="jumlah">{{ $hari['nilai'] }}</div>
                        <div class="isi" style="height: {{ (int) ($hari['nilai'] / $puncak * 120) }}px"></div>
                        <div class="label">{{ $hari['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="kartu animasi-muncul">
            <div class="kartu-judul">🗂️ Jumlah Buku per Kategori</div>
            @php $puncakKategori = max(1, collect($grafikKategori)->max('total')); @endphp
            <div class="grafik">
                @foreach ($grafikKategori as $kategori)
                    <div class="batang">
                        <div class="jumlah">{{ $kategori->total }}</div>
                        <div class="isi" style="height: {{ (int) ($kategori->total / $puncakKategori * 120) }}px"></div>
                        <div class="label">{{ $kategori->kategori }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">🕒 Transaksi Terbaru</div>
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
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $trx)
                        <tr>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->anggota->nama }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $trx->tanggal_batas->format('d/m/Y') }}</td>
                            <td>
                                @if ($trx->status === 'dipinjam')
                                    <span class="label-status {{ $trx->terlambat() ? 'merah' : 'kuning' }}">
                                        {{ $trx->terlambat() ? 'Terlambat' : 'Dipinjam' }}
                                    </span>
                                @else
                                    <span class="label-status hijau">Selesai</span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <a href="{{ route('peminjaman.cetak', $trx) }}" class="tombol putih kecil">Cetak</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="kosong">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('peminjaman.tambah') }}" class="tombol oranye">+ Catat Peminjaman</a>
            <a href="{{ route('pengembalian.index') }}" class="tombol hijau">Proses Pengembalian</a>
            <a href="{{ route('laporan') }}" class="tombol putih">Lihat Laporan</a>
        </div>
    </div>

@endsection