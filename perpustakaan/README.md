# Perpustakaan SMK BPPI

Aplikasi web untuk mengelola perpustakaan: data buku, data anggota, peminjaman,
pengembalian, perhitungan denda keterlambatan, sampai laporan.

Dibangun dengan **Laravel 10**, database **SQLite**, dan desain antarmuka
kustom bertema Perpustakaan SMK BPPI (krem hangat, hijau tua, oranye bata,
bentuk pil dan sudut membulat).

---

## Kebutuhan Sistem

| Kebutuhan | Keterangan |
|---|---|
| PHP | 8.1 atau lebih baru (8.2/8.3 juga bisa) |
| Composer | Untuk memasang dependensi paket |
| Browser | Chrome, Edge, atau Firefox |
| XAMPP/Laragon | Tidak wajib, boleh dipakai |

Ekstensi PHP yang dipakai: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`.

---

## Cara Menjalankan (Singkat)

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka browser:

```
http://localhost:8000
```

Alur halaman:

- Tamu -> `http://localhost:8000` melihat halaman beranda publik.
- Sudah login -> langsung masuk ke dashboard.

Detail langkah demi langkah ada di file `TATACARA-BUKA.md`.

---

## Akun Demo

| Peran | Username | Kata Sandi |
|---|---|---|
| Admin | `admin` | `admin123` |
| Staff | `staff` | `staff123` |
| Anggota | `anggota1` | `anggota123` |

---

## Lokasi Database

Database berupa **satu file**:

```
perpustakaan/database/database.sqlite
```

Tabel: `users`, `buku`, `anggota`, `peminjaman`, `pengaturan`.

Untuk menyalin data ke komputer lain, cukup copy file `database.sqlite`.

---

## Daftar Menu

| Peran | Menu yang terlihat |
|---|---|
| Admin | Dashboard, Buku, Anggota, Peminjaman, Pengembalian, Laporan, Manajemen User, Pengaturan |
| Staff | Dashboard, Buku, Anggota, Peminjaman, Pengembalian, Laporan |
| Anggota | Dashboard, Katalog Buku, Riwayat Saya |

---

## Desain Antarmuka (UI/UX)

| Unsur | Nilai |
|---|---|
| Latar | Krem hangat `#FBF7EC` |
| Warna utama | Hijau tua `#22494A` |
| Warna aksen | Oranye bata `#D8862B` |
| Latar rak buku | Cokelat gelap `#2E2620` |
| Bentuk | Pil (pill), sudut 20-28px, kartu bayangan lembut |
| Font | Poppins dengan fallback Segoe UI |
| Gerak | Kartu muncul bertahap, tombol terangkat saat hover, grafik batang membulat |

Berkas tampilan:

| Berkas | Isi |
|---|---|
| `public/css/app.css` | Seluruh design system (warna, komponen, animasi) |
| `public/js/app.js` | Konfirmasi sebelum menghapus data |
| `resources/views/landing.blade.php` | Beranda publik + ilustrasi rak buku |
| `resources/views/partials/rak-buku.blade.php` | Ilustrasi rak buku warna-warni (murni CSS) |
| `resources/views/partials/sisi-menu.blade.php` | Sidebar yang menyesuaikan peran |
| `resources/views/components/kop.blade.php` | Kepala halaman dengan ikon dan tombol aksi |
| `resources/views/components/statistik.blade.php` | Kartu statistik |
| `resources/views/components/kosong.blade.php` | Tampilan saat data kosong |
| `resources/views/vendor/pagination/default.blade.php` | Tampilan pagination |

---

## Perintah Bantu

```bash
php artisan serve                   # jalankan server lokal
php artisan migrate:fresh --seed   # buat ulang database + data contoh
php artisan test                    # jalankan 10 pengujian otomatis
```

---

## Struktur Folder Penting

```
perpustakaan/
|- app/Http/Controllers/     -> logika setiap halaman
|- app/Http/Middleware/      -> pembatas hak akses per peran
|- app/Models/               -> struktur tabel + perhitungan denda
|- app/Support/Kode.php      -> kode otomatis (BK-0001, ANG-0001, PMJ-0001)
|- database/migrations/      -> blueprint tabel
|- database/seeders/         -> data contoh awal
|- database/database.sqlite  -> BERKAS DATABASE
|- resources/views/          -> halaman Blade
|- resources/views/components -> komponen tampilan yang dipakai ulang
|- public/css/app.css        -> design system
|- routes/web.php            -> daftar URL + hak akses
`- tests/Feature/            -> pengujian otomatis
```