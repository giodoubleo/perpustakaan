@extends('layouts.app')

@section('judul', 'Riwayat Saya')
@section('halaman', 'Riwayat Peminjaman Saya')

@section('konten')

    <x-kop ikon="🗂️" judul="Riwayat Peminjaman Saya" deskripsi="Riwayat lengkap peminjaman buku milik akun Anda.">
        <x-slot:aksi>
            <a href="{{ route('katalog') }}" class="tombol oranye">Lihat Katalog</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Tanggal Kembali</th>
                        <th>Terlambat</th>
                        <th>Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $trx)
                        <tr>
                            <td>{{ $trx->kode }}</td>
                            <td>{{ $trx->judul }}</td>
                            <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $trx->tanggal_batas->format('d/m/Y') }}</td>
                            <td>{{ $trx->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $trx->hari_terlambat }} hari</td>
                            <td>{{ $trx->denda > 0 ? 'Rp ' . number_format($trx->denda, 0, ',', '.') : '-' }}</td>
                            <td>
                                @if ($trx->status === 'dipinjam')
                                    <span class="label-status {{ $trx->terlambat() ? 'merah' : 'kuning' }}">
                                        {{ $trx->terlambat() ? 'Terlambat' : 'Dipinjam' }}
                                    </span>
                                @else
                                    <span class="label-status hijau">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8">
                            <x-kosong pesan="Belum ada riwayat peminjaman." ikon="📭" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px">{{ $riwayat->links() }}</div>
    </div>

@endsection