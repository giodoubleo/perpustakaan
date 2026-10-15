<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Dashboard') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@php
    $daftarMenu = [
        ['cocok' => 'buku.*', 'label' => 'Data Buku'],
        ['cocok' => 'anggota.*', 'label' => 'Data Anggota'],
        ['cocok' => 'peminjaman.*', 'label' => 'Peminjaman'],
        ['cocok' => 'pengembalian.*', 'label' => 'Pengembalian'],
        ['cocok' => 'laporan', 'label' => 'Laporan'],
        ['cocok' => 'user.*', 'label' => 'Manajemen User'],
        ['cocok' => 'pengaturan*', 'label' => 'Pengaturan'],
        ['cocok' => 'katalog', 'label' => 'Katalog Buku'],
        ['cocok' => 'riwayat', 'label' => 'Riwayat Saya'],
    ];

    $halaman = trim(strip_tags((string) $__env->yieldContent('halaman', 'Dashboard')));
    $induk = 'Beranda';

    foreach ($daftarMenu as $menu) {
        if (request()->routeIs($menu['cocok'])) {
            $induk = $menu['label'];
            break;
        }
    }
@endphp
<div class="tata-letak">

    @include('partials.sisi-menu')

    <main class="isi">
        <header class="atas">
            <h1>
                🏠 {{ $induk }}
                @if ($halaman !== $induk)
                    <span style="color:#cbbfa8">/ {{ $halaman }}</span>
                @endif
            </h1>
            <div class="pengguna">
                <span style="font-size:13.5px;color:#7b7367">{{ now()->translatedFormat('l, d F Y') }}</span>
                <span class="peran">{{ auth()->user()->role }}</span>
            </div>
        </header>

        @if (session('sukses'))
            <div class="pesan sukses">{{ session('sukses') }}</div>
        @endif

        @if (session('error'))
            <div class="pesan gagal">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="pesan gagal">
                <b>Gagal menyimpan data:</b>
                <ul style="margin-left:18px">
                    @foreach ($errors->all() as $pesan)
                        <li>{{ $pesan }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('konten')
    </main>

</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>