@extends('layouts.siswa')

@section('title', 'Koleksi Buku')

@section('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/siswa/koleksibuku.css') }}"
>
@endsection

@section('content')

<div class="koleksi-page">

{{-- =========================================================
     MODAL LIHAT POSTER
========================================================= --}}
<div
    class="poster-modal"
    id="posterModal"
    aria-hidden="true"
>
    <div
        class="poster-modal-overlay"
        onclick="tutupPoster()"
    ></div>

    <div class="poster-modal-content">

        <button
            type="button"
            class="poster-modal-close"
            onclick="tutupPoster()"
            aria-label="Tutup poster"
        >
            <i class="fa-solid fa-xmark"></i>
        </button>

        <img
            src=""
            id="posterModalImage"
            alt="Poster Buku"
        >

    </div>
</div>


{{-- =========================================================
     AMBIL PENGUMUMAN
========================================================= --}}
@php

    $posterCollection = collect($posters ?? []);

    if ($posterCollection->count() === 0) {

        try {

            $posterCollection = \App\Models\Poster::with('book')
                ->latest()
                ->get();

        } catch (\Throwable $e) {

            $posterCollection = collect();

        }
    }

    $posterCollection = $posterCollection
        ->filter(function ($poster) {
            return !empty($poster->gambar);
        })
        ->values();

    $heroPosters = $posterCollection->take(5);

@endphp


{{-- =========================================================
     HERO PENGUMUMAN SLIDER
========================================================= --}}
<section class="hero-section">

@if($heroPosters->count() > 0)

    <div
        class="hero-slider"
        id="heroSlider"
    >

        @foreach($heroPosters as $index => $poster)

            @php
                $posterBook = $poster->book ?? null;
            @endphp

            <div
                class="hero-item {{ $index === 0 ? 'active' : '' }}"
                data-slide="{{ $index }}"
            >

                {{-- POSTER IMAGE --}}
                <div class="hero-poster">

                    <img
                        src="{{ asset('storage/' . ltrim($poster->gambar, '/')) }}"
                        alt="{{ $poster->judul ?? 'Poster Buku' }}"
                        draggable="false"
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        onerror="this.onerror=null;this.src='{{ asset('images/buku1.jpeg') }}';"
                    >

                </div>


                {{-- OVERLAY --}}
                <div class="hero-overlay"></div>


                {{-- CONTENT --}}
                <div class="hero-content">

                    <span class="hero-badge">
                        📚 Perpustakaan Digital
                    </span>

                    <h1>
                        {{ $poster->judul ?? 'Poster Buku' }}
                    </h1>

                    @if(!empty($poster->deskripsi))

                        <p>
                            {{
                                \Illuminate\Support\Str::limit(
                                    $poster->deskripsi,
                                    150
                                )
                            }}
                        </p>

                    @else

                        <p>
                            Temukan koleksi buku pilihan
                            dari perpustakaan digital.
                        </p>

                    @endif


                    {{-- TOMBOL BUKU --}}
                    @if($posterBook)

                        <a
                            href="{{ route('siswa.books.show', $posterBook) }}"
                            class="hero-button"
                        >

                            <i class="fa-solid fa-book-open"></i>

                            Lihat Buku Ini

                            <i class="fa-solid fa-arrow-right"></i>

                        </a>

                    @else

                        <a
                            href="{{ route('siswa.books.index') }}"
                            class="hero-button no-book"
                        >

                            <i class="fa-solid fa-book"></i>

                            Lihat Koleksi

                            <i class="fa-solid fa-arrow-right"></i>

                        </a>

                    @endif


                    {{-- TOMBOL LIHAT PENGUMUMAN --}}
                    <button
                        type="button"
                        class="hero-button hero-poster-button"
                        onclick="bukaPoster('{{ asset('storage/' . ltrim($poster->gambar, '/')) }}')"
                    >

                        <i class="fa-solid fa-image"></i>

                        Lihat Pengumuman

                    </button>

                </div>

            </div>

        @endforeach


        {{-- TOMBOL PREVIOUS --}}
        @if($heroPosters->count() > 1)

            <button
                type="button"
                class="hero-prev"
                id="heroPrev"
                aria-label="Poster sebelumnya"
            >
                <i class="fa-solid fa-chevron-left"></i>
            </button>


            {{-- TOMBOL NEXT --}}
            <button
                type="button"
                class="hero-next"
                id="heroNext"
                aria-label="Poster berikutnya"
            >
                <i class="fa-solid fa-chevron-right"></i>
            </button>


            {{-- DOTS --}}
            <div class="hero-dots">

                @foreach($heroPosters as $index => $poster)

                    <button
                        type="button"
                        class="hero-dot {{ $index === 0 ? 'active' : '' }}"
                        data-slide="{{ $index }}"
                        aria-label="Slide {{ $index + 1 }}"
                    ></button>

                @endforeach

            </div>

        @endif

    </div>

