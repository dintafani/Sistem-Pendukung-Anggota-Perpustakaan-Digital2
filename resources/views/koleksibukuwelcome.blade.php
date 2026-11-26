<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Semua Koleksi Buku - Perpustakaan Digital</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;900&family=Poppins:wght@400;500;600;700;900&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/koleksibukuwelcome.css') }}"
    >

</head>


<body>

<div class="app">


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <section class="koleksi-page-header">

        <div class="koleksi-page-container">

            <div class="kembali-wrapper">

                <a
                    href="{{ route('welcome') }}"
                    class="btn-kembali"
                >
                    ← Kembali ke Beranda
                </a>

            </div>


            <div class="koleksi-badge">
                ✨ Koleksi Perpustakaan
            </div>


            <h1>
                Semua Koleksi Buku 📚
            </h1>


            <p>
                Temukan berbagai buku yang tersedia di
                Perpustakaan Digital SMA Negeri 03 Kotabumi.
            </p>

        </div>

    </section>



    {{-- =========================================================
         SECTION KOLEKSI
    ========================================================== --}}

    <section class="koleksi-page-section">

        <div class="koleksi-page-container">


            {{-- =====================================================
                 FILTER
            ====================================================== --}}

            <div class="koleksi-filter">


                {{-- =================================================
                     SEARCH
                ================================================== --}}

                <div class="koleksi-search">

                    <form
                        action="{{ route('welcome.koleksi') }}"
                        method="GET"
                    >

                        @if(request()->filled('kategori'))

                            <input
                                type="hidden"
                                name="kategori"
                                value="{{ request('kategori') }}"
                            >

                        @endif


                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari judul, penulis, atau kategori..."
                        >


                        <button type="submit">
                            🔍 Cari
                        </button>

                    </form>

                </div>



                {{-- =================================================
                     KATEGORI BAGIAN ATAS
                     
                     DATABASE:
                     Novel, Contoh

                     TAMPIL:
                     Semua | Novel | Contoh
                ================================================== --}}

                <div class="kategori-list">


                    {{-- SEMUA --}}

                    <a
                        href="{{ route('welcome.koleksi', [
                            'search' => request('search')
                        ]) }}"
                        class="kategori-btn {{ !request('kategori') ? 'active' : '' }}"
                    >
                        Semua
                    </a>



                    {{-- =================================================
                         JIKA ADA KATEGORI YANG DIPILIH
                    ================================================== --}}

                    @if(request()->filled('kategori'))

                        <a
                            href="{{ route('welcome.koleksi', [
                                'kategori' => request('kategori'),
                                'search' => request('search')
                            ]) }}"
                            class="kategori-btn active"
                        >
                            {{ request('kategori') }}
                        </a>


                    @else


                        {{-- =================================================
                             PECAH SEMUA KATEGORI DARI DATABASE
                             
                             Novel, Contoh
                             ↓
                             Novel
                             Contoh
                        ================================================== --}}

                        @php

                            $kategoriTerpisah = collect($categories)
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


                        @foreach($kategoriTerpisah as $kategori)

                            <a
                                href="{{ route('welcome.koleksi', [
                                    'kategori' => $kategori,
                                    'search' => request('search')
                                ]) }}"
                                class="kategori-btn"
                            >
                                {{ $kategori }}
                            </a>

                        @endforeach

                    @endif

                </div>

            </div>



            {{-- =========================================================
                 INFORMASI HASIL
            ========================================================== --}}

            <div class="koleksi-result">

                Menampilkan

                <strong>
                    {{ $books->count() }}
                </strong>

                buku


                @if(request('search'))

                    untuk pencarian

                    <strong>
                        "{{ request('search') }}"
                    </strong>

                @endif


                @if(request('kategori'))

                    dalam kategori

                    <strong>
                        {{ request('kategori') }}
                    </strong>

                @endif

            </div>



            {{-- =========================================================
                 GRID BUKU
            ========================================================== --}}

            <div class="buku-grid">


                @forelse($books as $book)


                    <article class="buku-card">


                        {{-- =================================================
                             COVER
                        ================================================== --}}

                        <div class="buku-cover">

                            @if($book->cover)

                                <img
                                    src="{{ asset('storage/' . $book->cover) }}"
                                    alt="{{ $book->judul }}"
                                >

                            @else

                                <img
                                    src="{{ asset('images/no-book.png') }}"
                                    alt="Tidak ada cover buku"
                                >

                            @endif

                        </div>



                        {{-- =================================================
                             CONTENT
                        ================================================== --}}

                        <div class="buku-content">


                            {{-- =================================================
                                 KATEGORI KARTU + RATING
                                 
                                 Novel, Contoh
                                 ↓
                                 [Novel] [Contoh]
                            ================================================== --}}

                            <div class="buku-top">


                                {{-- KATEGORI DIPISAH --}}

                                <div class="buku-kategori-list">

                                    @if($book->kategori)

                                        @foreach(explode(',', $book->kategori) as $kategori)

                                            @php

                                                $kategori = trim($kategori);

                                            @endphp


                                            @if($kategori !== '')

                                                <span class="buku-kategori">
                                                    {{ $kategori }}
                                                </span>

                                            @endif

                                        @endforeach

                                    @else

                                        <span class="buku-kategori">
                                            Umum
                                        </span>

                                    @endif

                                </div>



                                {{-- RATING --}}

                                <div class="buku-rating">

                                    ⭐

                                    {{ number_format($book->reviews_avg_rating ?? 0, 1) }}

                                </div>

                            </div>



                            {{-- =================================================
                                 JUDUL
                            ================================================== --}}

                            <h3>
                                {{ $book->judul }}
                            </h3>



                            {{-- =================================================
                                 PENULIS
                            ================================================== --}}

                            <p class="buku-penulis">

                                oleh
                                {{ $book->penulis }}

                            </p>



                            {{-- =================================================
                                 DESKRIPSI
                            ================================================== --}}

                            <p class="buku-deskripsi">

                                {{ $book->deskripsi ?? 'Belum ada deskripsi buku.' }}

                            </p>



                            {{-- =================================================
                                 FOOTER
                            ================================================== --}}

                            <div class="buku-footer">

                                <span class="buku-ulasan">

                                    Stok:
                                    {{ $book->stok ?? 0 }}

                                </span>


                                <a
                                    href="{{ route('welcome.book.show', $book) }}"
                                    class="btn-pinjam"
                                >
                                    Lihat Buku
                                </a>

                            </div>

                        </div>

                    </article>


                @empty


                    {{-- =================================================
                         EMPTY
                    ================================================== --}}

                    <div class="koleksi-empty">

                        <div class="empty-icon">
                            📚
                        </div>


                        <h3>
                            Buku tidak ditemukan
                        </h3>


                        <p>
                            Coba gunakan kata pencarian atau kategori lainnya.
                        </p>


                        <a
                            href="{{ route('welcome.koleksi') }}"
                            class="btn-kembali"
                        >
                            Lihat Semua Buku
                        </a>

                    </div>


                @endforelse

            </div>

        </div>

    </section>

