@extends('layouts.petugas')

@section('title', 'Koleksi Buku')

@section('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/petugas/koleksibuku.css') }}"
    >
@endsection

@section('content')

<div class="book-page">

    {{-- =========================================================
        HEADER
    ========================================================= --}}
    <div class="book-page-header">

        <div class="book-page-title">

            <div class="book-title-icon">
                <i class="fa-solid fa-book"></i>
            </div>

            <div>
                <h1>Koleksi Buku</h1>

                <p>
                    {{ $books->count() }} judul
                    —
                    {{ $books->sum('stok') }} buku tersedia
                </p>
            </div>

        </div>


        {{-- =====================================================
            HEADER ACTION
        ====================================================== --}}
        <div class="book-header-actions">

            <div class="collection-menu">

                <button
                    type="button"
                    id="collectionMenuButton"
                    class="collection-menu-button"
                    aria-expanded="false"
                >
                    <i class="fa-solid fa-sliders"></i>

                    <span>Kelola Koleksi</span>

                    <i class="fa-solid fa-chevron-down menu-chevron"></i>
                </button>


                {{-- =================================================
                    DROPDOWN
                ================================================== --}}
                <div
                    id="collectionMenuDropdown"
                    class="collection-menu-dropdown"
                >

                    {{-- TAMBAH BUKU --}}
                    <a
                        href="{{ route('petugas.books.create') }}"
                        class="collection-menu-item"
                    >
                        <div class="collection-menu-item-icon">
                            <i class="fa-solid fa-plus"></i>
                        </div>

                        <div class="collection-menu-item-content">
                            <strong>Tambah Buku</strong>
                        </div>
                    </a>


                    {{-- IMPORT BUKU --}}
                    <a
                        href="{{ route('petugas.books.import.form') }}"
                        class="collection-menu-item"
                    >
                        <div class="collection-menu-item-icon">
                            <i class="fa-solid fa-file-excel"></i>
                        </div>

                        <div class="collection-menu-item-content">
                            <strong>Import Buku</strong>
                        </div>
                    </a>


                    {{-- UNDUH DATA BUKU --}}
                    <a
                        href="{{ route('petugas.books.export') }}"
                        class="collection-menu-item"
                    >
                        <div class="collection-menu-item-icon">
                            <i class="fa-solid fa-file-arrow-down"></i>
                        </div>

                        <div class="collection-menu-item-content">
                            <strong>Unduh Data Buku</strong>
                        </div>
                    </a>


                    {{-- POSTER --}}
                    <a
                        href="{{ route('petugas.posters.index') }}"
                        class="collection-menu-item"
                    >
                        <div class="collection-menu-item-icon">
                            <i class="fa-solid fa-images"></i>
                        </div>

                        <div class="collection-menu-item-content">
                            <strong>Pengumuman</strong>
                        </div>
                    </a>


                    <div class="collection-menu-divider"></div>


                    {{-- ACADEMIC SETTING --}}
                    <a
                        href="{{ route('petugas.academic-settings.index') }}"
                        class="collection-menu-item academic-item"
                    >
                        <div class="collection-menu-item-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                        <div class="collection-menu-item-content">
                            <strong>Academic Setting</strong>
                        </div>
                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TOOLBAR
    ========================================================= --}}
    <div class="book-toolbar">

        <div class="book-search">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                id="bookSearch"
                placeholder="Cari judul atau penulis..."
                autocomplete="off"
            >

        </div>


        <div class="book-view-switch">

            <button
                type="button"
                class="view-btn active"
                id="gridView"
                title="Tampilan grid"
            >
                <i class="fa-solid fa-table-cells"></i>
            </button>

            <button
                type="button"
                class="view-btn"
                id="listView"
                title="Tampilan daftar"
            >
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>

    </div>


    {{-- =========================================================
        CATEGORY FILTER
    ========================================================= --}}
    <div class="book-categories">

        {{-- SEMUA --}}
        <button
            type="button"
            class="category-btn active"
            data-category="all"
        >
            Semua
        </button>


        @php

            /*
             * Ambil semua kategori dari seluruh buku.
             *
             * Contoh database:
             * Novel, contoh
             * Sejarah
             * Novel, Pendidikan
             *
             * Akan diubah menjadi:
             * Novel
             * contoh
             * Sejarah
             * Pendidikan
             */

            $categories = $books
                ->pluck('kategori')
                ->filter(function ($kategori) {
                    return !empty(trim((string) $kategori));
                })
                ->flatMap(function ($kategori) {

                    return explode(
                        ',',
                        (string) $kategori
                    );

                })
                ->map(function ($kategori) {

                    return trim(
                        (string) $kategori
                    );

                })
                ->filter(function ($kategori) {

                    return $kategori !== '';

                })
                ->unique(function ($kategori) {

                    return strtolower(
                        $kategori
                    );

                })
                ->values();

        @endphp


        {{-- TOMBOL SETIAP KATEGORI --}}
        @foreach($categories as $category)

            <button
                type="button"
                class="category-btn"
                data-category="{{ strtolower(trim($category)) }}"
            >
                {{ trim($category) }}
            </button>

        @endforeach

    </div>


    {{-- =========================================================
        BOOK GRID / LIST
    ========================================================= --}}
    <div
        class="book-grid"
        id="bookGrid"
    >

        {{-- =====================================================
            LIST HEADER
        ====================================================== --}}
        <div class="book-list-header">

            <div>ID</div>
            <div>JUDUL</div>
            <div>PENULIS</div>
            <div>KATEGORI</div>
            <div>JENIS BUKU</div>
            <div>SEMESTER</div>
            <div>TAHUN</div>
            <div>AKSI</div>

        </div>


        @forelse($books as $book)

            @php

                /*
                 * Jumlah peminjaman aktif
                 */
                $jumlahDipinjam = $book->borrowings()
                    ->whereIn('status', [
                        'dipinjam',
                        'menunggu_pengembalian'
                    ])
                    ->count();


                /*
                 * Kode buku
                 */
                $kodeBuku =
                    'BK-' .
                    str_pad(
                        $book->id,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );


                /*
                 * Stok
                 */
                $maksStok = 12;


                $persentaseStok =
                    min(
                        (($book->stok ?? 0) / $maksStok) * 100,
                        100
                    );


                /*
                 * KATEGORI UNTUK FILTER
                 *
                 * Contoh:
                 * "Novel, contoh"
                 *
                 * menjadi:
                 * ["novel", "contoh"]
                 */
                $kategoriFilter = collect(
                    explode(
                        ',',
                        (string) ($book->kategori ?? '')
                    )
                )
                    ->map(function ($kategori) {

                        return strtolower(
                            trim($kategori)
                        );

                    })
                    ->filter(function ($kategori) {

                        return $kategori !== '';

                    })
                    ->values()
                    ->implode(',');

            @endphp


            {{-- =================================================
                BOOK CARD
            ================================================== --}}
            <article
                class="book-card"
                data-title="{{ strtolower($book->judul ?? '') }}"
                data-author="{{ strtolower($book->penulis ?? '') }}"
                data-category="{{ $kategoriFilter }}"
                data-type="{{ strtolower($book->jenis_buku ?? '') }}"
                data-semester="{{ $book->semester ?? '' }}"
            >


                {{-- =================================================
                    GRID CONTENT
                ================================================== --}}
                <div class="book-grid-content">


                    {{-- =================================================
                        COVER
                    ================================================== --}}
                    <div class="book-cover">

                        @if($book->cover)

                            <img
                                src="{{ asset('storage/' . $book->cover) }}"
                                alt="{{ $book->judul }}"
                            >

                        @else

                            <div class="book-cover-empty">

                                <i class="fa-solid fa-book-open"></i>

                                <span>
                                    Tidak ada cover
                                </span>

                            </div>

                        @endif


                        {{-- STOCK BADGE --}}
                        <span
                            class="
                                stock-badge
                                @if(($book->stok ?? 0) <= 2)
                                    stock-low
                                @elseif(($book->stok ?? 0) <= 5)
                                    stock-medium
                                @else
                                    stock-high
                                @endif
                            "
                        >
                            {{ $book->stok ?? 0 }} stok
                        </span>

                    </div>


                    {{-- =================================================
                        CARD BODY
                    ================================================== --}}
                    <div class="book-card-body">


                        {{-- TITLE --}}
                        <h3
                            class="book-title"
                            title="{{ $book->judul }}"
                        >
                            {{ $book->judul }}
                        </h3>


                        {{-- AUTHOR --}}
                        <p class="book-author">
                            {{ $book->penulis }}
                        </p>


                        {{-- =================================================
                            META
                        ================================================== --}}
                        <div class="book-meta">


                            {{-- =================================================
                                KATEGORI
                            ================================================= --}}
                            @if($book->kategori)

                                @foreach(
                                    explode(',', $book->kategori)
                                    as $kategori
                                )

                                    @php
                                        $kategori = trim($kategori);
                                    @endphp

                                    @if($kategori !== '')

                                        <span class="book-category">
                                            {{ $kategori }}
                                        </span>

                                    @endif

                                @endforeach

                            @endif


                            {{-- =================================================
                                JENIS BUKU
                            ================================================= --}}
                            @if($book->jenis_buku)

                                <span class="book-type">
                                    {{ $book->jenis_buku }}
                                </span>

                            @endif


                            {{-- =================================================
                                SEMESTER
                            ================================================= --}}
                            @if(
                                strtolower($book->jenis_buku ?? '') === 'paket'
                                && $book->semester
                            )

                                <span class="book-semester">
                                    Semester {{ $book->semester }}
                                </span>

                            @endif


                            {{-- =================================================
                                TAHUN
                            ================================================= --}}
                            @if($book->tahun)

                                <span class="book-year">
                                    {{ $book->tahun }}
                                </span>

                            @endif

                        </div>


                        {{-- =================================================
                            RAK
                        ================================================= --}}
                        @if($book->rak)

                            <div class="book-rack">

                                <i class="fa-solid fa-layer-group"></i>

                                <span>
                                    Rak {{ $book->rak }}
                                </span>

                            </div>

                        @endif


                        {{-- =================================================
                            JUMLAH DIPINJAM
                        ================================================= --}}
                        <div class="book-borrowed">

                            <i class="fa-solid fa-book-open-reader"></i>

                            <span>
                                {{ $jumlahDipinjam }}
                                sedang dipinjam
                            </span>

                        </div>


                        {{-- =================================================
                            ACTION
                        ================================================= --}}
                        <div class="book-actions">

                            <a
                                href="{{ route('petugas.books.edit', $book->id) }}"
                                class="book-action edit-action"
                                title="Edit buku"
                            >
                                <i class="fa-solid fa-pen"></i>
                            </a>


                            <form
                                action="{{ route('petugas.books.destroy', $book->id) }}"
                                method="POST"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="book-action delete-action"
                                    title="Hapus buku"
                                    onclick="return confirm('Yakin ingin menghapus buku ini?')"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    LIST CONTENT
                    TANPA STOK
                ================================================== --}}
                <div class="book-list-content">


                    {{-- ID --}}
                    <div class="list-id">
                        {{ $kodeBuku }}
                    </div>


                    {{-- JUDUL --}}
                    <div class="list-title">

                        <strong title="{{ $book->judul }}">
                            {{ $book->judul }}
                        </strong>

                    </div>


                    {{-- PENULIS --}}
                    <div class="list-author">

                        {{ $book->penulis }}

                    </div>


                    {{-- =================================================
                        KATEGORI LIST
                    ================================================== --}}
                    <div class="list-category">

                        @if($book->kategori)

                            @foreach(
                                explode(',', $book->kategori)
                                as $kategori
                            )

                                @php
                                    $kategori = trim($kategori);
                                @endphp

                                @if($kategori !== '')

                                    <span class="list-category-badge">
                                        {{ $kategori }}
                                    </span>

                                @endif

                            @endforeach

                        @else

                            <span class="list-empty-text">
                                -
                            </span>

                        @endif

                    </div>


                    {{-- JENIS BUKU --}}
                    <div class="list-type">

                        @if($book->jenis_buku)

                            <span class="list-type-badge">
                                {{ $book->jenis_buku }}
                            </span>

                        @else

                            <span class="list-empty-text">
                                -
                            </span>

                        @endif

                    </div>


                    {{-- SEMESTER --}}
                    <div class="list-semester">

                        @if(
                            strtolower($book->jenis_buku ?? '') === 'paket'
                            && $book->semester
                        )

                            <span class="list-semester-badge">
                                Semester {{ $book->semester }}
                            </span>

                        @else

                            <span class="list-empty-text">
                                -
                            </span>

                        @endif

                    </div>


                    {{-- TAHUN --}}
                    <div class="list-year">
                        {{ $book->tahun ?? '-' }}
                    </div>


                    {{-- AKSI --}}
                    <div class="list-actions">

                        <a
                            href="{{ route('petugas.books.edit', $book->id) }}"
                            class="list-edit-btn"
                            title="Edit buku"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                            Edit
                        </a>


                        <form
                            action="{{ route('petugas.books.destroy', $book->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus buku ini?');"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="list-delete-btn"
                                title="Hapus buku"
                            >
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>

                        </form>

                    </div>

                </div>

            </article>


        @empty


            {{-- =================================================
                EMPTY
            ================================================== --}}
            <div class="book-empty">

                <div class="book-empty-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <h3>
                    Belum ada koleksi buku
                </h3>

                <p>
                    Tambahkan buku pertama ke perpustakaan.
                </p>

                <a
                    href="{{ route('petugas.books.create') }}"
                    class="btn-add-empty"
                >
                    <i class="fa-solid fa-plus"></i>
                    Tambah Buku
                </a>

            </div>

        @endforelse

    </div>

