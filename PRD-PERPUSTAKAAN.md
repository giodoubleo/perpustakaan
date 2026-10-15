# PRD - Perpustakaan SMK BPPI

**Versi:** 3.0 (Implementasi Laravel 10, tampilan kustom SMK BPPI)
**Platform:** Web Application (Laravel 10 + SQLite)
**Nama Aplikasi:** Perpustakaan SMK BPPI
**Status:** Selesai dan sudah diuji

---

## 1. Ringkasan Produk

Aplikasi web untuk mengelola perpustakaan secara menyeluruh: data buku, data
anggota, data petugas (staff), proses peminjaman, proses pengembalian,
perhitungan denda keterlambatan, sampai laporan.

Sistem punya **3 tingkat pengguna** dengan hak akses berbeda: **Admin**,
**Staff**, dan **Anggota**.

Dua hal utama yang jadi prioritas proyek ini:

1. **Mudah dibawa ke komputer lain** - seluruh proyek cukup di-*copy* satu
   folder. Databasenya berupa satu file (`database/database.sqlite`), jadi
   tidak perlu install MySQL/XAMPP di komputer tujuan.
2. **Mudah dipresentasikan** - kode ditulis rapi dan terpisah per bagian
   (Model, Controller, View, Route), nama variabel dan fungsi jelas, dan
   seluruh antarmuka berbahasa Indonesia.

---

## 2. Masalah yang Diselesaikan

| Masalah Saat Ini | Solusi |
|---|---|
| Buku dipinjam dicatat di buku tulis, mudah hilang | Semua transaksi tersimpan digital dan bisa dicari |
| Sulit tahu buku apa yang sedang dipinjam | Dashboard menampilkan status real-time |
| Keterlambatan tidak terkontrol | Denda dihitung otomatis per hari |
| Tidak tahu siapa peminjam paling aktif | Laporan anggota teraktif tersedia |
| Data hilang saat komputer rusak | Database berupa 1 file, tinggal di-*copy* |

---

## 3. Pengguna (Actor) dan Hak Akses

### 3.1 Admin - pemilik sistem, akses penuh

| Menu | Kemampuan |
|---|---|
| Dashboard | Statistik keseluruhan: buku, anggota, peminjaman, terlambat, denda |
| Data Buku | Tambah, ubah, hapus, cari, filter kategori, lihat stok tersedia |
| Data Anggota | Tambah, ubah, hapus, ubah status aktif/nonaktif |
| Peminjaman | Catat peminjaman baru, batalkan, cetak bukti |
| Pengembalian | Proses pengembalian, hitung denda, tandai lunas, cetak |
| Laporan | Rekap transaksi, buku terlaris, anggota teraktif, daftar terlambat |
| Manajemen User | Buat akun Admin/Staff/Anggota, ubah sandi, aktif/nonaktifkan |
| Pengaturan | Batas pinjam, masa pinjam, tarif denda |

### 3.2 Staff - petugas operasional harian

| Menu | Kemampuan |
|---|---|
| Dashboard | Statistik keseluruhan |
| Data Buku | Tambah dan ubah data buku |
| Data Anggota | Tambah dan ubah data anggota |
| Peminjaman | Catat peminjaman baru, cetak bukti |
| Pengembalian | Proses pengembalian + hitung denda + cetak |
| Laporan | Semua laporan (hanya melihat) |

Staff **tidak** bisa menghapus data master, mengelola user, dan mengubah
pengaturan.

### 3.3 Anggota - pengguna pemohon buku

| Menu | Kemampuan |
|---|---|
| Dashboard | Kode anggota, sisa kuota pinjam, buku aktif, total denda |
| Katalog Buku | Mencari dan melihat buku yang tersedia |
| Riwayat Saya | Daftar peminjaman sendiri beserta status dan denda |

Anggota **tidak** bisa mengubah data apa pun.

### 3.4 Matriks Hak Akses

| Fitur | Admin | Staff | Anggota |
|---|---|---|---|
| Dashboard | YA | YA | YA (ringkasan miliknya) |
| Kelola buku | penuh | tambah/ubah | TIDAK |
| Kelola anggota | penuh | tambah/ubah | TIDAK |
| Catat peminjaman | YA | YA | TIDAK |
| Proses pengembalian | YA | YA | TIDAK |
| Laporan | YA | YA | TIDAK |
| Manajemen user | YA | TIDAK | TIDAK |
| Pengaturan | YA | TIDAK | TIDAK |

Pembatasan hak akses dijalankan di sisi server melalui *middleware*
`role:admin,staff` pada file `routes/web.php`, sehingga anggota yang membuka
alamat `/buku` akan mendapat **403 Forbidden**.