</div>



{{-- =========================================================
     CSS KATEGORI
========================================================== --}}

<style>

    /* =====================================================
       KATEGORI DI KARTU BUKU
    ====================================================== */

    .buku-kategori-list {

        display: flex;

        flex-wrap: wrap;

        align-items: center;

        gap: 6px;

        min-width: 0;

    }


    /* =====================================================
       LABEL KATEGORI
    ====================================================== */

    .buku-kategori {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        padding: 5px 10px;

        border-radius: 8px;

        background: #fff1e8;

        color: #ff7414;

        font-size: 11px;

        font-weight: 700;

        line-height: 1.2;

        white-space: nowrap;

    }


    /* =====================================================
       BAGIAN ATAS KARTU
    ====================================================== */

    .buku-top {

        display: flex;

        align-items: flex-start;

        justify-content: space-between;

        gap: 10px;

        width: 100%;

    }


    /* =====================================================
       RATING
    ====================================================== */

    .buku-rating {

        flex-shrink: 0;

        white-space: nowrap;

    }


    /* =====================================================
       KATEGORI FILTER BAGIAN ATAS
    ====================================================== */

    .kategori-list {

        display: flex;

        flex-wrap: wrap;

        align-items: center;

        gap: 8px;

    }


    .kategori-btn {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        white-space: nowrap;

    }


    /* =====================================================
       MOBILE
    ====================================================== */

    @media (max-width: 600px) {

        .buku-top {

            align-items: flex-start;

        }


        .buku-kategori-list {

            max-width: 75%;

        }

    }

</style>


</body>

</html>