@extends('layouts.app')

@section('judul', $buku->exists ? 'Ubah Buku' : 'Tambah Buku')
@section('halaman', $buku->exists ? 'Ubah Buku' : 'Tambah Buku')

@section('konten')

    <x-kop ikon="{{ $buku->exists ? '✏️' : '📚' }}"
           :judul="$buku->exists ? 'Ubah Data Buku' : 'Tambah Buku Baru'"
           deskripsi="Kode buku dibuat otomatis oleh sistem, contoh BK-0001.">
        <x-slot:aksi>
            <a href="{{ route('buku.index') }}" class="tombol putih">Kembali ke daftar</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <form method="POST" action="{{ $buku->exists ? route('buku.update', $buku) : route('buku.simpan') }}">
            @csrf
            @if ($buku->exists) @method('PUT') @endif

            <div class="baris-form">
                <div>
                    <label>Kode Buku</label>
                    <input type="text" name="kode" value="{{ old('kode', $buku->kode) }}"
                           @disabled($buku->exists) placeholder="otomatis oleh sistem">
                </div>
                <div>
                    <label>ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}">
                </div>
                <div>
                    <label>Judul Buku</label>
                    <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" required>
                </div>
                <div>
                    <label>Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" required>
                </div>
                <div>
                    <label>Penerbit</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}">
                </div>
                <div>
                    <label>Tahun Terbit</label>
                    <input type="number" name="tahun" value="{{ old('tahun', $buku->tahun) }}" min="1900" max="2100">
                </div>
                <div>
                    <label>Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $buku->kategori) }}" required>
                </div>
                <div>
                    <label>Jumlah Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $buku->stok ?? 1) }}" min="1" required>
                </div>
                <div>
                    <label>Lokasi Rak</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $buku->lokasi) }}" placeholder="Contoh: Rak A-1">
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="tombol oranye">Simpan</button>
                <a href="{{ route('buku.index') }}" class="tombol putih">Batal</a>
            </div>
        </form>
    </div>

@endsection