---

## 4. Fitur yang Dibangun

### 4.1 Autentikasi

- Login memakai **username + kata sandi**.
- Kata sandi disimpan dengan hash **bcrypt** (bukan teks biasa).
- Pesan error jelas: "Username atau kata sandi salah, atau akun sudah
  dinonaktifkan."
- Tombol **Keluar** mengakhiri sesi.
- Tiga akun demo siap pakai (Bagian 9).

### 4.2 Manajemen Buku

- Field: Kode Buku, ISBN, Judul, Penulis, Penerbit, Tahun, Kategori, Stok,
  Lokasi Rak.
- **Kode buku dibuat otomatis** oleh sistem, contoh `BK-0001`, `BK-0002`.
- Pencarian berdasarkan judul/penulis/ISBN/kode + filter kategori.
- Kolom "Tersedia" dihitung otomatis = Stok - buku yang sedang dipinjam.
- Stok tidak boleh dikurangi di bawah jumlah buku yang sedang dipinjam.

### 4.3 Manajemen Anggota

- Field: Kode Anggota, Nama, NIK, Jenis Kelamin, Telepon, Email, Alamat,
  Tanggal Daftar, Status.
- **Kode anggota dibuat otomatis**, contoh `ANG-0001`.
- Otomatis menampilkan jumlah peminjaman aktif, jumlah buku terlambat, dan
  total denda.

### 4.4 Peminjaman Buku

Alur:

1. Buka menu **Peminjaman - tombol + Catat Peminjaman**.
2. Pilih **Anggota** dan **Buku** (keduanya berupa daftar pilihan).
3. Tanggal pinjam = hari ini, tanggal batas kembali dihitung otomatis dari
   pengaturan (masa pinjam).
4. Klik **Simpan Peminjaman**, transaksi tersimpan dengan nomor otomatis
   `PMJ-0001`.

Validasi otomatis (sistem menolak dan menampilkan alasannya):

| Kondisi | Pesan yang muncul |
|---|---|
| Buku tidak dipilih / tidak ada | "buku wajib diisi." |
| Stok buku habis | "Stok buku tidak tersedia." |
| Anggota tidak aktif | "Anggota berstatus tidak aktif, tidak boleh melakukan peminjaman." |
| Anggota punya buku terlambat | "Anggota memiliki buku yang terlambat. Selesaikan pengembalian terlebih dahulu." |
| Pinjaman melebihi batas | "Anggota sudah memiliki 3 buku. Batas maksimal 3 buku." |

### 4.5 Pengembalian Buku

Alur:

1. Buka menu **Pengembalian**.
2. Klik **Kembalikan** pada transaksi yang dimaksud.
3. Tabel otomatis menampilkan: tanggal pinjam, batas pengembalian, perkiraan
   hari terlambat, dan estimasi denda.
4. Isi **tanggal dikembalikan**, lalu klik **Simpan Pengembalian**.
5. Status transaksi menjadi **Selesai**, hari terlambat dan denda tersimpan.
6. Tombol **Tandai Lunas** menandai denda sudah dibayar.
7. Tombol **Cetak** membuka halaman bukti transaksi yang siap dicetak.

Rumus denda:

```
Hari terlambat = Tanggal kembali - Tanggal batas kembali   (minimal 0)
Denda          = Hari terlambat x Tarif denda per hari
```

Contoh: batas kembali 22 Oktober, buku dikembalikan 24 Oktober, tarif
Rp 1.000 per hari, maka denda = 2 x 1.000 = **Rp 2.000**.

### 4.6 Dashboard

- Enam kartu statistik: Total Buku, Total Anggota, Sedang Dipinjam, Buku
  Terlambat, Denda Belum Lunas, Total Transaksi.
- Dua grafik batang sederhana: peminjaman 7 hari terakhir dan jumlah buku per
  kategori.
- Tabel **5 transaksi terbaru**.
- Untuk anggota: kartu info pribadi, daftar buku yang dipinjam, riwayat
  terakhir.

### 4.7 Laporan

- Filter rentang tanggal.
- **Rekap transaksi** (total, selesai, dipinjam, total hari terlambat, total
  denda, denda belum lunas).
- **Buku terlaris** - judul yang paling sering dipinjam.
- **Anggota teraktif** - anggota dengan peminjaman terbanyak.
- **Daftar buku terlambat**.
- Tombol **Cetak Laporan** untuk mencetak halaman.

### 4.8 Desain Antarmuka (UI/UX)

