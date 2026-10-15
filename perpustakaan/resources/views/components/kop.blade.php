@props(['ikon' => '📘', 'judul' => '', 'deskripsi' => ''])

<div class="halaman-kop">
    <span class="kotak-ikon">{{ $ikon }}</span>
    <div>
        <h2>{{ $judul }}</h2>
        @if ($deskripsi)
            <p>{{ $deskripsi }}</p>
        @endif
    </div>
    @isset($aksi)
        <div class="aksi-kanan">{{ $aksi }}</div>
    @endisset
</div>