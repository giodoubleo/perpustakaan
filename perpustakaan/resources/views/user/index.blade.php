@extends('layouts.app')

@section('judul', 'Manajemen Pengguna')
@section('halaman', 'Manajemen Pengguna')

@section('konten')

    <x-kop ikon="🔐" judul="Manajemen Pengguna" deskripsi="Kelola akun admin, staff, dan anggota beserta status aktifnya.">
        <x-slot:aksi>
            <a href="{{ route('user.tambah') }}" class="tombol oranye">+ Tambah Pengguna</a>
        </x-slot:aksi>
    </x-kop>

    <div class="kartu animasi-muncul">
        <div class="bungkus-tabel">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Hak Akses</th>
                        <th>Telepon</th>
                        <th>Anggota Terhubung</th>
                        <th>Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span class="avatar" style="width:30px;height:30px;font-size:11px">{{ $user->initials() }}</span>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td><span class="peran">{{ $user->role }}</span></td>
                            <td>{{ $user->telepon ?? '-' }}</td>
                            <td>{{ $user->anggota?->kode ?? '-' }}</td>
                            <td>
                                <span class="label-status {{ $user->status === 'aktif' ? 'hijau' : 'abu-lm' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td style="text-align:right;white-space:nowrap">
                                <a href="{{ route('user.ubah', $user) }}" class="tombol putih kecil">Ubah</a>
                                <form method="POST" action="{{ route('user.hapus', $user) }}" style="display:inline"
                                      data-konfirmasi="Hapus akun {{ $user->username }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tombol merah kecil">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-kosong pesan="Belum ada pengguna." ikon="👤" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection