@if ($paginator->hasPages())
    <nav style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">
        @if ($paginator->onFirstPage())
            <span class="tombol putih kecil" style="opacity:.5">Sebelumnya</span>
        @else
            <a class="tombol putih kecil" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="tombol abu kecil" style="pointer-events:none">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="tombol oranye kecil">{{ $page }}</span>
                    @else
                        <a class="tombol putih kecil" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="tombol putih kecil" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
        @else
            <span class="tombol putih kecil" style="opacity:.5">Berikutnya</span>
        @endif
    </nav>
@endif