Tampilan dibangun khusus dengan tema Perpustakaan SMK BPPI, tanpa framework
tampilan pihak ketiga, sehingga aplikasi tetap ringan dan bisa jalan offline.

| Unsur | Spesifikasi |
|---|---|
| Warna latar | Krem hangat `#FBF7EC` |
| Warna utama | Hijau tua `#22494A` |
| Aksen | Oranye bata `#D8862B` |
| Latar rak buku | Cokelat gelap `#2E2620` |
| Bentuk | Tombol pil, kartu sudut 20-28px, bayangan lembut |
| Huruf | Poppins dengan fallback Segoe UI |
| Gerak | Kartu muncul bertahap, tombol terangkat saat hover |

Fitur tampilan:

1. **Beranda publik** untuk pengunjung sebelum login, berisi hero, fitur utama,
   dan penjelasan tiga tingkat pengguna.
2. **Sidebar peran** yang hanya menampilkan menu sesuai hak akses.
3. **Kepala halaman** dengan ikon, judul, deskripsi, dan tombol aksi.
4. **Kartu statistik** dengan ikon berwarna dan angka besar.
5. **Katalog kartu buku** dengan punggung buku berwarna-warni.
6. **Ilustrasi rak buku** dibuat murni dengan CSS tanpa file gambar.
7. **Tabel** dengan header krem, status berbentuk pil, dan baris yang menyala
   saat kursor diarahkan.
8. **Halaman cetak** otomatis menyesuaikan gaya print.

Semua komponen tampilan disimpan terpisah agar mudah diubah:
`resources/views/components/` (kepala halaman, statistik, kosong),
`resources/views/partials/` (sidebar, ilustrasi rak), dan
`public/css/app.css` (seluruh design system).

### 4.9 Pengaturan (Admin)

| Pengaturan | Default | Fungsi |
|---|---|---|
| Batas Peminjaman per Anggota | 3 buku | Maksimal buku aktif sekaligus |
| Masa Peminjaman | 7 hari | Lama pinjam sebelum terlambat |
| Tarif Denda per Hari | Rp 1.000 | Denda per 1 hari keterlambatan |

---

## 5. Struktur Data (Database)

Database memakai **SQLite**, disimpan di **satu file**:

```
perpustakaan/database/database.sqlite
```

### 5.1 Tabel `users` - akun pengguna

| Field | Tipe | Keterangan |
|---|---|---|
| id | Integer | Kunci utama |
| name | String | Nama lengkap |
| username | String | Nama login (unik) |
| email | String | Email (unik) |
| password | String | Kata sandi (hash bcrypt) |
| role | Enum | admin / staff / anggota |
| telepon | String | Nomor kontak |
| status | Enum | aktif / nonaktif |
| anggota_id | Integer | Menghubungkan akun anggota dengan data anggota |

### 5.2 Tabel `buku` - data buku

| Field | Tipe | Keterangan |
|---|---|---|
| id | Integer | Kunci utama |
| kode | String | Kode buku (unik) |
| isbn | String | Nomor ISBN |
| judul | String | Judul buku |
| penulis | String | Nama penulis |
| penerbit | String | Nama penerbit |
| tahun | Year | Tahun terbit |
| kategori | String | Kategori buku |
| stok | Integer | Jumlah eksemplar |
| lokasi | String | Kode rak penyimpanan |

### 5.3 Tabel `anggota` - data anggota perpustakaan

| Field | Tipe | Keterangan |
|---|---|---|
| id | Integer | Kunci utama |
| kode | String | Kode anggota (unik) |
| nama | String | Nama lengkap |
| nik | String | NIK |
| gender | Enum | L / P |
| telepon | String | Nomor HP |
| email | String | Email |
| alamat | Text | Alamat tempat tinggal |
| tanggal_daftar | Date | Tanggal pendaftaran |
| status | Enum | aktif / nonaktif |

### 5.4 Tabel `peminjaman` - transaksi pinjam dan kembali

| Field | Tipe | Keterangan |
|---|---|---|
| id | Integer | Kunci utama |
| kode | String | Nomor transaksi (unik) |
| tanggal_pinjam | Date | Tanggal buku dipinjam |
| tanggal_batas | Date | Batas paling lambat dikembalikan |
| tanggal_kembali | Date | Tanggal benar-benar dikembalikan |
| anggota_id | Integer | Relasi ke tabel anggota |
| buku_id | Integer | Relasi ke tabel buku |
| kasir_id | Integer | User staff/admin yang melayani |
| judul | String | Nama judul saat transaksi dibuat |
| status | Enum | dipinjam / selesai |
| hari_terlambat | Integer | Jumlah hari keterlambatan |
| denda | Integer | Nominal denda |
| denda_lunas | Boolean | Denda sudah dibayar atau belum |

