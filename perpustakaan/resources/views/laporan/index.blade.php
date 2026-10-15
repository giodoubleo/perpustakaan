@extends('layouts.app')

@section('judul', 'Laporan')
@section('halaman', 'Laporan Perpustakaan')

@section('konten')

    <x-kop ikon="📈" judul="Laporan Perpustakaan" deskripsi="Semua laporan dapat difilter berdasarkan rentang tanggal.">
        <x-slot:aksi>
            <button type="button" onclick="window.print()" class="tombol oranye">Cetak Laporan</button>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <form method="GET" action="{{ route('laporan') }}" class="baris-alat">
            <label style="margin:0">Dari Tanggal</label>
            <input type="date" name="dari" value="{{ $dari }}">
            <label style="margin:0">Sampai Tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai }}">
            <button type="submit" class="tombol oranye">Tampilkan</button>
            <a href="{{ route('laporan') }}" class="tombol putih">Reset</a>
        </form>
    </div>

    <div class="statistik tiga">
        @php
            $ikonRekap = ['🧾', '✅', '📤', '⏰', '🧮', '💰'];
        @endphp
        @foreach ($rekap as $index => $item)
            <x-statistik :label="$item['label']" :nilai="$item['nilai']" :ikon="$ikonRekap[$index] ?? '📘'"
                         :warna="['biru', 'hijau', 'kuning', 'merah', 'ungu', 'abu'][$index] ?? 'biru'" />
        @endforeach
    </div>

    <div class="dua-kolom">
        <div class="kartu animasi-muncul">
            <div class="kartu-judul">🔥 Buku Terlaris</div>
            <div class="bungkus-tabel">
                <table>
                    <thead><tr><th>Judul</th><th>Kategori</th><th style="text-align:right">Dipinjam</th></tr></thead>
                    <tbody>
                        @forelse ($bukuTerlaris as $buku)
                            <tr>
                                <td>{{ $buku->judul }}</td>
                                <td><span class="label-status abu-lm">{{ $buku->kategori }}</span></td>
                                <td style="text-align:right">{{ $buku->jumlah }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><x-kosong pesan="Belum ada data." ikon="📭" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="kartu animasi-muncul">
            <div class="kartu-judul">🏅 Anggota Teraktif</div>
            <div class="bungkus-tabel">
                <table>
                    <thead><tr><th>Nama</th><th>Kode</th><th style="text-align:right">Total Pinjam</th></tr></thead>
                    <tbody>
                        @forelse ($anggotaTeraktif as $item)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <span class="avatar" style="width:28px;height:28px;font-size:11px">{{ $item->initials() }}</span>
                                        <span>{{ $item->nama }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->kode }}</td>
                                <td style="text-align:right">{{ $item->jumlah }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3"><x-kosong pesan="Belum ada data." ikon="📭" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">⏰ Daftar Buku Terlambat</div>
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr><th>No. Transaksi</th><th>Anggota</th><th>Judul</th><th>Batas Kembali</th><th>Terlambat</th><th>Estimasi Denda</th></tr>
                </thead>
                <tbody>
                    @forelse ($terlambat as $trx)
                        <tr>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->anggota->nama }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_batas->format('d/m/Y') }}</td>
                            <td><span class="label-status merah">{{ $trx->hariTerlambatSekarang() }} hari</span></td>
                            <td>Rp {{ number_format($trx->hitungDenda($tarif), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-kosong pesan="Tidak ada buku terlambat." ikon="✅" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">🧾 Rekap Transaksi ({{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }})</div>
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr><th>No. Transaksi</th><th>Anggota</th><th>Judul</th><th>Pinjam</th><th>Kembali</th><th>Denda</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($transaksi as $trx)
                        <tr>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->anggota->nama }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $trx->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                            <td>Rp {{ number_format($trx->denda, 0, ',', '.') }}</td>
                            <td>
                                <span class="label-status {{ $trx->status === 'selesai' ? 'hijau' : 'kuning' }}">
                                    {{ $trx->status === 'selesai' ? 'Selesai' : 'Dipinjam' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">
                            <x-kosong pesan="Tidak ada transaksi pada periode ini." ikon="📭" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection