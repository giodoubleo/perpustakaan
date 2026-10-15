@extends('layouts.app')

@section('judul', $user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna')
@section('halaman', $user->exists ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('konten')

    <x-kop ikon="{{ $user->exists ? '✏️' : '🔐' }}"
           :judul="$user->exists ? 'Ubah Akun Pengguna' : 'Tambah Akun Pengguna'"
           deskripsi="Hak akses menentukan menu yang tampil saat pengguna masuk.">
        <x-slot:aksi>
            <a href="{{ route('user.index') }}" class="tombol putih">Kembali ke daftar</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <form method="POST" action="{{ $user->exists ? route('user.update', $user) : route('user.simpan') }}">
            @csrf
            @if ($user->exists) @method('PUT') @endif

            <div class="baris-form">
                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                           @disabled($user->exists) required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}">
                </div>
                <div>
                    <label>Kata Sandi {{ $user->exists ? '(kosongkan jika tidak diubah)' : '' }}</label>
                    <input type="password" name="password" @required(! $user->exists) minlength="6">
                </div>
                <div>
                    <label>Hak Akses</label>
                    <select name="role" required>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                        <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                        <option value="anggota" @selected(old('role', $user->role) === 'anggota')>Anggota</option>
                    </select>
                </div>
                <div>
                    <label>Nomor Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}">
                </div>
                <div>
                    <label>Status Akun</label>
                    <select name="status" required>
                        <option value="aktif" @selected(old('status', $user->status ?? 'aktif') === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $user->status) === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label>Tautkan ke Data Anggota</label>
                    <select name="anggota_id">
                        <option value="">- Tidak ditautkan -</option>
                        @foreach (\App\Models\Anggota::orderBy('kode')->get() as $item)
                            <option value="{{ $item->id }}" @selected(old('anggota_id', $user->anggota_id) === $item->id)>
                                {{ $item->kode }} - {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="tombol oranye">Simpan</button>
                <a href="{{ route('user.index') }}" class="tombol putih">Batal</a>
            </div>
        </form>
    </div>

@endsection