
@extends('layouts.petugas')

@section('title', 'Koleksi Buku')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/koleksibuku.css') }}">

    <style>
        .copy-summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 7px;
            margin: 12px 0;
        }

        .copy-summary-item {
            padding: 9px;
            background: #fff8f0;
            border: 1px solid #f0e3d7;
            border-radius: 8px;
            font-size: 12px;
        }

        .copy-summary-item strong {
            display: block;
            margin-top: 4px;
            font-size: 17px;
            color: #333;
        }

        .copy-details {
            margin: 10px 0;
            border: 1px solid #eadfd4;
            border-radius: 8px;
            overflow: hidden;
            font-size: 12px;
        }

        .copy-details summary {
            padding: 11px;
            cursor: pointer;
            background: #fff8f0;
            font-weight: 600;
            list-style-position: inside;
        }

        .copy-list {
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .copy-item {
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 7px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .copy-item-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .copy-status {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: capitalize;
            white-space: nowrap;
            background: #eee;
        }

        .copy-status.tersedia {
            background: #dcfce7;
            color: #166534;
        }

        .copy-status.dipinjam {
            background: #ffedd5;
            color: #9a3412;
        }

        .copy-status.rusak {
            background: #fee2e2;
            color: #991b1b;
        }

        .copy-status.hilang {
            background: #e5e7eb;
            color: #374151;
        }

        .copy-empty {
            padding: 12px;
            color: #888;
            text-align: center;
        }

        .book-total-info {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            font-size: 12px;
        }

        .book-total-info span {
            padding: 5px 9px;
            border-radius: 6px;
            background: #fff5eb;
            color: #8a4b18;
        }

        .copy-summary-total {
            background: #fff1e5;
        }

        @media (max-width: 480px) {
            .copy-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | TOTAL DATA KOLEKSI
    |--------------------------------------------------------------------------
    */

    $totalJudul = $books->count();

    $totalEksemplar = $books->sum(function ($book) {
        return $book->total_eksemplar ?? 0;
    });

    $totalTersedia = $books->sum(function ($book) {
        return $book->eksemplar_tersedia ?? 0;
    });

    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    */

    $categories = $books
        ->pluck('kategori')
        ->filter()
        ->flatMap(function ($kategori) {
            return explode(',', (string) $kategori);
        })
        ->map(function ($kategori) {
            return trim($kategori);
        })
        ->filter()
        ->unique(function ($kategori) {
            return strtolower($kategori);
        })
        ->values();

    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS EKSEMPLAR
    |--------------------------------------------------------------------------
    */

    $statusLabels = [
        'tersedia' => 'Tersedia',
        'dipinjam' => 'Dipinjam',
        'rusak' => 'Rusak',
        'hilang' => 'Hilang',
    ];
@endphp

<div class="book-page">

    {{-- HEADER --}}
    <div class="book-page-header">

        <div class="book-page-title">

            <div class="book-title-icon">
                <i class="fa-solid fa-book"></i>
            </div>

            <div>
                <h1>Koleksi Buku</h1>

                <p>
                    {{ $totalJudul }} judul buku
                    —
                    {{ $totalEksemplar }} total eksemplar
                    —
                    {{ $totalTersedia }} tersedia
                </p>
            </div>

        </div>

        {{-- MENU KELOLA KOLEKSI --}}
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

                <div
                    id="collectionMenuDropdown"
                    class="collection-menu-dropdown"
                >

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


    {{-- TOOLBAR --}}
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


    {{-- FILTER KATEGORI --}}
    <div class="book-categories">

        <button
            type="button"
            class="category-btn active"
            data-category="all"
        >
            Semua
        </button>

        @foreach($categories as $category)

            <button
                type="button"
                class="category-btn"
                data-category="{{ strtolower($category) }}"
            >
                {{ $category }}
            </button>

        @endforeach

    </div>


    {{-- GRID / LIST --}}
    <div class="book-grid" id="bookGrid">

        {{-- HEADER LIST --}}
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
                |--------------------------------------------------------------------------
                | DATA EKSEMPLAR DARI BOOK_COPIES
                |--------------------------------------------------------------------------
                */

                $copies = $book->copies ?? collect();

                $jumlahTotal = (int) ($book->total_eksemplar ?? $copies->count());

                $jumlahTersedia = (int) (
                    $book->eksemplar_tersedia
                    ?? $copies->where('status', 'tersedia')->count()
                );

                $jumlahDipinjam = (int) (
                    $book->eksemplar_dipinjam
                    ?? $copies->where('status', 'dipinjam')->count()
                );

                $jumlahRusak = (int) (
                    $book->eksemplar_rusak
                    ?? $copies->where('status', 'rusak')->count()
                );

                $jumlahHilang = (int) (
                    $book->eksemplar_hilang
                    ?? $copies->where('status', 'hilang')->count()
                );

                /*
                |--------------------------------------------------------------------------
                | KODE BUKU
                |--------------------------------------------------------------------------
                */

                $kodeBuku = 'BK-' . str_pad(
                    $book->id,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                /*
                |--------------------------------------------------------------------------
                | KATEGORI UNTUK FILTER
                |--------------------------------------------------------------------------
                */

                $kategoriFilter = collect(
                    explode(',', (string) ($book->kategori ?? ''))
                )
                    ->map(function ($kategori) {
                        return strtolower(trim($kategori));
                    })
                    ->filter()
                    ->implode(',');

                /*
                |--------------------------------------------------------------------------
                | JENIS BUKU
                |--------------------------------------------------------------------------
                */

                $jenisBuku = strtolower(trim((string) ($book->jenis_buku ?? '')));

            @endphp


            <article
                class="book-card"
                data-title="{{ strtolower($book->judul ?? '') }}"
                data-author="{{ strtolower($book->penulis ?? '') }}"
                data-category="{{ $kategoriFilter }}"
                data-type="{{ $jenisBuku }}"
                data-semester="{{ $book->semester ?? '' }}"
            >

                {{-- KONTEN GRID --}}
                <div class="book-grid-content">

                    {{-- COVER --}}
                    <div class="book-cover">

                        @if($book->cover)

                            <img
                                src="{{ asset('storage/' . $book->cover) }}"
                                alt="Cover {{ $book->judul }}"
                            >

                        @else

                            <div class="book-cover-empty">
                                <i class="fa-solid fa-book-open"></i>
                                <span>Tidak ada cover</span>
                            </div>

                        @endif

                        <span
                            class="
                                stock-badge
                                @if($jumlahTersedia <= 2)
                                    stock-low
                                @elseif($jumlahTersedia <= 5)
                                    stock-medium
                                @else
                                    stock-high
                                @endif
                            "
                        >
                            {{ $jumlahTersedia }} tersedia
                        </span>

                    </div>


                    {{-- INFORMASI BUKU --}}
                    <div class="book-card-body">

                        <h3
                            class="book-title"
                            title="{{ $book->judul }}"
                        >
                            {{ $book->judul }}
                        </h3>

                        <p class="book-author">
                            {{ $book->penulis ?? '-' }}
                        </p>


                        {{-- META BUKU --}}
                        <div class="book-meta">

                            @if($book->kategori)

                                @foreach(explode(',', $book->kategori) as $kategori)

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


                            @if($book->jenis_buku)
                                <span class="book-type">
                                    {{ ucfirst($book->jenis_buku) }}
                                </span>
                            @endif


                            @if($jenisBuku === 'paket' && $book->semester)
                                <span class="book-semester">
                                    Semester {{ $book->semester }}
                                </span>
                            @endif


                            @if($book->tahun)
                                <span class="book-year">
                                    {{ $book->tahun }}
                                </span>
                            @endif

                        </div>


                        {{-- PENERBIT --}}
                        @if($book->penerbit)
                            <div class="book-rack">
                                <i class="fa-solid fa-building"></i>
                                <span>{{ $book->penerbit }}</span>
                            </div>
                        @endif


                        {{-- RAK DEFAULT --}}
                        @if($book->rak)
                            <div class="book-rack">
                                <i class="fa-solid fa-layer-group"></i>
                                <span>Rak {{ $book->rak }}</span>
                            </div>
                        @endif


                        {{-- RINGKASAN EKSEMPLAR --}}
                        <div class="copy-summary">

                            <div class="copy-summary-item copy-summary-total">
                                Total Eksemplar
                                <strong>{{ $jumlahTotal }}</strong>
                            </div>

                            <div class="copy-summary-item">
                                Tersedia
                                <strong>{{ $jumlahTersedia }}</strong>
                            </div>

                            <div class="copy-summary-item">
                                Dipinjam
                                <strong>{{ $jumlahDipinjam }}</strong>
                            </div>

                            <div class="copy-summary-item">
                                Rusak
                                <strong>{{ $jumlahRusak }}</strong>
                            </div>

                            <div class="copy-summary-item">
                                Hilang
                                <strong>{{ $jumlahHilang }}</strong>
                            </div>

                        </div>


                        {{-- DETAIL EKSEMPLAR --}}
                        <details class="copy-details">

                            <summary>
                                <i class="fa-solid fa-barcode"></i>
                                Detail Eksemplar ({{ $jumlahTotal }})
                            </summary>

                            <div class="copy-list">

                                @forelse($copies->sortBy('nomor_eksemplar') as $copy)

                                    <div class="copy-item">

                                        <div class="copy-item-info">

                                            <strong>
                                                Eksemplar {{ $copy->nomor_eksemplar }}
                                            </strong>

                                            <span>
                                                Kode:
                                                {{ $copy->kode_eksemplar ?? '-' }}
                                            </span>

                                            <span>
                                                Rak:
                                                {{ $copy->rak ?? $book->rak ?? '-' }}
                                            </span>

                                        </div>

                                        <span class="copy-status {{ $copy->status }}">
                                            {{ $statusLabels[$copy->status] ?? ucfirst($copy->status) }}
                                        </span>

                                    </div>

                                @empty

                                    <div class="copy-empty">
                                        Belum ada data eksemplar.
                                    </div>

                                @endforelse

                            </div>

                        </details>


                        {{-- AKSI --}}
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


                {{-- KONTEN LIST --}}
                <div class="book-list-content">

                    <div class="list-id">
                        {{ $kodeBuku }}
                    </div>

                    <div class="list-title">
                        <strong title="{{ $book->judul }}">
                            {{ $book->judul }}
                        </strong>

                        <div class="book-total-info">
                            <span>{{ $jumlahTotal }} eksemplar</span>
                            <span>{{ $jumlahTersedia }} tersedia</span>
                        </div>
                    </div>

                    <div class="list-author">
                        {{ $book->penulis ?? '-' }}
                    </div>


                    {{-- KATEGORI --}}
                    <div class="list-category">

                        @if($book->kategori)

                            @foreach(explode(',', $book->kategori) as $kategori)

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

                            <span class="list-empty-text">-</span>

                        @endif

                    </div>


                    {{-- JENIS --}}
                    <div class="list-type">

                        @if($book->jenis_buku)
                            <span class="list-type-badge">
                                {{ ucfirst($book->jenis_buku) }}
                            </span>
                        @else
                            <span class="list-empty-text">-</span>
                        @endif

                    </div>


                    {{-- SEMESTER --}}
                    <div class="list-semester">

                        @if($jenisBuku === 'paket' && $book->semester)
                            <span class="list-semester-badge">
                                Semester {{ $book->semester }}
                            </span>
                        @else
                            <span class="list-empty-text">-</span>
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

            <div class="book-empty">

                <div class="book-empty-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <h3>Belum ada koleksi buku</h3>

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


{{-- JAVASCRIPT --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | MENU KELOLA KOLEKSI
    |--------------------------------------------------------------------------
    */

    const menuButton = document.getElementById('collectionMenuButton');
    const menuDropdown = document.getElementById('collectionMenuDropdown');

    if (menuButton && menuDropdown) {

        menuButton.addEventListener('click', function (event) {

            event.stopPropagation();

            const isOpen = menuDropdown.classList.contains('show');

            menuDropdown.classList.toggle('show', !isOpen);
            menuButton.classList.toggle('active', !isOpen);

            menuButton.setAttribute(
                'aria-expanded',
                !isOpen ? 'true' : 'false'
            );

        });

        document.addEventListener('click', function (event) {

            if (
                !menuDropdown.contains(event.target) &&
                !menuButton.contains(event.target)
            ) {

                menuDropdown.classList.remove('show');
                menuButton.classList.remove('active');

                menuButton.setAttribute('aria-expanded', 'false');

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH DAN FILTER
    |--------------------------------------------------------------------------
    */

    const searchInput = document.getElementById('bookSearch');
    const cards = document.querySelectorAll('.book-card');
    const categoryButtons = document.querySelectorAll('.category-btn');

    const bookGrid = document.getElementById('bookGrid');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    let activeCategory = 'all';

    function filterBooks() {

        const keyword = searchInput
            ? searchInput.value.toLowerCase().trim()
            : '';

        cards.forEach(function (card) {

            const title = (card.dataset.title || '').toLowerCase();
            const author = (card.dataset.author || '').toLowerCase();

            const category = (card.dataset.category || '').toLowerCase();

            const matchSearch =
                title.includes(keyword) ||
                author.includes(keyword);

            const categories = category
                .split(',')
                .map(function (item) {
                    return item.trim();
                })
                .filter(Boolean);

            const matchCategory =
                activeCategory === 'all' ||
                categories.includes(activeCategory);

            card.style.display =
                matchSearch && matchCategory ? '' : 'none';

        });

    }


    if (searchInput) {
        searchInput.addEventListener('input', filterBooks);
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER KATEGORI
    |--------------------------------------------------------------------------
    */

    categoryButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            categoryButtons.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');

            activeCategory = (
                this.dataset.category || 'all'
            ).toLowerCase().trim();

            filterBooks();

        });

    });


    /*
    |--------------------------------------------------------------------------
    | TAMPILAN GRID
    |--------------------------------------------------------------------------
    */

    if (gridView) {

        gridView.addEventListener('click', function () {

            if (bookGrid) {
                bookGrid.classList.remove('book-list-view');
            }

            gridView.classList.add('active');

            if (listView) {
                listView.classList.remove('active');
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILAN LIST
    |--------------------------------------------------------------------------
    */

    if (listView) {

        listView.addEventListener('click', function () {

            if (bookGrid) {
                bookGrid.classList.add('book-list-view');
            }

            listView.classList.add('active');

            if (gridView) {
                gridView.classList.remove('active');
            }

        });

    }


    filterBooks();

});
</script>

@endpush
