<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'staff', 'anggota'])->default('anggota');
            $table->string('telepon')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('anggota_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('isbn')->nullable();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->year('tahun')->nullable();
            $table->string('kategori');
            $table->integer('stok')->default(1);
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });

        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('nik')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_daftar');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_batas');
            $table->date('tanggal_kembali')->nullable();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('buku_id')->constrained('buku');
            $table->foreignId('kasir_id')->nullable();
            $table->string('judul');
            $table->enum('status', ['dipinjam', 'selesai'])->default('dipinjam');
            $table->integer('hari_terlambat')->default(0);
            $table->integer('denda')->default(0);
            $table->boolean('denda_lunas')->default(true);
            $table->timestamps();
        });

        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->integer('batas_pinjam')->default(3);
            $table->integer('masa_pinjam')->default(7);
            $table->integer('tarif_denda')->default(1000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('anggota');
        Schema::dropIfExists('buku');
        Schema::dropIfExists('users');
    }
};