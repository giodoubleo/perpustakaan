@php
    $navigasi = [
        'Transaksi' => [
            ['route' => 'buku.index', 'label' => 'Data Buku', 'ikon' => '📚', 'cocok' => 'buku.*'],
            ['route' => 'anggota.index', 'label' => 'Data Anggota', 'ikon' => '👥', 'cocok' => 'anggota.*'],
            ['route' => 'peminjaman.index', 'label' => 'Peminjaman', 'ikon' => '📤', 'cocok' => 'peminjaman.*'],
            ['route' => 'pengembalian.index', 'label' => 'Pengembalian', 'ikon' => '📥', 'cocok' => 'pengembalian.*'],
            ['route' => 'laporan', 'label' => 'Laporan', 'ikon' => '📈', 'cocok' => 'laporan'],
        ],
        'Anggota' => [
            ['route' => 'katalog', 'label' => 'Katalog Buku', 'ikon' => '🔎', 'cocok' => 'katalog'],
            ['route' => 'riwayat', 'label' => 'Riwayat Saya', 'ikon' => '🗂️', 'cocok' => 'riwayat'],
        ],
        'Administrator' => [
            ['route' => 'user.index', 'label' => 'Manajemen User', 'ikon' => '🔐', 'cocok' => 'user.*'],
            ['route' => 'pengaturan', 'label' => 'Pengaturan', 'ikon' => '⚙️', 'cocok' => 'pengaturan*'],
        ],
    ];

    $user = auth()->user();
    $bagianTampil = $user->isAnggota() ? ['Anggota'] : ($user->isAdmin() ? ['Transaksi', 'Administrator'] : ['Transaksi']);
@endphp

<aside class="sisi">
    <div class="merek">
        <span class="merek-ikon">📚</span>
        <span>Perpustakaan<br><small>SMK BPPI</small></span>
    </div>

    <ul class="sisi-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'aktif' : '' }}">
                <span>🏠</span> Dashboard
            </a>
        </li>

        @foreach ($navigasi as $bagian => $item)
            @if (in_array($bagian, $bagianTampil))
                <li class="judul-bagian">{{ $bagian }}</li>
                @foreach ($item as $menu)
                    <li>
                        <a href="{{ route($menu['route']) }}" class="{{ request()->routeIs($menu['cocok']) ? 'aktif' : '' }}">
                            <span>{{ $menu['ikon'] }}</span> {{ $menu['label'] }}
                        </a>
                    </li>
                @endforeach
            @endif
        @endforeach
    </ul>

    <div>
        <div class="kartu-profil">
            <div class="avatar">{{ $user->initials() }}</div>
            <div style="min-width:0">
                <div class="profil-nama">{{ $user->name }}</div>
                <span class="peran">{{ $user->role }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="tombol abu kecil">Keluar</button>
        </form>
    </div>
</aside>