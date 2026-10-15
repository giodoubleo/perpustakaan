@extends('layouts.app')

@section('judul', $anggota->exists ? 'Ubah Anggota' : 'Tambah Anggota')
@section('halaman', $anggota->exists ? 'Ubah Anggota' : 'Tambah Anggota')

@section('konten')

    <x-kop ikon="{{ $anggota->exists ? '✏️' : '👥' }}"
           :judul="$anggota->exists ? 'Ubah Data Anggota' : 'Tambah Anggota Baru'"
           deskripsi="Anggota berstatus nonaktif tidak boleh melakukan peminjaman.">
        <x-slot:aksi>
            <a href="{{ route('anggota.index') }}" class="tombol putih">Kembali ke daftar</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <form method="POST" action="{{ $anggota->exists ? route('anggota.update', $anggota) : route('anggota.simpan') }}">
            @csrf
            @if ($anggota->exists) @method('PUT') @endif

            <div class="baris-form">
                <div>
                    <label>Kode Anggota</label>
                    <input type="text" name="kode" value="{{ old('kode', $anggota->kode) }}"
                           @disabled($anggota->exists) placeholder="otomatis oleh sistem">
                </div>
                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $anggota->nama) }}" required>
                </div>
                <div>
                    <label>NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $anggota->nik) }}">
                </div>
                <div>
                    <label>Jenis Kelamin</label>
                    <select name="gender">
                        <option value="">- Pilih -</option>
                        <option value="L" @selected(old('gender', $anggota->gender) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('gender', $anggota->gender) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label>Nomor Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $anggota->telepon) }}">
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $anggota->email) }}">
                </div>
                <div>
                    <label>Tanggal Daftar</label>
                    <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar', $anggota->tanggal_daftar ?? now()->format('Y-m-d')) }}" required>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status" required>
                        <option value="aktif" @selected(old('status', $anggota->status ?? 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $anggota->status) === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div style="grid-column: 1 / -1">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="2">{{ old('alamat', $anggota->alamat) }}</textarea>
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="tombol oranye">Simpan</button>
                <a href="{{ route('anggota.index') }}" class="tombol putih">Batal</a>
            </div>
        </form>
    </div>

@endsection