<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="halaman-publik">

    <span class="blob blob-hijau" style="width:420px;height:380px;top:-120px;right:-60px"></span>
    <span class="blob blob-oranye" style="width:300px;height:280px;bottom:60px;left:-110px"></span>

    <nav class="nav-publik">
        <div class="merek">
            <span class="merek-ikon">📚</span>
            <span>Perpustakaan <small>SMK BPPI</small></span>
        </div>

        <div class="tautan">
            <a href="#tentang">Tentang</a>
            <a href="#fitur">Fitur</a>
            <a href="#alur">Alur Peminjaman</a>
            <a href="#hak-akses">Hak Akses</a>
        </div>

        <div class="aksi">
            <a href="{{ route('login') }}" class="tombol oranye">Masuk</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-teks">
            <h1>Perpustakaan<br>online untuk semua</h1>
            <p>
                Kelola buku, anggota, peminjaman, dan pengembalian dalam satu
                tempat. Denda keterlambatan dihitung otomatis, laporan tersedia
                kapan pun, dan hak akses tiap pengguna terpisah dengan rapi.
            </p>
            <div class="hero-aksi">
                <a href="{{ route('login') }}" class="tombol oranye">Mulai Sekarang</a>
                <a href="#alur" class="tombol putih">Lihat Alur Kerja</a>
            </div>
        </div>

        <div style="position:relative;display:flex;justify-content:center">
            <span class="blob blob-krem" style="width:560px;height:470px;top:-40px;left:-30px"></span>
            <div style="position:relative;z-index:2;width:100%;max-width:560px">
                @include('partials.rak-buku', ['kelas' => ''])
            </div>
        </div>
    </section>

    <section class="hero-fitur" id="fitur">
        <div class="kartu-fitur">
            <span class="kotak-ikon">📤</span>
            <h3>Peminjaman Cepat</h3>
            <p>Pilih anggota dan buku, sistem otomatis mencatat nomor transaksi dan tanggal batas pengembalian.</p>
        </div>
        <div class="kartu-fitur">
            <span class="kotak-ikon ikon-kuning">🧮</span>
            <h3>Denda Otomatis</h3>
            <p>Keterlambatan dihitung per hari dan tarif denda mengikuti pengaturan yang dapat diubah admin.</p>
        </div>
        <div class="kartu-fitur">
            <span class="kotak-ikon ikon-hijau">📈</span>
            <h3>Laporan Real-time</h3>
            <p>Buku terlaris, anggota teraktif, daftar terlambat, dan rekap transaksi siap kapan pun.</p>
        </div>
    </section>

    <section class="hero-fitur" id="alur" style="padding-top:0">
        <div class="kartu-fitur">
            <span class="kotak-ikon ikon-abu">1️⃣</span>
            <h3>Admin</h3>
            <p>Mengelola buku, anggota, akun pengguna, pengaturan batas pinjam, masa pinjam, dan tarif denda.</p>
        </div>
        <div class="kartu-fitur">
            <span class="kotak-ikon ikon-biru">2️⃣</span>
            <h3>Staff</h3>
            <p>Melayani peminjaman, memproses pengembalian, menghitung denda, dan mencetak bukti transaksi.</p>
        </div>
        <div class="kartu-fitur">
            <span class="kotak-ikon ikon-ungu">3️⃣</span>
            <h3>Anggota</h3>
            <p>Mencari buku pada katalog, melihat riwayat peminjaman sendiri, dan memantau denda yang harus dibayar.</p>
        </div>
    </section>

    <div class="gelombang-bawah"></div>

    <div style="position:relative;z-index:2;text-align:center;padding:0 60px 40px">
        <a href="{{ route('login') }}" class="tombol oranye">Masuk ke Aplikasi</a>
    </div>

</div>
</body>
</html>