# TATACARA MENJALANKAN APLIKASI PERPUSTAKAAN SMK BPPI

Dokumen ini berisi cara membuka aplikasi Perpustakaan SMK BPPI, termasuk cara
memindahkan ke komputer lain.

---

## A. CARA CEPAT (3 Perintah)

Pastikan PHP dan Composer sudah terpasang, lalu buka **Command Prompt / PowerShell**
di dalam folder `perpustakaan`, lalu jalankan:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Terakhir, buka browser dan akses:

```
http://localhost:8000
```

Kalau `composer install` sudah pernah dijalankan di komputer ini, perintah itu
boleh dilewati.

---

## B. CARA BUKA FILE (LANGKAH LANGKAH)

### Langkah 1 - Pastikan PHP terpasang

Buka **Command Prompt**, ketik:

```bash
php -v
```

Kalau muncul versi PHP (contoh `PHP 8.1.10`), berarti sudah siap.
Kalau muncul pesan "`php` bukan dikenali sebagai perintah", install PHP 8.1
lebih baru (unduh dari php.net atau lewat XAMPP/Laragon), lalu tambahkan folder
PHP ke Windows PATH.

### Langkah 2 - Pastikan Composer terpasang

Ketik:

```bash
composer -V
```

Kalau belum ada, unduh Composer dari getcomposer.org/Composer-Setup.exe.

### Langkah 3 - Masuk ke folder proyek

```bash
cd C:\Users\Gio\Desktop\arulll\perpustakaan
```

(sesuaikan lokasi folder di komputer Anda)

### Langkah 4 - Pasang paket library

```bash
composer install
```

Kalau folder `vendor` sudah ikut tersalin bersama folder proyek, perintah ini
boleh dilewati.

### Langkah 5 - Buat kunci keamanan aplikasi

```bash
php artisan key:generate
```

### Langkah 6 - Siapkan database + data contoh

```bash
php artisan migrate --seed
```

Perintah ini membuat file `database/database.sqlite`, membuat semua tabel, lalu
mengisi data contoh (8 buku, 3 anggota, 5 transaksi).

Kalau ingin mengulang dari awal:

```bash
php artisan migrate:fresh --seed
```

### Langkah 7 - Jalankan server

```bash
php artisan serve
```

Akan muncul tulisan:

```
Server running on [http://127.0.0.1:8000]
```

### Langkah 8 - Buka di browser

Klik tautan atau buka browser dan ketik:

```
http://localhost:8000
```

### Langkah 9 - Login

| Peran | Username | Kata Sandi |
|---|---|---|
| Admin | admin | admin123 |
| Staff | staff | staff123 |
| Anggota | anggota1 | anggota123 |

### Langkah 10 - Menghentikan server

Kembali ke jendela Command Prompt, tekan **Ctrl + C**.

---

## C. CARA MEMINDAHKAN KE KOMPUTER LAIN

Metode paling aman: copy **seluruh folder** `perpustakaan`.

1. Tutup server di komputer lama (tekan `Ctrl + C`).
2. Copy folder `perpustakaan` ke flashdisk (termasuk folder `vendor`).
3. Paste di komputer tujuan, misalnya di `D:\perpustakaan`.
4. Di komputer tujuan, buka Command Prompt lalu:

```bash
cd D:\perpustakaan
php artisan serve
```

5. Buka `http://localhost:8000`.

Syarat di komputer tujuan:

- PHP 8.1 atau lebih baru terpasang
- Extension `pdo_sqlite` aktif
- Browser (Chrome/Edge/Firefox)

Tidak perlu MySQL, tidak perlu XAMPP, tidak perlu internet.

### Kalau `vendor` tidak ikut ter-copy

Jalankan sekali di komputer tujuan:

```bash
composer install
```

Butuh koneksi internet untuk mengunduh paket.

### Cara memindahkan datanya

Seluruh data ada di **satu file**:

```
perpustakaan/database/database.sqlite
```

Untuk memindahkan data saja:

1. Copy file `database/database.sqlite` ke komputer tujuan (timpa yang lama).
2. Jalankan `php artisan serve` di komputer tujuan.

Untuk membuat salinan cadangan, cukup copy file tersebut kapan saja.

---

## D. PERINTAH YANG SERING DIPAKAI

| Perintah | Fungsi |
|---|---|
| `php artisan serve` | Menjalankan server lokal |
| `php artisan migrate --seed` | Membuat tabel + data contoh |
| `php artisan migrate:fresh --seed` | Mengulang database dari nol + data contoh |
| `php artisan test` | Menjalankan 10 pengujian otomatis |
| `php artisan route:list` | Melihat daftar alamat URL |
| `php artisan db:show` | Melihat informasi database |

---

## E. KALAU MUNCUL MASALAH

| Gejala | Penyebab | Solusi |
|---|---|---|
| `composer` bukan perintah | Composer belum terpasang | Install Composer |
| `php` bukan perintah | PHP belum ada di PATH | Install PHP / tambah ke PATH |
| `could not find driver` | Extension pdo_sqlite mati | Aktifkan `extension=pdo_sqlite` di php.ini |
| Halaman kosong / 500 | Kunci keamanan belum dibuat | Jalankan `php artisan key:generate` |
| `no such table` | Database belum dibuat | Jalankan `php artisan migrate --seed` |
| Port 8000 sudah dipakai | Server lain sedang jalan | Jalankan `php artisan serve --port=8080` lalu buka `http://localhost:8080` |
| Login selalu gagal | Password berbeda | Gunakan tabel akun demo di atas |

---

*Seluruh perintah dijalankan dari dalam folder `perpustakaan`.*