### 5.5 Tabel `pengaturan` - konfigurasi sistem

| Field | Tipe | Default |
|---|---|---|
| batas_pinjam | Integer | 3 |
| masa_pinjam | Integer | 7 |
| tarif_denda | Integer | 1000 |

### 5.6 Relasi Antar Tabel

```
anggota (1) -----< (banyak) peminjaman ----- (1) buku
peminjaman (banyak) ----- (1) users  [kasir]
anggota (1) ----- (1) users [akun anggota]
```

---

## 6. Struktur Folder Proyek

```
perpustakaan/
|- app/
|  |- Http/Controllers/    -> logika setiap halaman (PHP)
|  |- Http/Middleware/     -> RoleMiddleware (pembatas hak akses)
|  |- Models/              -> User, Buku, Anggota, Peminjaman, Pengaturan
|  `- Support/Kode.php     -> pembuat kode otomatis (BK-0001, PMJ-0001)
|- database/
|  |- migrations/          -> blueprint tabel
|  |- seeders/             -> data contoh awal
|  `- database.sqlite      -> BERKAS DATABASE
|- resources/views/         -> halaman Blade (tampilan)
|- public/css/app.css       -> gaya tampilan
|- public/js/app.js         -> konfirmasi sebelum hapus
|- routes/web.php           -> daftar URL + hak akses
|- tests/Feature/           -> pengujian otomatis
`- .env                     -> konfigurasi (jenis database, nama aplikasi)
```

---

## 7. Kebutuhan Non-Fungsional

| Aspek | Keterangan |
|---|---|
| Portabilitas | Copy 1 folder + 1 file database, tanpa install database server |
| Performa | Muat cepat untuk sekitar 10.000 transaksi |
| Keamanan | Password di-hash bcrypt, CSRF token, pembatasan menu per role |
| Usability | Antarmuka Bahasa Indonesia, tema Perpustakaan SMK BPPI, tombol berlabel jelas |
| Maintainability | Pemisahan Model-Controller-View, komponen tampilan terpisah, nama fungsi deskriptif |
| Kualitas | 10 pengujian otomatis (`php artisan test`) |

---

## 8. Alur Utama (Use Case)

```
[Anggota] --cari buku----------> [Katalog Buku]
[Anggota] --minta pinjam-------> [Staff] --cek validasi--> [Catat Peminjaman]
[Anggota] --kembalikan buku----> [Staff] --cek terlambat-> [Hitung Denda] --> [Cetak Bukti]
[Admin]  --kelola-------------> [Buku / Anggota / User / Pengaturan]
[Admin]  --backup---------------> [copy database.sqlite ke komputer lain]
```

---

## 9. Akun Demo

| Peran | Username | Kata Sandi | Halaman yang Terlihat |
|---|---|---|---|
| Admin | `admin` | `admin123` | Semua menu |
| Staff | `staff` | `staff123` | Semua menu kecuali Manajemen User dan Pengaturan |
| Anggota | `anggota1` | `anggota123` | Dashboard, Katalog, Riwayat |

Data awal sudah berisi 8 buku, 3 anggota, dan 5 transaksi contoh.

---

## 10. Kriteria Penerimaan

- [x] Login berhasil untuk 3 role, menu menyesuaikan role
- [x] Admin dan staff dapat menambah, mengubah, menghapus buku
- [x] Admin dan staff dapat menambah, mengubah, status anggota
- [x] Kode buku, kode anggota, dan nomor transaksi dibuat otomatis
- [x] Peminjaman tercatat, stok tersedia berkurang, tanggal kembali otomatis
- [x] Validasi menolak peminjaman (stok habis, kuota penuh, terlambat, nonaktif)
- [x] Pengembalian mencatat tanggal, menghitung denda, menandai lunas
- [x] Bukti transaksi dapat dicetak
- [x] Laporan rekap, buku terlaris, anggota teraktif, terlambat tersedia
- [x] Admin dapat mengatur batas pinjam, masa pinjam, tarif denda
- [x] Beranda publik tampil untuk pengunjung sebelum login
- [x] Tampilan konsisten bertema Perpustakaan SMK BPPI di semua halaman
- [x] 10 pengujian otomatis lulus
- [x] Berjalan di komputer lain cukup dengan copy folder + file database

---

*PRD untuk keperluan Akademik / Tugas Besar Sistem Informasi.*