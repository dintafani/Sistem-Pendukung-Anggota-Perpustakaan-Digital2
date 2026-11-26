<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $book->judul }} | Perpustakaan Digital
    </title>


    {{-- =====================================================
        GOOGLE FONT
    ====================================================== --}}

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    {{-- =====================================================
        FONT AWESOME
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    {{-- =====================================================
        CSS DETAIL BUKU
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/siswa/detailbuku.css') }}"
    >


    {{-- =====================================================
        CSS POPUP LOGIN
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/popup/login.css') }}"
    >


    {{-- =====================================================
        CSS POPUP REGISTER
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/popup/register.css') }}"
    >

</head>


<body>


<div class="detail-page">


    {{-- =====================================================
        DATA TAMBAHAN BUKU
    ====================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | JENIS BUKU
        |--------------------------------------------------------------------------
        */

        $jenisBuku = strtolower(
            trim($book->jenis_buku ?? 'umum')
        );


        /*
        |--------------------------------------------------------------------------
        | LABEL JENIS BUKU
        |--------------------------------------------------------------------------
        */

        if ($jenisBuku === 'literasi') {

            $jenisLabel = 'LITERASI';

        } elseif ($jenisBuku === 'paket') {

            $jenisLabel = 'BUKU PAKET';

        } else {

            $jenisLabel = strtoupper(
                $book->jenis_buku ?? 'UMUM'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | KATEGORI BUKU
        |--------------------------------------------------------------------------
        |
        | Contoh database:
        |
        | Novel, Contoh
        |
        | Hasil:
        |
        | Novel
        | Contoh
        |
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
        | REVIEW
        |--------------------------------------------------------------------------
        */

        $reviewCount = $book->reviews->count();

        $averageRating = $reviewCount > 0
            ? $book->reviews->avg('rating')
            : 0;

        $roundedRating = round($averageRating);


        /*
        |--------------------------------------------------------------------------
        | STOK
        |--------------------------------------------------------------------------
        */

        $stok = (int) ($book->stok ?? 0);


        /*
        |--------------------------------------------------------------------------
        | SEMESTER BUKU
        |--------------------------------------------------------------------------
        */

        $semesterBuku = $book->semester ?? null;

    @endphp



    {{-- =====================================================
        TOMBOL KEMBALI
    ====================================================== --}}

    <div class="detail-top">

        <a
            href="{{ url()->previous() }}"
            class="back-link"
            aria-label="Kembali ke halaman sebelumnya"
            title="Kembali ke halaman sebelumnya"
        >

            <i class="fa-solid fa-arrow-left"></i>

        </a>

    </div>



    {{-- =====================================================
        ALERT SUCCESS
    ====================================================== --}}

    @if(session('success'))

        <div class="alert-success">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif



    {{-- =====================================================
        ALERT ERROR
    ====================================================== --}}

    @if(session('error'))

        <div class="alert-error">

            <i class="fa-solid fa-circle-xmark"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif



    {{-- =========================================================
        HERO DETAIL BUKU
    ========================================================== --}}

    <div class="detail-hero">


        {{-- =====================================================
            COVER BUKU
        ====================================================== --}}

        <div class="detail-cover">

            {{-- =================================================
                JIKA ADA COVER
            ================================================== --}}

            @if(!empty($book->cover))

                <img
                    src="{{ asset('storage/' . ltrim($book->cover, '/')) }}"
                    alt="{{ $book->judul }}"
                    loading="eager"
                >

            {{-- =================================================
                JIKA TIDAK ADA COVER
            ================================================== --}}

            @else

                <div class="empty-book-cover">

                    <i class="fa-solid fa-book-open"></i>

                    <span>
                        Belum ada cover
                    </span>

                </div>

            @endif


            {{-- =================================================
                STATUS STOK
            ================================================== --}}

            @if($stok > 0)

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
            INFORMASI UTAMA
        ====================================================== --}}

        <div class="detail-content">


            {{-- =================================================
                BADGE / TAG
            ================================================== --}}

            <div class="book-tags">


                {{-- KATEGORI --}}

                @if($kategoriBuku->count() > 0)

                    @foreach($kategoriBuku as $kategori)

                        <span>
                            {{ strtoupper($kategori) }}
                        </span>

                    @endforeach

                @else

                    <span>
                        UMUM
                    </span>

                @endif


                {{-- JENIS BUKU --}}

                <span>
                    {{ $jenisLabel }}
                </span>


                {{-- SEMESTER --}}

                @if(
                    $jenisBuku === 'paket'
                    && !empty($semesterBuku)
                )

                    <span>
                        SEMESTER {{ $semesterBuku }}
                    </span>

                @endif


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
                    $book->deskripsi ?? 'Belum ada deskripsi buku.',
                    80
                ) }}

            </p>



            {{-- =================================================
                RATING
            ================================================== --}}

            <div class="book-rating">

                <div class="stars">

                    @if($reviewCount > 0)

                        @for($i = 1; $i <= 5; $i++)

                            @if($i <= $roundedRating)

                                ★

                            @else

                                ☆

                            @endif

                        @endfor

                    @else

                        ☆☆☆☆☆

                    @endif

                </div>


                <span>

                    {{ number_format($averageRating, 1) }}

                </span>


                <small>

                    ({{ $reviewCount }} ulasan)

                </small>

            </div>



            {{-- =================================================
                CARD INFORMASI BUKU
            ================================================== --}}

            <div class="book-info-card">


                {{-- PENULIS --}}

                <div class="info-row">

                    <span>
                        Penulis
                    </span>

                    <strong>

                        {{ $book->penulis ?? '-' }}

                    </strong>

                </div>



                {{-- PENERBIT --}}

                <div class="info-row">

                    <span>
                        Penerbit
                    </span>

                    <strong>

                        {{ $book->penerbit ?? '-' }}

                    </strong>

                </div>



                {{-- TAHUN TERBIT --}}

                <div class="info-row">

                    <span>
                        Tahun Terbit
                    </span>

                    <strong>

                        {{ $book->tahun ?? '-' }}

                    </strong>

                </div>



                {{-- KATEGORI --}}

                <div class="info-row">

                    <span>
                        Kategori
                    </span>

                    <strong class="detail-kategori-list">

                        @if($kategoriBuku->count() > 0)

                            @foreach($kategoriBuku as $kategori)

                                <span class="detail-kategori-item">
                                    {{ $kategori }}
                                </span>

                            @endforeach

                        @else

                            <span class="detail-kategori-item">
                                Umum
                            </span>

                        @endif

                    </strong>

                </div>



                {{-- JENIS BUKU --}}

                <div class="info-row">

                    <span>
                        Jenis Buku
                    </span>

                    <strong>

                        {{ $jenisLabel }}

                    </strong>

                </div>



                {{-- SEMESTER --}}

                @if($jenisBuku === 'paket')

                    <div class="info-row">

                        <span>
                            Semester
                        </span>

                        <strong>

                            @if(!empty($semesterBuku))

                                Semester {{ $semesterBuku }}

                            @else

                                -

                            @endif

                        </strong>

                    </div>

                @endif



                {{-- RAK --}}

                <div class="info-row">

                    <span>
                        Rak
                    </span>

                    <strong>

                        {{ $book->rak ?? '-' }}

                    </strong>

                </div>



                {{-- STOK --}}

                <div class="info-row">

                    <span>
                        Stok Tersedia
                    </span>

                    <strong>

                        {{ $stok }} Eksemplar

                    </strong>

                </div>

            </div>



            {{-- =================================================
                INFORMASI KHUSUS JENIS BUKU
            ================================================== --}}

            @if($jenisBuku === 'literasi')

                <div class="stock-text">

                    <i class="fa-solid fa-book-open"></i>

                    Buku literasi — maksimal 3 buku literasi
                    aktif per siswa.

                </div>

            @elseif($jenisBuku === 'paket')

                <div class="stock-text">

                    <i class="fa-solid fa-graduation-cap"></i>

                    Buku paket

                    @if(!empty($semesterBuku))

                        untuk Semester {{ $semesterBuku }}

                    @else

                        untuk semester yang sesuai

                    @endif

                    sesuai aturan peminjaman perpustakaan.

                </div>

            @endif



            {{-- =================================================
                STATUS STOK
            ================================================== --}}

            <div class="stock-text">

                @if($stok > 0)

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

                @if($stok > 0)

                    @auth

                        <form
                            action="{{ route('siswa.borrow.store', $book->id) }}"
                            method="POST"
                            class="borrow-form"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn-borrow"
                            >

                                <i class="fa-solid fa-book"></i>

                                <span>
                                    Pinjam Buku Ini
                                </span>

                            </button>

                        </form>

                    @else

                        <a
                            href="#"
                            id="openLogin"
                            class="btn-borrow"
                        >

                            <i class="fa-solid fa-right-to-bracket"></i>

                            <span>
                                Login untuk Meminjam
                            </span>

                        </a>

                    @endauth

                @else

                    <button
                        type="button"
                        class="btn-disabled"
                        disabled
                    >

                        <i class="fa-solid fa-ban"></i>

                        <span>
                            Buku Tidak Tersedia
                        </span>

                    </button>

                @endif

            </div>



            {{-- =================================================
                FITUR
            ================================================== --}}

            <div class="book-feature">

                <div>

                    <i class="fa-regular fa-calendar"></i>

                    <span>
                        Maks. 7 hari peminjaman
                    </span>

                </div>


                <div>

                    <i class="fa-solid fa-rotate"></i>

                    <span>
                        Telah dipinjam
                        {{ $book->borrowings->count() }}
                        kali
                    </span>

                </div>


                <div>

                    <i class="fa-solid fa-location-dot"></i>

                    <span>
                        Ambil di meja sirkulasi
                    </span>

                </div>


                <div>

                    <i class="fa-solid fa-id-card"></i>

                    <span>
                        Wajib membawa kartu Anggota
                    </span>

                </div>

            </div>


        </div>

    </div>



    {{-- =========================================================
        TAB MENU
    ========================================================== --}}

    <div class="detail-tabs">


        {{-- DESKRIPSI --}}

        <button
            type="button"
            class="tab-btn active"
            data-tab="deskripsi"
        >

            <i class="fa-solid fa-book-open"></i>

            <span>
                Deskripsi
            </span>

        </button>



        {{-- DETAIL --}}

        <button
            type="button"
            class="tab-btn"
            data-tab="detail"
        >

            <i class="fa-solid fa-circle-info"></i>

            <span>
                Detail Buku
            </span>

        </button>



        {{-- ULASAN --}}

        <button
            type="button"
            class="tab-btn"
            data-tab="ulasan"
        >

            <i class="fa-solid fa-star"></i>

            <span>
                Ulasan
            </span>

            <span class="review-count">

                {{ $reviewCount }}

            </span>

        </button>

    </div>



    {{-- =========================================================
        CONTENT TAB
    ========================================================== --}}

    <div class="tab-content-wrapper">


        {{-- =====================================================
            TAB DESKRIPSI
        ====================================================== --}}

        <div
            class="tab-content active"
            id="deskripsi"
        >

            <div class="detail-grid">


                {{-- BAGIAN KIRI --}}

                <div class="detail-left">

                    <div class="content-card">

                        <h2>
                            Tentang Buku Ini
                        </h2>

                        <p>

                            {{ $book->deskripsi ?? 'Belum ada deskripsi buku.' }}

                        </p>

                    </div>

                </div>



                {{-- BAGIAN KANAN --}}

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

                            <span>
                                Stok :
                            </span>

                            <strong>
                                {{ $stok }}
                            </strong>


                            @if($stok > 0)

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
            TAB DETAIL BUKU
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


                    {{-- JUDUL --}}

                    <tr>

                        <th>
                            Judul
                        </th>

                        <td>

                            {{ $book->judul }}

                        </td>

                    </tr>



                    {{-- PENULIS --}}

                    <tr>

                        <th>
                            Penulis
                        </th>

                        <td>

                            {{ $book->penulis ?? '-' }}

                        </td>

                    </tr>



                    {{-- PENERBIT --}}

                    <tr>

                        <th>
                            Penerbit
                        </th>

                        <td>

                            {{ $book->penerbit ?? '-' }}

                        </td>

                    </tr>



                    {{-- TAHUN --}}

                    <tr>

                        <th>
                            Tahun
                        </th>

                        <td>

                            {{ $book->tahun ?? '-' }}

                        </td>

                    </tr>



                    {{-- KATEGORI --}}

                    <tr>

                        <th>
                            Kategori
                        </th>

                        <td>

                            <div class="detail-kategori-list table-kategori">

                                @if($kategoriBuku->count() > 0)

                                    @foreach($kategoriBuku as $kategori)

                                        <span class="detail-kategori-item">
                                            {{ $kategori }}
                                        </span>

                                    @endforeach

                                @else

                                    <span class="detail-kategori-item">
                                        Umum
                                    </span>

                                @endif

                            </div>

                        </td>

                    </tr>



                    {{-- JENIS BUKU --}}

                    <tr>

                        <th>
                            Jenis Buku
                        </th>

                        <td>

                            {{ $jenisLabel }}

                        </td>

                    </tr>



                    {{-- SEMESTER --}}

                    @if($jenisBuku === 'paket')

                        <tr>

                            <th>
                                Semester
                            </th>

                            <td>

                                @if(!empty($semesterBuku))

                                    Semester {{ $semesterBuku }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @endif



                    {{-- RAK --}}

                    <tr>

                        <th>
                            Rak
                        </th>

                        <td>

                            {{ $book->rak ?? '-' }}

                        </td>

                    </tr>



                    {{-- STOK --}}

                    <tr>

                        <th>
                            Stok
                        </th>

                        <td>

                            {{ $stok }} Buku

                        </td>

                    </tr>


                </table>

            </div>

        </div>



        {{-- =====================================================
            TAB ULASAN
        ====================================================== --}}

        <div
            class="tab-content"
            id="ulasan"
        >

            <div class="content-card review-content-card">


                {{-- HEADER ULASAN --}}

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

                        {{ $reviewCount }}

                        ulasan

                    </div>

                </div>



                {{-- ADA ULASAN --}}

                @if($reviewCount > 0)

                    <div class="review-list">


                        @foreach($book->reviews as $review)

                            <div class="review-card">


                                {{-- HEADER REVIEW --}}

                                <div class="review-header">


                                    <div class="review-avatar">

                                        {{
                                            strtoupper(
                                                substr(
                                                    optional($review->user)->name ?? 'U',
                                                    0,
                                                    1
                                                )
                                            )
                                        }}

                                    </div>



                                    <div class="review-user">

                                        <h4>

                                            {{ optional($review->user)->name ?? 'Pengguna' }}

                                        </h4>


                                        <div class="review-stars">

                                            @for($i = 1; $i <= 5; $i++)

                                                @if($i <= $review->rating)

                                                    <i class="fa-solid fa-star"></i>

                                                @else

                                                    <i class="fa-regular fa-star"></i>

                                                @endif

                                            @endfor

                                        </div>

                                    </div>

                                </div>



                                {{-- ISI REVIEW --}}

                                <div class="review-body">

                                    <p>

                                        {{ $review->ulasan ?? 'Tidak ada isi ulasan.' }}

                                    </p>

                                </div>



                                {{-- TANGGAL REVIEW --}}

                                @if($review->created_at)

                                    <div class="review-date">

                                        {{ $review->created_at->format('d M Y') }}

                                    </div>

                                @endif


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


</div>



{{-- =========================================================
    POPUP LOGIN
========================================================= --}}

@include('components.login-modal')



{{-- =========================================================
    POPUP REGISTER
========================================================= --}}

@include('components.register-modal')



{{-- =========================================================
    CSS TAMBAHAN
========================================================= --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | COVER BUKU KOSONG
    |--------------------------------------------------------------------------
    */

    .empty-book-cover {

        width: 100%;
        height: 100%;

        min-height: 420px;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        gap: 12px;

        background: #f8f3ee;

        color: #b8aaa0;

        text-align: center;

    }


    .empty-book-cover i {

        font-size: 58px;

        opacity: 0.65;

    }


    .empty-book-cover span {

        font-size: 13px;

        font-weight: 600;

    }


    /*
    |--------------------------------------------------------------------------
    | KATEGORI PADA BADGE ATAS
    |--------------------------------------------------------------------------
    */

    .book-tags {

        display: flex;

        flex-wrap: wrap;

        align-items: center;

        gap: 8px;

    }


    .book-tags > span {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        white-space: nowrap;

    }


    /*
    |--------------------------------------------------------------------------
    | KATEGORI PADA INFORMASI BUKU
    |--------------------------------------------------------------------------
    */

    .detail-kategori-list {

        display: flex;

        flex-wrap: wrap;

        align-items: center;

        gap: 6px;

    }


    .detail-kategori-item {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        padding: 5px 10px;

        border-radius: 7px;

        background: #fff1e8;

        color: #ff7414;

        font-size: 12px;

        font-weight: 700;

        line-height: 1.2;

        white-space: nowrap;

    }


    /*
    |--------------------------------------------------------------------------
    | KATEGORI DI TABLE DETAIL
    |--------------------------------------------------------------------------
    */

    .table-kategori {

        justify-content: flex-start;

    }


    /*
    |--------------------------------------------------------------------------
    | STRONG PADA INFO ROW
    |--------------------------------------------------------------------------
    */

    .info-row > strong.detail-kategori-list {

        display: flex;

        flex-wrap: wrap;

        justify-content: flex-end;

        gap: 6px;

    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 600px) {

        .book-tags {

            gap: 6px;

        }


        .detail-kategori-list {

            gap: 5px;

        }


        .info-row > strong.detail-kategori-list {

            justify-content: flex-start;

        }


        .empty-book-cover {

            min-height: 300px;

        }

    }

</style>



{{-- =========================================================
    JAVASCRIPT TAB
========================================================= --}}

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


                        /*
                        |--------------------------------------------------------------------------
                        | NONAKTIFKAN SEMUA BUTTON
                        |--------------------------------------------------------------------------
                        */

                        tabButtons.forEach(
                            function (btn) {

                                btn.classList.remove(
                                    'active'
                                );

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | SEMBUNYIKAN SEMUA TAB
                        |--------------------------------------------------------------------------
                        */

                        tabContents.forEach(
                            function (content) {

                                content.classList.remove(
                                    'active'
                                );

                            }
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | AKTIFKAN BUTTON
                        |--------------------------------------------------------------------------
                        */

                        button.classList.add(
                            'active'
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | TAMPILKAN CONTENT
                        |--------------------------------------------------------------------------
                        */

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



{{-- =========================================================
    JAVASCRIPT UTAMA
========================================================= --}}

<script src="{{ asset('js/script.js') }}"></script>


</body>

</html>