</div>

@endsection


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       MENU KELOLA KOLEKSI
    ========================================================= */

    const menuButton =
        document.getElementById('collectionMenuButton');

    const menuDropdown =
        document.getElementById('collectionMenuDropdown');


    if (menuButton && menuDropdown) {

        menuButton.addEventListener('click', function (event) {

            event.stopPropagation();

            const isOpen =
                menuDropdown.classList.contains('show');


            if (isOpen) {

                menuDropdown.classList.remove('show');

                menuButton.classList.remove('active');

                menuButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            } else {

                menuDropdown.classList.add('show');

                menuButton.classList.add('active');

                menuButton.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }

        });


        document.addEventListener('click', function (event) {

            if (
                !menuDropdown.contains(event.target) &&
                !menuButton.contains(event.target)
            ) {

                menuDropdown.classList.remove('show');

                menuButton.classList.remove('active');

                menuButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }

        });

    }


    /* =========================================================
       SEARCH & FILTER ELEMENT
    ========================================================= */

    const searchInput =
        document.getElementById('bookSearch');

    const cards =
        document.querySelectorAll('.book-card');

    const categoryButtons =
        document.querySelectorAll('.category-btn');

    const bookGrid =
        document.getElementById('bookGrid');

    const gridView =
        document.getElementById('gridView');

    const listView =
        document.getElementById('listView');


    let activeCategory = 'all';


    /* =========================================================
       FILTER BUKU
    ========================================================= */

    function filterBooks() {


        const keyword =
            searchInput
                ? searchInput.value
                    .toLowerCase()
                    .trim()
                : '';


        cards.forEach(function (card) {


            /* =================================================
               DATA BUKU
            ================================================= */

            const title =
                (
                    card.dataset.title ||
                    ''
                )
                    .toLowerCase()
                    .trim();


            const author =
                (
                    card.dataset.author ||
                    ''
                )
                    .toLowerCase()
                    .trim();


            const category =
                (
                    card.dataset.category ||
                    ''
                )
                    .toLowerCase()
                    .trim();


            /* =================================================
               CEK SEARCH
            ================================================= */

            const matchSearch =
                title.includes(keyword) ||
                author.includes(keyword);


            /* =================================================
               PECAH KATEGORI
            ================================================= */

            const bookCategories =
                category
                    .split(',')
                    .map(function (item) {

                        return item
                            .trim()
                            .toLowerCase();

                    })
                    .filter(function (item) {

                        return item !== '';

                    });


            /* =================================================
               CEK KATEGORI
            ================================================= */

            const matchCategory =
                activeCategory === 'all' ||
                bookCategories.includes(
                    activeCategory
                        .toLowerCase()
                        .trim()
                );


            /* =================================================
               HASIL FILTER
            ================================================= */

            if (
                matchSearch &&
                matchCategory
            ) {

                card.style.display = '';

            } else {

                card.style.display = 'none';

            }

        });

    }


    /* =========================================================
       SEARCH EVENT
    ========================================================= */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterBooks
        );

    }


    /* =========================================================
       CATEGORY EVENT
    ========================================================= */

    categoryButtons.forEach(function (button) {

        button.addEventListener(
            'click',
            function () {


                /* =============================================
                   HAPUS ACTIVE DARI SEMUA BUTTON
                ============================================= */

                categoryButtons.forEach(
                    function (item) {

                        item.classList.remove(
                            'active'
                        );

                    }
                );


                /* =============================================
                   ACTIVE BUTTON
                ============================================= */

                this.classList.add(
                    'active'
                );


                /* =============================================
                   SIMPAN KATEGORI AKTIF
                ============================================= */

                activeCategory =
                    (
                        this.dataset.category ||
                        'all'
                    )
                        .toLowerCase()
                        .trim();


                /* =============================================
                   JALANKAN FILTER
                ============================================= */

                filterBooks();

            }
        );

    });


    /* =========================================================
       GRID VIEW
    ========================================================= */

    if (gridView) {

        gridView.addEventListener(
            'click',
            function () {


                if (bookGrid) {

                    bookGrid.classList.remove(
                        'book-list-view'
                    );

                }


                gridView.classList.add(
                    'active'
                );


                if (listView) {

                    listView.classList.remove(
                        'active'
                    );

                }

            }
        );

    }


    /* =========================================================
       LIST VIEW
    ========================================================= */

    if (listView) {

        listView.addEventListener(
            'click',
            function () {


                if (bookGrid) {

                    bookGrid.classList.add(
                        'book-list-view'
                    );

                }


                listView.classList.add(
                    'active'
                );


                if (gridView) {

                    gridView.classList.remove(
                        'active'
                    );

                }

            }
        );

    }


    /* =========================================================
       FILTER AWAL
    ========================================================= */

    filterBooks();

});
</script>

@endpush