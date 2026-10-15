@extends('layouts.app')

@section('judul', 'Pengaturan')
@section('halaman', 'Pengaturan Sistem')

@section('konten')

    <x-kop ikon="⚙️" judul="Pengaturan Sistem" deskripsi="Pengaturan ini langsung dipakai oleh seluruh proses peminjaman." />

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">🧮 Aturan Peminjaman</div>

        <form method="POST" action="{{ route('pengaturan.simpan') }}">
            @csrf

            <div class="baris-form">
                <div>
                    <label>Batas Peminjaman per Anggota (buku)</label>
                    <input type="number" name="batas_pinjam" value="{{ old('batas_pinjam', $pengaturan->batas_pinjam) }}" min="1" max="20" required>
                </div>
                <div>
                    <label>Masa Peminjaman (hari)</label>
                    <input type="number" name="masa_pinjam" value="{{ old('masa_pinjam', $pengaturan->masa_pinjam) }}" min="1" max="60" required>
                </div>
                <div>
                    <label>Tarif Denda per Hari (Rp)</label>
                    <input type="number" name="tarif_denda" value="{{ old('tarif_denda', $pengaturan->tarif_denda) }}" min="0" required>
                </div>
            </div>

            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="tombol oranye">Simpan Pengaturan</button>
                <a href="{{ route('dashboard') }}" class="tombol putih">Kembali</a>
            </div>
        </form>
    </div>

    <div class="kartu animasi-muncul">
        <div class="kartu-judul">💡 Penjelasan</div>
        <div class="kolom-alur">
            <div class="kartu-langkah">
                <div class="nomor">1</div>
                <h4>Batas Peminjaman</h4>
                <p>Jumlah maksimal buku yang boleh dipinjam anggota secara bersamaan.</p>
            </div>
            <div class="kartu-langkah">
                <div class="nomor">2</div>
                <h4>Masa Peminjaman</h4>
                <p>Lama buku boleh dipinjam sebelum dianggap terlambat.</p>
            </div>
            <div class="kartu-langkah">
                <div class="nomor">3</div>
                <h4>Tarif Denda</h4>
                <p>Nominal denda yang ditambahkan untuk setiap 1 hari keterlambatan.</p>
            </div>
            <div class="kartu-langkah">
                <div class="nomor">4</div>
                <h4>Contoh Perhitungan</h4>
                <p>Masa 7 hari, kembali hari ke-9, tarif Rp 1.000, maka denda Rp 2.000.</p>
            </div>
        </div>
    </div>

@endsection