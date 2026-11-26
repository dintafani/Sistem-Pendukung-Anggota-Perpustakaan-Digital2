@extends('layouts.siswa')

@section('title', 'Detail Buku')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/siswa/detailbuku.css') }}"
>

@endsection


@section('content')

<div class="detail-page">

    {{-- =========================================================
         TOMBOL KEMBALI
    ========================================================== --}}

    <div class="detail-top">

        <a
            href="{{ route('siswa.books.index') }}"
            class="back-link"
            aria-label="Kembali ke Koleksi"
            title="Kembali ke Koleksi"
        >
            <i class="fa-solid fa-arrow-left"></i>
        </a>

    </div>


    {{-- =========================================================
         ALERT SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert-success">

            <i class="fa-solid fa-circle-check"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
         ALERT ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="alert-error">

            <i class="fa-solid fa-circle-xmark"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
         DATA KATEGORI
    ========================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | Pisahkan kategori yang tersimpan dalam satu kolom
        |
        | Contoh database:
        | Novel, Contoh
        |
        | Hasil:
        | ['Novel', 'Contoh']
        |--------------------------------------------------------------------------
        */

        $kategoriBuku = collect(
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


        /*
        |--------------------------------------------------------------------------
        | Jika tidak ada kategori
        |--------------------------------------------------------------------------
        */

        if ($kategoriBuku->isEmpty()) {
            $kategoriBuku = collect(['Umum']);
        }

    @endphp


    {{-- =========================================================
         DATA RATING
    ========================================================== --}}

    @php

        $reviews = $book->reviews;

        $totalReviews = $reviews->count();

        $averageRating = $totalReviews > 0
            ? round($reviews->avg('rating'), 1)
            : 0;

        $fullStars = floor($averageRating);

        $hasHalfStar =
            ($averageRating - $fullStars) >= 0.5;

    @endphp


    {{-- =========================================================
         HERO DETAIL
    ========================================================== --}}

    <div class="detail-hero">

        {{-- =====================================================
             COVER
        ====================================================== --}}
<div class="detail-cover">

    @if($book->cover && Storage::disk('public')->exists($book->cover))

        <img
            src="{{ asset('storage/' . $book->cover) }}"
            alt="{{ $book->judul }}"
        >

    @else

        <div class="no-cover">
            <i class="fa-solid fa-book-open"></i>
            <span>Cover Tidak Tersedia</span>
        </div>

    @endif

    @if($book->stok > 0)

        <span class="book-status available">
            Tersedia
        </span>

    @else

        <span class="book-status empty">
            Habis
        </span>

    @endif

</div>

        {{-- =====================================================
             INFORMASI BUKU
        ====================================================== --}}

        <div class="detail-content">

            {{-- =================================================
                 TAG
            ================================================== --}}

            <div class="book-tags">

                {{-- KATEGORI --}}
                @foreach($kategoriBuku as $kategori)

                    <span>
                        {{ strtoupper($kategori) }}
                    </span>

                @endforeach


                {{-- JENIS BUKU --}}
                <span>
                    {{ strtoupper($book->jenis_buku ?? 'Umum') }}
                </span>


                {{-- SEMESTER --}}
                @if(
                    strtolower($book->jenis_buku ?? '') === 'paket'
                    && !empty($book->semester)
                )

                    <span>
                        SEMESTER {{ $book->semester }}
                    </span>

                @endif


                {{-- KOLEKSI --}}
                <span>
                    KOLEKSI PERPUSTAKAAN
                </span>

            </div>


            {{-- =================================================
                 JUDUL
            ================================================== --}}

            <h1 class="book-title">
                {{ $book->judul }}
            </h1>


            {{-- =================================================
                 DESKRIPSI SINGKAT
            ================================================== --}}

            <p class="book-subtitle">

                {{ \Illuminate\Support\Str::limit(
                    $book->deskripsi ?? '',
                    80
                ) }}

            </p>


            {{-- =================================================
                 RATING
            ================================================== --}}

            <div class="book-rating">

                <div class="stars">

                    @for($i = 1; $i <= 5; $i++)

                        @if($i <= $fullStars)

                            <i class="fa-solid fa-star"></i>

                        @elseif(
                            $i === ($fullStars + 1)
                            && $hasHalfStar
                        )

                            <i class="fa-solid fa-star-half-stroke"></i>

                        @else

                            <i class="fa-regular fa-star"></i>

                        @endif

                    @endfor

                </div>

                <span>
                    {{ number_format($averageRating, 1) }}
                </span>

                <small>
                    ({{ $totalReviews }} ulasan)
                </small>

            </div>


            {{-- =================================================
                 INFORMASI
            ================================================== --}}

            <div class="book-info-card">

                <div class="info-row">

                    <span>
                        Penulis
                    </span>

                    <strong>
                        {{ $book->penulis }}
                    </strong>

                </div>


                <div class="info-row">

                    <span>
                        Penerbit
                    </span>

                    <strong>
                        {{ $book->penerbit }}
                    </strong>

                </div>


                <div class="info-row">

                    <span>
                        Tahun Terbit
                    </span>

                    <strong>
                        {{ $book->tahun ?? '-' }}
                    </strong>

                </div>


                {{-- =================================================
                     KATEGORI
                ================================================== --}}

                <div class="info-row">

                    <span>
                        Kategori
                    </span>

                    <strong>

                        <span class="category-text-list">

                            @foreach($kategoriBuku as $index => $kategori)

                                {{ $kategori }}

                                @if(!$loop->last)
                                    ,&nbsp;
                                @endif

                            @endforeach

                        </span>

                    </strong>

                </div>


                <div class="info-row">

                    <span>
                        Jenis Buku
                    </span>

                    <strong>
                        {{ strtoupper($book->jenis_buku ?? '-') }}
                    </strong>

                </div>


                @if(
                    strtolower($book->jenis_buku ?? '') === 'paket'
                )

                    <div class="info-row">

                        <span>
                            Semester
                        </span>

                        <strong>

                            @if($book->semester)

                                Semester {{ $book->semester }}

                            @else

                                -

                            @endif

                        </strong>

                    </div>

                @endif


                <div class="info-row">

                    <span>
                        Rak
                    </span>

                    <strong>
                        {{ $book->rak ?? '-' }}
                    </strong>

                </div>


                <div class="info-row">

                    <span>
                        Stok Tersedia
                    </span>

                    <strong>
                        {{ $book->stok }} Eksemplar
                    </strong>

                </div>

            </div>


            {{-- =================================================
                 STATUS STOK
            ================================================== --}}

            <div class="stock-text">

                @if($book->stok > 0)

                    <i class="fa-solid fa-circle-check"></i>

                    Tersedia — dapat dipinjam sekarang

                @else

                    <i class="fa-solid fa-circle-xmark"></i>

                    Buku sedang tidak tersedia

                @endif

            </div>


            {{-- =================================================
                 TOMBOL PINJAM
            ================================================== --}}

            <div class="book-action">

                @if($book->stok > 0)

                    <form
                        action="{{ route(
                            'siswa.borrow.store',
                            $book->id
                        ) }}"
                        method="POST"
                        class="borrow-form"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn-borrow"
                        >

                            <i class="fa-solid fa-book"></i>

                            Pinjam Buku Ini

                        </button>

                    </form>

                @else

                    <button
                        type="button"
                        class="btn-disabled"
                        disabled
                    >
                        Buku Tidak Tersedia
                    </button>

                @endif

            </div>


            {{-- =================================================
                 FITUR PEMINJAMAN
            ================================================== --}}

            <div class="book-feature">

                @if(
                    strtolower($book->jenis_buku ?? '') === 'paket'
                )

                    <div>

                        <i class="fa-regular fa-calendar"></i>

                        Peminjaman mengikuti akhir semester

                    </div>

                @else

                    <div>

                        <i class="fa-regular fa-calendar"></i>

                        Maks. 7 hari peminjaman

                    </div>

                @endif


                <div>

                    <i class="fa-solid fa-rotate"></i>

                    Telah dipinjam
                    {{ $book->borrowings->count() }}
                    kali

                </div>


                <div>

                    <i class="fa-solid fa-location-dot"></i>

                    Ambil di meja sirkulasi

                </div>


                <div>

                    <i class="fa-solid fa-id-card"></i>

                    Wajib membawa kartu Anggota

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TAB
    ========================================================== --}}

    <div class="detail-tabs">

        <button
            type="button"
            class="tab-btn active"
            data-tab="deskripsi"
        >

            <i class="fa-solid fa-book-open"></i>

            Deskripsi

        </button>


        <button
            type="button"
            class="tab-btn"
            data-tab="detail"
        >

            <i class="fa-solid fa-circle-info"></i>

            Detail Buku

        </button>


        <button
            type="button"
            class="tab-btn"
            data-tab="ulasan"
        >

            <i class="fa-solid fa-star"></i>

            Ulasan

            <span class="review-count">
                {{ $totalReviews }}
            </span>

        </button>

    </div>


    {{-- =========================================================
         TAB CONTENT
    ========================================================== --}}

    <div class="tab-content-wrapper">


        {{-- =====================================================
             DESKRIPSI
        ====================================================== --}}

        <div
            class="tab-content active"
            id="deskripsi"
        >

            <div class="detail-grid">

                <div class="detail-left">

                    <div class="content-card">

                        <h2>
                            Tentang Buku Ini
                        </h2>

                        <p>
                            {{ $book->deskripsi
                                ?? 'Belum ada deskripsi buku.' }}
                        </p>

                    </div>

                </div>


                <div class="detail-right">

                    <div class="location-card">

                        <span class="location-label">
                            Lokasi di Perpustakaan
                        </span>

                        <h3>
                            {{ $book->rak ?? '-' }}
                        </h3>

                        <p>
                            Silakan menuju rak tersebut
                            untuk mengambil buku.
                        </p>

                        <div class="location-stock">

                            Stok :

                            <strong>
                                {{ $book->stok }}
                            </strong>

                            @if($book->stok > 0)

                                <span class="green-check">

                                    <i class="fa-solid fa-circle-check"></i>

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             DETAIL BUKU
        ====================================================== --}}

        <div
            class="tab-content"
            id="detail"
        >

            <div class="content-card">

                <h2>
                    Informasi Lengkap Buku
                </h2>

                <table class="detail-table">

                    <tr>

                        <th>
                            Judul
                        </th>

                        <td>
                            {{ $book->judul }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Penulis
                        </th>

                        <td>
                            {{ $book->penulis }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Penerbit
                        </th>

                        <td>
                            {{ $book->penerbit ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Tahun
                        </th>

                        <td>
                            {{ $book->tahun ?? '-' }}
                        </td>

                    </tr>


                    {{-- =================================================
                         KATEGORI
                    ================================================== --}}

                    <tr>

                        <th>
                            Kategori
                        </th>

                        <td>

                            <div class="detail-category-list">

                                @foreach($kategoriBuku as $kategori)

                                    <span class="detail-category-badge">
                                        {{ $kategori }}
                                    </span>

                                @endforeach

                            </div>

                        </td>

                    </tr>


                    <tr>

                        <th>
                            Jenis Buku
                        </th>

                        <td>
                            {{ strtoupper(
                                $book->jenis_buku ?? '-'
                            ) }}
                        </td>

                    </tr>


                    @if(
                        strtolower($book->jenis_buku ?? '') === 'paket'
                    )

                        <tr>

                            <th>
                                Semester
                            </th>

                            <td>

                                @if($book->semester)

                                    Semester {{ $book->semester }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @endif


                    <tr>

                        <th>
                            Rak
                        </th>

                        <td>
                            {{ $book->rak ?? '-' }}
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Stok
                        </th>

                        <td>
                            {{ $book->stok }} Buku
                        </td>

                    </tr>

                </table>

            </div>

        </div>


        {{-- =====================================================
             ULASAN
        ====================================================== --}}

        <div
            class="tab-content"
            id="ulasan"
        >

            <div class="content-card review-content-card">

                <div class="review-title">

                    <div>

                        <h2>
                            Ulasan Pembaca
                        </h2>

                        <p>
                            Pendapat pembaca tentang buku ini.
                        </p>

                    </div>


                    <div class="review-total">

                        <i class="fa-solid fa-star"></i>

                        {{ $totalReviews }}

                        ulasan

                    </div>

                </div>


                @if($totalReviews > 0)

                    <div class="review-list">

                        @foreach($reviews as $review)

                            <div class="review-card">

                                <div class="review-header">

                                    <div class="review-avatar">

                                        {{
                                            strtoupper(
                                                substr(
                                                    $review->user->name ?? 'U',
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </div>


                                    <div class="review-user">

                                        <h4>
                                            {{ $review->user->name
                                                ?? 'Pengguna' }}
                                        </h4>


                                        <div class="review-stars">

                                            @for($i = 1; $i <= 5; $i++)

                                                @if(
                                                    $i <= $review->rating
                                                )

                                                    <i class="fa-solid fa-star"></i>

                                                @else

                                                    <i class="fa-regular fa-star"></i>

                                                @endif

                                            @endfor

                                        </div>

                                    </div>

                                </div>


                                <div class="review-body">

                                    <p>
                                        {{ $review->ulasan }}
                                    </p>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-review">

                        <i class="fa-regular fa-comment-dots"></i>

                        <h3>
                            Belum Ada Ulasan
                        </h3>

                        <p>
                            Jadilah pembaca pertama yang memberikan
                            ulasan untuk buku ini.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         JAVASCRIPT TAB
    ========================================================== --}}

    <script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const tabButtons =
                document.querySelectorAll(
                    '.tab-btn'
                );

            const tabContents =
                document.querySelectorAll(
                    '.tab-content'
                );

            tabButtons.forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const targetId =
                                button.getAttribute(
                                    'data-tab'
                                );

                            tabButtons.forEach(
                                function (btn) {

                                    btn.classList.remove(
                                        'active'
                                    );

                                }
                            );

                            tabContents.forEach(
                                function (content) {

                                    content.classList.remove(
                                        'active'
                                    );

                                }
                            );

                            button.classList.add(
                                'active'
                            );

                            const targetContent =
                                document.getElementById(
                                    targetId
                                );

                            if (targetContent) {

                                targetContent.classList.add(
                                    'active'
                                );

                            }

                        }
                    );

                }
            );

        }
    );

    </script>

@endsection