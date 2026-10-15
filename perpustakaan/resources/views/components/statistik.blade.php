@props(['label' => '', 'nilai' => '', 'ikon' => '📘', 'warna' => 'biru'])

<div class="kartu animasi-muncul">
    <span class="kotak-ikon ikon-{{ $warna }}">{{ $ikon }}</span>
    <div>
        <div class="label">{{ $label }}</div>
        <div class="nilai">{{ $nilai }}</div>
    </div>
</div>