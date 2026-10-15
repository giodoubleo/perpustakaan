@extends('layouts.app')

@section('judul', 'Peminjaman Baru')
@section('halaman', 'Catat Peminjaman')

@section('konten')

    <x-kop ikon="📤" judul="Catat Peminjaman" deskripsi="Pilih anggota dan buku, sisanya diisi sistem secara otomatis.">
        <x-slot:aksi>
            <a href="{{ route('peminjaman.index') }}" class="tombol putih">Kembali ke daftar</a>
        </x-slot:aksi>
    </x-kop>

    <div class="statistik">
        <x-statistik label="Masa Peminjaman" :nilai="$pengaturan->masa_pinjam . ' hari'" ikon="🗓️" warna="biru" />
        <x-statistik label="Batas Peminjaman" :nilai="$pengaturan->batas_pinjam . ' buku'" ikon="📚" warna="hijau" />
        <x-statistik label="Tarif Denda" :nilai="'Rp ' . number_format($pengaturan->tarif_denda, 0, ',', '.') . ' / hari'" ikon="🧮" warna="kuning" />
        <x-statistik label="Buku Tersedia" :nilai="$bukuTersedia->count() . ' judul'" ikon="✅" warna="ungu" />
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">✍️ Data Peminjaman</div>

        <form method="POST" action="{{ route('peminjaman.simpan') }}">
            @csrf

            <div class="baris-form">
                <div>
                    <label>Pilih Anggota</label>
                    <select name="anggota_id" required>
                        <option value="">-- Pilih anggota --</option>
                        @foreach ($anggotaAktif as $item)
                            <option value="{{ $item->id }}">{{ $item->kode }} - {{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Pilih Buku</label>
                    <select name="buku_id" required>
                        <option value="">-- Pilih buku --</option>
                        @foreach ($bukuTersedia as $buku)
                            <option value="{{ $buku->id }}">{{ $buku->kode }} - {{ $buku->judul }} (sisa {{ $buku->tersedia() }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tanggal Pinjam</label>
                    <input type="date" value="{{ now()->format('Y-m-d') }}" disabled>
                </div>
                <div>
                    <label>Batas Kembali</label>
                    <input type="date" value="{{ now()->addDays($pengaturan->masa_pinjam)->format('Y-m-d') }}" disabled>
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="tombol oranye">Simpan Peminjaman</button>
                <a href="{{ route('peminjaman.index') }}" class="tombol putih">Batal</a>
            </div>
        </form>
    </div>

@endsection