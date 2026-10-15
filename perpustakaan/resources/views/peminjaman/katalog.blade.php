@extends('layouts.app')

@section('judul', 'Katalog Buku')
@section('halaman', 'Katalog Buku')

@section('konten')

    <x-kop ikon="🔎" judul="Katalog Buku" deskripsi="Cari buku yang tersedia untuk dipinjam." />

    <div class="kartu animasi-muncul">
        <form method="GET" action="{{ route('katalog') }}" class="baris-alat">
            <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari judul / penulis / kategori">
            <button type="submit" class="tombol oranye">Cari</button>
            <a href="{{ route('katalog') }}" class="tombol putih">Reset</a>
        </form>

        <div class="grid-buku">
            @forelse ($daftar as $buku)
                <div class="kartu-buku">
                    <div class="punggung-buku warna-buku-{{ ($buku->id % 6) + 1 }}">📕</div>
                    <div class="isi-buku">
                        <h4>{{ $buku->judul }}</h4>
                        <div class="penulis">{{ $buku->penulis }}</div>
                        <div class="meta">
                            <span class="label-status abu-lm">{{ $buku->kategori }}</span>
                            @if ($buku->jumlah_tersedia > 0)
                                <span class="label-status hijau">{{ $buku->jumlah_tersedia }} tersedia</span>
                            @else
                                <span class="label-status merah">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <x-kosong pesan="Buku tidak ditemukan." ikon="🔍" />
            @endforelse
        </div>

        <div style="margin-top:18px">{{ $daftar->links() }}</div>
    </div>

@endsection