@else

    {{-- FALLBACK --}}
    <div class="hero-slider">

        <div class="hero-item active hero-default">

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-badge">
                    📚 Perpustakaan Digital
                </span>

                <h1>
                    Temukan Buku Terbaik
                    Untuk Hari Ini
                </h1>

                <p>
                    Jelajahi koleksi buku digital dan fisik
                    perpustakaan dengan tampilan modern dan nyaman.
                </p>

                <a
                    href="{{ route('siswa.books.index') }}"
                    class="hero-button"
                >

                    <i class="fa-solid fa-book"></i>

                    Jelajahi Koleksi

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>

        </div>

    </div>

@endif

</section>


{{-- =========================================================
     JELAJAHI KOLEKSI
========================================================= --}}
<section
    class="explore-section"
    id="koleksi"
>

    <div class="section-title">

        <span></span>

        <h2>
            Jelajahi Koleksi
        </h2>

        <span></span>

    </div>


    {{-- SEARCH --}}
    <form
        action="{{ route('siswa.books.index') }}"
        method="GET"
        class="search-form"
    >

        @if(request('kategori'))

            <input
                type="hidden"
                name="kategori"
                value="{{ request('kategori') }}"
            >

        @endif

        @if(request('rating'))

            <input
                type="hidden"
                name="rating"
                value="{{ request('rating') }}"
            >

        @endif


        <div class="search-box">

            <div class="search-left">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul, penulis, atau topik..."
                >

            </div>


            <div class="search-right">

                <label class="switch">

                    <input
                        type="checkbox"
                        name="tersedia"
                        value="1"
                        {{ request('tersedia') ? 'checked' : '' }}
                    >

                    <span class="slider"></span>

                </label>

                <small>
                    Tersedia saja
                </small>

                <button type="submit">
                    Cari
                </button>

            </div>

        </div>

    </form>


    {{-- =========================================================
         KATEGORI
    ========================================================== --}}
    <div class="category-wrapper">

        @php

            /*
            |--------------------------------------------------------------------------
            | PECAH KATEGORI
            |--------------------------------------------------------------------------
            |
            | Database:
            | Novel, Contoh
            | Novel, Fiksi
            | Komik
            |
            | Ditampilkan:
            | Novel | Contoh | Fiksi | Komik
            |
            */

            $kategoriTerpisah = collect($categories ?? [])
                ->flatMap(function ($kategori) {

                    return explode(',', $kategori);

                })
                ->map(function ($kategori) {

                    return trim($kategori);

                })
                ->filter(function ($kategori) {

                    return $kategori !== '';

                })
                ->unique(function ($kategori) {

                    return strtolower($kategori);

                })
                ->sort()
                ->values();

        @endphp


        {{-- SEMUA --}}
        <a
            href="{{ route('siswa.books.index') }}"
            class="category-item {{ !request('kategori') ? 'active' : '' }}"
        >
            Semua
        </a>


        {{-- KATEGORI --}}
        @foreach($kategoriTerpisah as $kategori)

            <a
                href="{{ route('siswa.books.index', [
                    'kategori' => $kategori,
                    'search' => request('search'),
                    'rating' => request('rating'),
                    'tersedia' => request('tersedia')
                ]) }}"
                class="category-item {{ request('kategori') == $kategori ? 'active' : '' }}"
            >
                {{ $kategori }}
            </a>

        @endforeach


        {{-- JUMLAH BUKU --}}
        <div class="book-count">
            {{ $books->count() }} Buku
        </div>

    </div>

