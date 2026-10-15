<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Pengaturan;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Pengaturan::create(['batas_pinjam' => 3, 'masa_pinjam' => 7, 'tarif_denda' => 1000]);

        $buku = [
            ['kode' => 'BK-0001', 'isbn' => '978-979-1234-567-8', 'judul' => 'Dasar-Dasar Pemrograman', 'penulis' => 'Abdul Kadir', 'penerbit' => 'Andi Offset', 'tahun' => 2021, 'kategori' => 'Komputer', 'stok' => 4, 'lokasi' => 'Rak A-1'],
            ['kode' => 'BK-0002', 'isbn' => '978-979-4321-876-5', 'judul' => 'Sistem Informasi Manajemen', 'penulis' => 'Jogiyanto', 'penerbit' => 'ANDI', 'tahun' => 2020, 'kategori' => 'Komputer', 'stok' => 3, 'lokasi' => 'Rak A-2'],
            ['kode' => 'BK-0003', 'isbn' => '978-979-5566-111-2', 'judul' => 'Analisis dan Perancangan Sistem', 'penulis' => 'Raja Irman', 'penerbit' => 'Informatika', 'tahun' => 2022, 'kategori' => 'Komputer', 'stok' => 3, 'lokasi' => 'Rak A-2'],
            ['kode' => 'BK-0004', 'isbn' => '978-602-0000-111-1', 'judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'penerbit' => 'B071 Publishing', 'tahun' => 2019, 'kategori' => 'Komputer', 'stok' => 2, 'lokasi' => 'Rak A-3'],
            ['kode' => 'BK-0005', 'isbn' => '978-979-7762-888-4', 'judul' => 'Kalkulus Dasar', 'penulis' => 'Thomas G. GunCalc', 'penerbit' => 'Erlangga', 'tahun' => 2018, 'kategori' => 'Matematika', 'stok' => 5, 'lokasi' => 'Rak B-1'],
            ['kode' => 'BK-0006', 'isbn' => '979-10-1234-56-7', 'judul' => 'Pengantar Ekonomi', 'penulis' => 'D. Najafi', 'penerbit' => 'Ghafara', 'tahun' => 2021, 'kategori' => 'Ekonomi', 'stok' => 4, 'lokasi' => 'Rak C-1'],
            ['kode' => 'BK-0007', 'isbn' => '978-979-0999-333-2', 'judul' => 'Sejarah Indonesia Modern', 'penulis' => 'Umar F. Al-Attar', 'penerbit' => 'LKiS', 'tahun' => 2017, 'kategori' => 'Sastra', 'stok' => 3, 'lokasi' => 'Rak D-2'],
            ['kode' => 'BK-0008', 'isbn' => '978-979-1234-000-9', 'judul' => 'Cerita Pendek Nusantara', 'penulis' => 'Danarto', 'penerbit' => 'Diksi Insan Mulia', 'tahun' => 2020, 'kategori' => 'Sastra', 'stok' => 6, 'lokasi' => 'Rak D-3'],
        ];

        foreach ($buku as $data) {
            Buku::create($data);
        }

        $anggota = [
            ['kode' => 'ANG-0001', 'nama' => 'Budi Santoso', 'nik' => '3273010101990001', 'gender' => 'L', 'telepon' => '0812-1000-0001', 'email' => 'budi@smkbppi.test', 'alamat' => 'Jl. Merdeka No. 10', 'tanggal_daftar' => '2024-01-15', 'status' => 'aktif'],
            ['kode' => 'ANG-0002', 'nama' => 'Siti Nurhaliza', 'nik' => '3273010202000002', 'gender' => 'P', 'telepon' => '0812-1000-0002', 'email' => 'siti@smkbppi.test', 'alamat' => 'Jl. Rakyat No. 5', 'tanggal_daftar' => '2024-02-20', 'status' => 'aktif'],
            ['kode' => 'ANG-0003', 'nama' => 'Andi Pratama', 'nik' => '3273010301990003', 'gender' => 'L', 'telepon' => '0812-1000-0003', 'email' => 'andi@smkbppi.test', 'alamat' => 'Jl. Asia Afrika No. 8', 'tanggal_daftar' => '2024-03-05', 'status' => 'aktif'],
        ];

        foreach ($anggota as $data) {
            Anggota::create($data);
        }

        User::create(['name' => 'Administrator', 'username' => 'admin', 'email' => 'admin@smkbppi.test', 'password' => 'admin123', 'role' => 'admin', 'telepon' => '0812-1000-1000', 'status' => 'aktif']);
        User::create(['name' => 'Petugas Perpustakaan', 'username' => 'staff', 'email' => 'staff@smkbppi.test', 'password' => 'staff123', 'role' => 'staff', 'telepon' => '0812-1000-1001', 'status' => 'aktif']);
        User::create(['name' => 'Budi Santoso', 'username' => 'anggota1', 'email' => 'budi@smkbppi.test', 'password' => 'anggota123', 'role' => 'anggota', 'telepon' => '0812-1000-0001', 'status' => 'aktif', 'anggota_id' => 1]);

        $adminId = User::where('username', 'admin')->value('id');
        $staffId = User::where('username', 'staff')->value('id');

        $transaksi = [
            ['kode' => 'PMJ-0001', 'tanggal_pinjam' => now()->subDays(9), 'tanggal_batas' => now()->subDays(2), 'tanggal_kembali' => null, 'anggota_id' => 1, 'buku_id' => 1, 'kasir_id' => $staffId, 'judul' => 'Dasar-Dasar Pemrograman', 'status' => 'dipinjam', 'hari_terlambat' => 0, 'denda' => 0, 'denda_lunas' => true],
            ['kode' => 'PMJ-0002', 'tanggal_pinjam' => now()->subDays(3), 'tanggal_batas' => now()->addDays(4), 'tanggal_kembali' => null, 'anggota_id' => 2, 'buku_id' => 2, 'kasir_id' => $staffId, 'judul' => 'Sistem Informasi Manajemen', 'status' => 'dipinjam', 'hari_terlambat' => 0, 'denda' => 0, 'denda_lunas' => true],
            ['kode' => 'PMJ-0003', 'tanggal_pinjam' => now()->subDays(5), 'tanggal_batas' => now()->addDays(2), 'tanggal_kembali' => null, 'anggota_id' => 3, 'buku_id' => 5, 'kasir_id' => $adminId, 'judul' => 'Kalkulus Dasar', 'status' => 'dipinjam', 'hari_terlambat' => 0, 'denda' => 0, 'denda_lunas' => true],
            ['kode' => 'PMJ-0004', 'tanggal_pinjam' => now()->subDays(20), 'tanggal_batas' => now()->subDays(13), 'tanggal_kembali' => now()->subDays(11), 'anggota_id' => 1, 'buku_id' => 4, 'kasir_id' => $staffId, 'judul' => 'Clean Code', 'status' => 'selesai', 'hari_terlambat' => 2, 'denda' => 2000, 'denda_lunas' => true],
            ['kode' => 'PMJ-0005', 'tanggal_pinjam' => now()->subDays(15), 'tanggal_batas' => now()->subDays(8), 'tanggal_kembali' => now()->subDays(6), 'anggota_id' => 2, 'buku_id' => 6, 'kasir_id' => $adminId, 'judul' => 'Pengantar Ekonomi', 'status' => 'selesai', 'hari_terlambat' => 2, 'denda' => 2000, 'denda_lunas' => false],
        ];

        foreach ($transaksi as $data) {
            Peminjaman::create($data);
        }
    }
}