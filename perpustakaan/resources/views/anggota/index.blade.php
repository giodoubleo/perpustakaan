@extends('layouts.app')

@section('judul', 'Data Anggota')
@section('halaman', 'Data Anggota')

@section('konten')

    <x-kop ikon="👥" judul="Data Anggota" deskripsi="Kode anggota dibuat otomatis oleh sistem, contoh ANG-0001.">
        <x-slot:aksi>
            <a href="{{ route('anggota.tambah') }}" class="tombol oranye">+ Tambah Anggota</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <form method="GET" action="{{ route('anggota.index') }}" class="baris-alat">
            <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari nama / kode / NIK">
            <button type="submit" class="tombol oranye">Cari</button>
            <a href="{{ route('anggota.index') }}" class="tombol putih">Reset</a>
        </form>

        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>L/P</th>
                        <th>Telepon</th>
                        <th>Tanggal Daftar</th>
                        <th>Pinjaman Aktif</th>
                        <th>Terlambat</th>
                        <th>Total Denda</th>
                        <th>Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $anggota)
                        <tr>
                            <td>{{ $anggota->kode }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span class="avatar" style="width:28px;height:28px;font-size:11px">{{ $anggota->initials() }}</span>
                                    <span>{{ $anggota->nama }}</span>
                                </div>
                            </td>
                            <td>{{ $anggota->gender }}</td>
                            <td>{{ $anggota->telepon }}</td>
                            <td>{{ \Carbon\Carbon::parse($anggota->tanggal_daftar)->format('d/m/Y') }}</td>
                            <td>{{ $anggota->jumlah_aktif }}</td>
                            <td>
                                <span class="label-status {{ $anggota->jumlah_terlambat > 0 ? 'merah' : 'abu-lm' }}">
                                    {{ $anggota->jumlah_terlambat }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($anggota->total_denda, 0, ',', '.') }}</td>
                            <td>
                                <span class="label-status {{ $anggota->status === 'aktif' ? 'hijau' : 'abu-lm' }}">
                                    {{ ucfirst($anggota->status) }}
                                </span>
                            </td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('anggota.ubah', $anggota) }}" class="tombol putih kecil">Ubah</a>
                                <form method="POST" action="{{ route('anggota.hapus', $anggota) }}" style="display:inline"
                                      data-konfirmasi="Hapus anggota {{ $anggota->nama }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tombol merah kecil">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10">
                            <x-kosong pesan="Data anggota tidak ditemukan." ikon="🔍" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px">{{ $daftar->links() }}</div>
    </div>

@endsection