</section>


{{-- =========================================================
     PALING BANYAK DIPINJAM
========================================================= --}}
<section class="popular-section">

    <div class="popular-header">

        <div class="popular-title">

            <span class="popular-icon">
                🔥
            </span>

            <h2>
                Paling Banyak Dipinjam
            </h2>

        </div>


        <div class="popular-filter">

            {{-- TERPOPULER --}}
            <a
                href="{{ route('siswa.books.index', [
                    'search' => request('search'),
                    'kategori' => request('kategori'),
                    'tersedia' => request('tersedia')
                ]) }}"
                class="popular-filter-btn {{ !request('rating') ? 'popular-active' : '' }}"
            >
                🔥 Terpopuler
            </a>


            {{-- RATING --}}
            <a
                href="{{ route('siswa.books.index', [
                    'search' => request('search'),
                    'kategori' => request('kategori'),
                    'tersedia' => request('tersedia'),
                    'rating' => 1
                ]) }}"
                class="popular-filter-btn {{ request('rating') ? 'popular-active' : '' }}"
            >
                ⭐ Rating
            </a>

        </div>


        {{-- =====================================================
             URUTKAN BERDASARKAN RATING
        ====================================================== --}}
        @php

            if (request('rating')) {

                $popularBooks = collect($popularBooks)
                    ->sortByDesc(function ($book) {

                        return (float) (
                            $book->reviews()
                                ->whereNotNull('rating')
                                ->avg('rating') ?? 0
                        );

                    })
                    ->values();

            }

            $popularBooks = collect($popularBooks)
                ->take(3)
                ->values();

        @endphp

    </div>


    @if($popularBooks->count() > 0)

        <div class="popular-wrapper">

            {{-- PREVIOUS --}}
            @if($popularBooks->count() > 1)

                <button
                    type="button"
                    class="popular-arrow popular-prev"
                    aria-label="Buku populer sebelumnya"
                >
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

            @endif


            <div class="popular-books">

                @foreach($popularBooks as $index => $book)

                    @php

                        $colors = [
                            'orange',
                            'yellow',
                            'green',
                            'blue',
                            'pink',
                            'purple'
                        ];

                        $color =
                            $colors[
                                $index % count($colors)
                            ];


                        /*
                        |--------------------------------------------------------------------------
                        | RATING
                        |--------------------------------------------------------------------------
                        */

                        $rating = (float) (
                            $book->reviews()
                                ->whereNotNull('rating')
                                ->avg('rating') ?? 0
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | JUMLAH PEMINJAMAN
                        |--------------------------------------------------------------------------
                        */

                        $borrowCount =
                            $book->borrowings_count ?? 0;


                        /*
                        |--------------------------------------------------------------------------
                        | JENIS BUKU
                        |--------------------------------------------------------------------------
                        */

                        $isPaket =
                            strtolower(
                                trim(
                                    $book->jenis_buku ?? ''
                                )
                            ) === 'paket';


                        /*
                        |--------------------------------------------------------------------------
                        | MULTIPLE KATEGORI
                        |--------------------------------------------------------------------------
                        */

                        $kategoriPopular = collect(
                            explode(',', $book->kategori ?? '')
                        )
                        ->map(function ($kategori) {

                            return trim($kategori);

                        })
                        ->filter(function ($kategori) {

                            return $kategori !== '';

                        })
                        ->unique(function ($kategori) {

                            return strtolower($kategori);

                        })
                        ->values();

                    @endphp


                    <a
                        href="{{ route('siswa.books.show', $book) }}"
                        class="popular-card {{ $color }}"
                    >

                        {{-- RANKING --}}
                        <div class="popular-side">

                            <span>
                                #{{ $index + 1 }}
                            </span>

                        </div>


                        {{-- COVER --}}
                        <div class="popular-cover">

                            @if(!empty($book->cover))

                                <img
                                    src="{{ asset('storage/' . ltrim($book->cover, '/')) }}"
                                    alt="{{ $book->judul }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="popular-empty">
                                    📚
                                </div>

                            @endif

                        </div>


                        {{-- INFORMASI --}}
                        <div class="popular-content">

                            {{-- KATEGORI --}}
                            <div class="popular-category-list">

                                @if($kategoriPopular->count() > 0)

                                    @foreach($kategoriPopular as $kategori)

                                        <span class="popular-category">
                                            {{ $kategori }}
                                        </span>

                                    @endforeach

                                @else

                                    <span class="popular-category">
                                        Umum
                                    </span>

                                @endif

                            </div>


                            {{-- JENIS BUKU --}}
                            <span class="popular-category">
                                {{ $book->jenis_buku ?? 'Umum' }}
                            </span>


                            {{-- SEMESTER --}}
                            @if($isPaket && !empty($book->semester))

                                <span class="popular-category">
                                    Semester {{ $book->semester }}
                                </span>

                            @endif


                            {{-- JUDUL --}}
                            <h3>
                                {{ $book->judul }}
                            </h3>


                            {{-- PENULIS --}}
                            <p>
                                {{ $book->penulis }}
                            </p>


                            {{-- RATING --}}
                            <div class="popular-rating">

                                <span class="rating-stars">

                                    @for($i = 1; $i <= 5; $i++)

                                        @if($rating >= $i)

                                            ⭐

                                        @else

                                            ☆

                                        @endif

                                    @endfor

                                </span>

                                <strong>
                                    {{ number_format($rating, 1) }}
                                </strong>

                            </div>


                            {{-- PEMINJAMAN --}}
                            <div class="popular-footer">

                                <span>
                                    📚
                                    {{ $borrowCount }}
                                    dipinjam
                                </span>

                            </div>

                        </div>

                    </a>

                @endforeach

            </div>


            {{-- NEXT --}}
            @if($popularBooks->count() > 1)

                <button
                    type="button"
                    class="popular-arrow popular-next"
                    aria-label="Buku populer berikutnya"
                >
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

            @endif

        </div>

    @else

        <div class="popular-empty-data">

            <div class="empty-icon">
                📚
            </div>

            <h3>
                Belum Ada Buku Populer
            </h3>

            <p>
                Data peminjaman belum tersedia.
            </p>

        </div>

    @endif

</section>


{{-- =========================================================
     RAK BUKU
========================================================= --}}
<section class="library-section">

    <div class="library-header">

        <div class="library-title">

            <span class="library-icon">
                📚
            </span>

            <h2>
                Rak Buku Perpustakaan
            </h2>

        </div>


        <div class="library-total">
            {{ $books->count() }} Buku
        </div>

    </div>


    @if($books->count() > 0)

        <div class="library-grid">

            @foreach($books as $index => $book)

                @php

                    $colors = [
                        'blue',
                        'orange',
                        'green',
                        'yellow',
                        'purple',
                        'pink'
                    ];

                    $color =
                        $colors[
                            $index % count($colors)
                        ];


                    /*
                    |--------------------------------------------------------------------------
                    | JENIS BUKU
                    |--------------------------------------------------------------------------
                    */

                    $isPaket =
                        strtolower(
                            trim(
                                $book->jenis_buku ?? ''
                            )
                        ) === 'paket';


                    /*
                    |--------------------------------------------------------------------------
                    | RATING
                    |--------------------------------------------------------------------------
                    */

                    $rating = (float) (
                        $book->reviews()
                            ->whereNotNull('rating')
                            ->avg('rating') ?? 0
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | MULTIPLE KATEGORI
                    |--------------------------------------------------------------------------
                    */

                    $kategoriRak = collect(
                        explode(',', $book->kategori ?? '')
                    )
                    ->map(function ($kategori) {

                        return trim($kategori);

                    })
                    ->filter(function ($kategori) {

                        return $kategori !== '';

                    })
                    ->unique(function ($kategori) {

                        return strtolower($kategori);

                    })
                    ->values();

                @endphp


                <a
                    href="{{ route('siswa.books.show', $book) }}"
                    class="library-card {{ $color }}"
                >

                    {{-- COVER --}}
                    <div class="library-cover">

                        @if(!empty($book->cover))

                            <img
                                src="{{ asset('storage/' . ltrim($book->cover, '/')) }}"
                                alt="{{ $book->judul }}"
                                loading="lazy"
                            >

                        @else

                            <div class="cover-empty">
                                📚
                            </div>

                        @endif


                        @if(($book->stok ?? 0) > 0)

                            <span class="stock-badge available">
                                {{ $book->stok }} ada
                            </span>

                        @else

                            <span class="stock-badge empty">
                                Habis
                            </span>

                        @endif

                    </div>


                    {{-- BODY --}}
                    <div class="library-body">

                        <small class="book-year">
                            {{ $book->tahun ?? '-' }}
                        </small>


                        <h3>
                            {{ $book->judul }}
                        </h3>


                        <p>
                            {{ $book->penulis }}
                        </p>


                        {{-- =================================================
                             KATEGORI
                        ================================================== --}}
                        <div class="book-category-list">

                            @if($kategoriRak->count() > 0)

                                @foreach($kategoriRak as $kategori)

                                    <span class="book-category">
                                        {{ $kategori }}
                                    </span>

                                @endforeach

                            @else

                                <span class="book-category">
                                    Umum
                                </span>

                            @endif

                        </div>


                        {{-- JENIS --}}
                        <div class="book-category">
                            {{ $book->jenis_buku ?? 'Umum' }}
                        </div>


                        {{-- SEMESTER --}}
                        @if($isPaket && !empty($book->semester))

                            <div class="book-category">
                                Semester {{ $book->semester }}
                            </div>

                        @endif


                        {{-- RATING --}}
                        <div class="popular-rating">

                            <span class="rating-stars">

                                @for($i = 1; $i <= 5; $i++)

                                    @if($rating >= $i)

                                        ⭐

                                    @else

                                        ☆

                                    @endif

                                @endfor

                            </span>

                            <strong>
                                {{ number_format($rating, 1) }}
                            </strong>

                        </div>


                        {{-- ACTION --}}
                        <div class="library-action">

                            <span>
                                📖
                            </span>

                            Lihat & Pinjam

                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    @else

        <div class="empty-library">

            <div class="empty-icon">
                📚
            </div>

            <h3>
                Belum Ada Koleksi Buku
            </h3>

            <p>
                Koleksi buku belum tersedia.
            </p>

        </div>

    @endif

</section>


{{-- =========================================================
     PAGINATION
========================================================= --}}
@if(
    $books instanceof \Illuminate\Pagination\LengthAwarePaginator
    && $books->hasPages()
)

    <div class="pagination-wrapper">

        {{ $books->withQueryString()->links() }}

    </div>

@endif

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /* =====================================================
           HERO SLIDER
        ====================================================== */

        const heroSlider =
            document.getElementById(
                'heroSlider'
            );

        if (!heroSlider) {
            return;
        }


        const heroItems =
            Array.from(
                heroSlider.querySelectorAll(
                    '.hero-item'
                )
            );


        const heroPrev =
            document.getElementById(
                'heroPrev'
            );


        const heroNext =
            document.getElementById(
                'heroNext'
            );


        const heroDots =
            Array.from(
                heroSlider.querySelectorAll(
                    '.hero-dot'
                )
            );


        if (heroItems.length === 0) {
            return;
        }


        let currentIndex = 0;
        let autoSlide = null;

        let isPointerDown = false;

        let startX = 0;
        let currentX = 0;

        let moved = false;

        const AUTO_TIME = 5000;
        const DRAG_THRESHOLD = 60;


        /* =====================================================
           POSISI SLIDE
        ====================================================== */

        function setSlidePosition(
            animate = true
        ) {

            heroItems.forEach(
                function (
                    item,
                    index
                ) {

                    item.style.transition =
                        animate
                            ? 'transform .55s ease, opacity .55s ease'
                            : 'none';


                    const position =
                        (
                            index -
                            currentIndex
                        ) * 100;


                    item.style.transform =
                        'translateX(' +
                        position +
                        '%)';


                    if (
                        index ===
                        currentIndex
                    ) {

                        item.style.opacity =
                            '1';

                        item.style.zIndex =
                            '10';

                        item.classList.add(
                            'active'
                        );

                    } else {

                        item.style.opacity =
                            '0';

                        item.style.zIndex =
                            '1';

                        item.classList.remove(
                            'active'
                        );

                    }

                }
            );


            heroDots.forEach(
                function (
                    dot,
                    index
                ) {

                    dot.classList.toggle(
                        'active',
                        index ===
                        currentIndex
                    );

                }
            );

        }


        /* =====================================================
           SHOW SLIDE
        ====================================================== */

        function showSlide(
            index,
            animate = true
        ) {

            if (
                heroItems.length <= 1
            ) {
                return;
            }


            if (index < 0) {

                index =
                    heroItems.length - 1;

            }


            if (
                index >=
                heroItems.length
            ) {

                index = 0;

            }


            currentIndex =
                index;


            setSlidePosition(
                animate
            );

        }


        /* =====================================================
           NEXT
        ====================================================== */

        function nextSlide() {

            showSlide(
                currentIndex + 1,
                true
            );

        }


        /* =====================================================
           PREVIOUS
        ====================================================== */

        function previousSlide() {

            showSlide(
                currentIndex - 1,
                true
            );

        }


        /* =====================================================
           AUTO SLIDE
        ====================================================== */

        function stopAutoSlide() {

            if (
                autoSlide !== null
            ) {

                clearInterval(
                    autoSlide
                );

                autoSlide = null;

            }

        }


        function startAutoSlide() {

            stopAutoSlide();


            if (
                heroItems.length <= 1
            ) {
                return;
            }


            autoSlide =
                setInterval(
                    function () {

                        nextSlide();

                    },
                    AUTO_TIME
                );

        }


        function restartAutoSlide() {

            stopAutoSlide();
            startAutoSlide();

        }


        /* =====================================================
           NEXT BUTTON
        ====================================================== */

        if (heroNext) {

            heroNext.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    nextSlide();
                    restartAutoSlide();

                }
            );

        }


        /* =====================================================
           PREVIOUS BUTTON
        ====================================================== */

        if (heroPrev) {

            heroPrev.addEventListener(
                'click',
                function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    previousSlide();
                    restartAutoSlide();

                }
            );

        }


        /* =====================================================
           DOTS
        ====================================================== */

        heroDots.forEach(
            function (
                dot,
                index
            ) {

                dot.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();
                        event.stopPropagation();

                        showSlide(
                            index,
                            true
                        );

                        restartAutoSlide();

                    }
                );

            }
        );


        /* =====================================================
           POINTER DOWN
        ====================================================== */

        if (
            heroItems.length > 1
        ) {

            heroSlider.addEventListener(
                'pointerdown',
                function (event) {

                    if (
                        event.target.closest(
                            '.hero-prev'
                        ) ||
                        event.target.closest(
                            '.hero-next'
                        ) ||
                        event.target.closest(
                            '.hero-dot'
                        ) ||
                        event.target.closest(
                            '.hero-button'
                        )
                    ) {
                        return;
                    }


                    isPointerDown = true;

                    moved = false;

                    startX =
                        event.clientX;

                    currentX =
                        event.clientX;


                    heroSlider.classList.add(
                        'is-dragging'
                    );


                    stopAutoSlide();


                    try {

                        heroSlider.setPointerCapture(
                            event.pointerId
                        );

                    } catch (error) {}

                }
            );


            /* =================================================
               POINTER MOVE
            ================================================== */

            heroSlider.addEventListener(
                'pointermove',
                function (event) {

                    if (
                        !isPointerDown
                    ) {
                        return;
                    }


                    currentX =
                        event.clientX;


                    const distance =
                        currentX -
                        startX;


                    if (
                        Math.abs(distance) > 5
                    ) {

                        moved = true;

                    }


                    const width =
                        heroSlider.offsetWidth;


                    if (!width) {
                        return;
                    }


                    const percentage =
                        (
                            distance /
                            width
                        ) * 100;


                    heroItems.forEach(
                        function (
                            item,
                            index
                        ) {

                            const base =
                                (
                                    index -
                                    currentIndex
                                ) * 100;


                            item.style.transition =
                                'none';


                            item.style.transform =
                                'translateX(' +
                                (
                                    base +
                                    percentage
                                ) +
                                '%)';

                        }
                    );

                }
            );


            /* =================================================
               POINTER UP
            ================================================== */

            function finishPointer(
                event
            ) {

                if (
                    !isPointerDown
                ) {
                    return;
                }


                isPointerDown = false;


                heroSlider.classList.remove(
                    'is-dragging'
                );


                const distance =
                    currentX -
                    startX;


                if (
                    moved &&
                    Math.abs(distance) >=
                    DRAG_THRESHOLD
                ) {

                    if (
                        distance < 0
                    ) {

                        nextSlide();

                    } else {

                        previousSlide();

                    }

                } else {

                    setSlidePosition(
                        true
                    );

                }


                restartAutoSlide();


                try {

                    heroSlider.releasePointerCapture(
                        event.pointerId
                    );

                } catch (error) {}

            }


            heroSlider.addEventListener(
                'pointerup',
                finishPointer
            );


            heroSlider.addEventListener(
                'pointercancel',
                finishPointer
            );


            heroSlider.addEventListener(
                'pointerleave',
                function (event) {

                    if (
                        isPointerDown &&
                        event.pointerType ===
                        'mouse'
                    ) {

                        finishPointer(
                            event
                        );

                    }

                }
            );

        }


        /* =====================================================
           PAUSE AUTO SLIDE
        ====================================================== */

        heroSlider.addEventListener(
            'mouseenter',
            function () {

                if (
                    !isPointerDown
                ) {

                    stopAutoSlide();

                }

            }
        );


        heroSlider.addEventListener(
            'mouseleave',
            function () {

                if (
                    !isPointerDown
                ) {

                    startAutoSlide();

                }

            }
        );


        /* =====================================================
           CEGAH GAMBAR DRAG
        ====================================================== */

        heroItems.forEach(
            function (item) {

                const image =
                    item.querySelector(
                        'img'
                    );


                if (image) {

                    image.addEventListener(
                        'dragstart',
                        function (event) {

                            event.preventDefault();

                        }
                    );

                }

            }
        );


        /* =====================================================
           INITIALISASI
        ====================================================== */

        setSlidePosition(false);

        startAutoSlide();


        /* =====================================================
           POPULAR BOOK SLIDER
        ====================================================== */

        const popularBooks =
            document.querySelector(
                '.popular-books'
            );


        const popularPrev =
            document.querySelector(
                '.popular-prev'
            );


        const popularNext =
            document.querySelector(
                '.popular-next'
            );


        if (
            popularBooks &&
            popularPrev &&
            popularNext
        ) {

            function getPopularScrollAmount() {

                const card =
                    popularBooks.querySelector(
                        '.popular-card'
                    );


                if (!card) {
                    return 360;
                }


                return (
                    card.offsetWidth +
                    24
                );

            }


            popularNext.addEventListener(
                'click',
                function () {

                    popularBooks.scrollBy({

                        left:
                            getPopularScrollAmount(),

                        behavior:
                            'smooth'

                    });

                }
            );


            popularPrev.addEventListener(
                'click',
                function () {

                    popularBooks.scrollBy({

                        left:
                            -getPopularScrollAmount(),

                        behavior:
                            'smooth'

                    });

                }
            );

        }

    }
);


/* =====================================================
   MODAL POSTER
===================================================== */

function bukaPoster(gambar) {

    const modal =
        document.getElementById(
            'posterModal'
        );


    const image =
        document.getElementById(
            'posterModalImage'
        );


    if (!modal || !image) {
        return;
    }


    image.src =
        gambar;


    modal.classList.add(
        'show'
    );


    modal.setAttribute(
        'aria-hidden',
        'false'
    );


    document.body.classList.add(
        'poster-modal-open'
    );

}


function tutupPoster() {

    const modal =
        document.getElementById(
            'posterModal'
        );


    const image =
        document.getElementById(
            'posterModalImage'
        );


    if (!modal) {
        return;
    }


    modal.classList.remove(
        'show'
    );


    modal.setAttribute(
        'aria-hidden',
        'true'
    );


    document.body.classList.remove(
        'poster-modal-open'
    );


    if (image) {

        image.src = '';

    }

}


/* =====================================================
   ESC
===================================================== */

document.addEventListener(
    'keydown',
    function(event) {

        if (
            event.key === 'Escape'
        ) {

            tutupPoster();

        }

    }
);

</script>

@endpush

@endsection