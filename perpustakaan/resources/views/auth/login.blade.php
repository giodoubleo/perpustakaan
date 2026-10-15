<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="login">

    <section class="login-kiri">
        <span class="blob blob-hijau" style="width:320px;height:300px;top:-90px;left:-90px"></span>
        <span class="blob blob-oranye" style="width:220px;height:210px;bottom:20px;right:-60px"></span>

        <a href="{{ route('beranda') }}" class="merek" style="position:relative;z-index:2">
            <span class="merek-ikon">📚</span>
            <span>Perpustakaan <small>SMK BPPI</small></span>
        </a>

        <h2>Selamat datang<br>di perpustakaan kami</h2>
        <p>
            Masuk untuk meminjam buku, mengembalikan buku, atau melihat riwayat
            peminjaman Anda.
        </p>

        <div style="position:relative;z-index:2;max-width:400px">
            @include('partials.rak-buku')
        </div>
    </section>

    <section class="login-kanan">
        <div class="login-kotak">
            <h3>Masuk Akun</h3>
            <p class="sub">Gunakan username dan kata sandi yang terdaftar.</p>

            @if (session('error'))
                <div class="pesan gagal">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.proses') }}">
                @csrf

                <div style="margin-bottom:16px">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus>
                </div>

                <div style="margin-bottom:22px">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="tombol oranye" style="width:100%;justify-content:center">
                    Masuk
                </button>
            </form>

            <div class="info-akun">
                <b>Akun demo</b><br>
                Admin &nbsp;&nbsp;: admin / admin123<br>
                Staff &nbsp;&nbsp;: staff / staff123<br>
                Anggota: anggota1 / anggota123
            </div>

            <p style="text-align:center;margin-top:18px;font-size:13px">
                <a href="{{ route('beranda') }}">Kembali ke beranda</a>
            </p>
        </div>
    </section>

</div>
</body>
</html>