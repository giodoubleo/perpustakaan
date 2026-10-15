@extends('layouts.app')

@section('judul', 'Data Buku')
@section('halaman', 'Data Buku')

@section('konten')

    <x-kop ikon="📚" judul="Data Buku" deskripsi="Kelola koleksi buku, stok, dan lokasi rak penyimpanan.">
        <x-slot:aksi>
            <a href="{{ route('buku.tambah') }}" class="tombol oranye">+ Tambah Buku</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <form method="GET" action="{{ route('buku.index') }}" class="baris-alat">
            <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari judul / penulis / ISBN">
            <select name="kategori">
                <option value="">Semua Kategori</option>
                @foreach ($kategoriList as $item)
                    <option value="{{ $item }}" @selected($kategori === $item)>{{ $item }}</option>
                @endforeach
            </select>
            <button type="submit" class="tombol oranye">Cari</button>
            <a href="{{ route('buku.index') }}" class="tombol putih">Reset</a>
        </form>

        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Rak</th>
                        <th>Stok</th>
                        <th>Dipinjam</th>
                        <th>Tersedia</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $buku)
                        <tr>
                            <td>{{ $buku->kode }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            <td><span class="label-status abu-lm">{{ $buku->kategori }}</span></td>
                            <td>{{ $buku->lokasi }}</td>
                            <td>{{ $buku->stok }}</td>
                            <td>{{ $buku->jumlah_dipinjam }}</td>
                            <td>
                                <span class="label-status {{ $buku->jumlah_tersedia > 0 ? 'hijau' : 'merah' }}">
                                    {{ $buku->jumlah_tersedia }}
                                </span>
                            </td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('buku.ubah', $buku) }}" class="tombol putih kecil">Ubah</a>
                                <form method="POST" action="{{ route('buku.hapus', $buku) }}" style="display:inline"
                                      data-konfirmasi="Hapus buku {{ $buku->judul }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tombol merah kecil">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="kosong">
                            <x-kosong pesan="Data buku tidak ditemukan." ikon="🔍" />
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px">{{ $daftar->links() }}</div>
    </div>

@endsection