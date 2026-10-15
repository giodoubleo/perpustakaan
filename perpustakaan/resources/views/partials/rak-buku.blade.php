<div class="rak {{ $kelas ?? '' }}">
    @php
        $warna = ['#d8862b', '#2f6b63', '#d9a021', '#c9503f', '#7b5ea7', '#3f8f5c', '#b8433f'];
        $tinggi = [
            52, 64, 46, 70, 58, 42, 66, 54, 60, 48, 68, 50,
            44, 62, 56, 72, 50, 64, 46, 58, 40, 66, 54, 62,
        ];
        $miring = [3, 8, 15, 20];
    @endphp

    @foreach (range(0, 2) as $tingkat)
        <div class="rak-tingkat">
            @foreach (range(0, 7) as $slot)
                @php
                    $nomor = ($tingkat * 8) + $slot;
                    $tinggiBuku = $tinggi[$nomor];
                    $kelasBuku = in_array($nomor, $miring) ? 'miring' : '';
                    $warnaBuku = $warna[$nomor % count($warna)];
                @endphp
                <div class="rak-sumbu {{ $kelasBuku }}"
                     style="height: {{ $tinggiBuku }}px; background: {{ $warnaBuku }}"></div>
            @endforeach
        </div>
    @endforeach
</div>