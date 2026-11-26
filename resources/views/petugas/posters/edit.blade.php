@extends('layouts.petugas')

@section('title', 'Edit Poster')

@section('styles')

<style>

.poster-edit-page {
    max-width: 900px;
    margin: 0 auto;
    padding-bottom: 40px;
}

.poster-edit-card {
    background: #ffffff;
    border: 2px solid #111827;
    border-radius: 22px;
    padding: 30px;
    box-shadow: 8px 8px 0 #111827;
}

.poster-edit-header {
    margin-bottom: 30px;
}

.poster-edit-header h1 {
    margin: 0 0 8px;
    font-size: 28px;
    font-weight: 900;
    color: #111827;
}

.poster-edit-header p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}


/* =========================================================
   ERROR
========================================================= */

.poster-errors {
    margin-bottom: 25px;
    padding: 15px 18px;
    border: 2px solid #dc2626;
    border-radius: 14px;
    background: #fef2f2;
    color: #991b1b;
}

.poster-errors ul {
    margin: 8px 0 0;
    padding-left: 20px;
}


/* =========================================================
   FORM
========================================================= */

.poster-form-group {
    margin-bottom: 22px;
}

.poster-form-group > label {
    display: block;
    margin-bottom: 8px;
    font-weight: 900;
    color: #111827;
}

.poster-form-group input[type="text"],
.poster-form-group input[type="number"],
.poster-form-group textarea,
.poster-form-group input[type="file"] {

    width: 100%;
    box-sizing: border-box;

    padding: 13px 14px;

    border: 2px solid #111827;
    border-radius: 12px;

    background: #fff;

    font-family: inherit;
    font-size: 14px;

    color: #111827;

    outline: none;

    transition:
        border-color .2s ease,
        box-shadow .2s ease;
}

.poster-form-group input[type="text"]:focus,
.poster-form-group input[type="number"]:focus,
.poster-form-group textarea:focus {

    border-color: #f97316;

    box-shadow:
        0 0 0 3px rgba(249, 115, 22, .15);
}

.poster-form-group textarea {
    min-height: 120px;
    resize: vertical;
}


/* =========================================================
   BOOK SEARCH
========================================================= */

.book-search-wrapper {
    position: relative;
}

.book-search-input {
    width: 100%;
    box-sizing: border-box;

    padding: 13px 14px;

    border: 2px solid #111827;
    border-radius: 12px;

    background: #ffffff;

    font-family: inherit;
    font-size: 14px;

    color: #111827;

    outline: none;
}

.book-search-input:focus {

    border-color: #f97316;

    box-shadow:
        0 0 0 3px rgba(249, 115, 22, .15);
}


/* =========================================================
   HASIL PENCARIAN BUKU
========================================================= */

.book-search-results {

    position: absolute;

    top: calc(100% + 6px);
    left: 0;
    right: 0;

    z-index: 1000;

    background: #ffffff;

    border: 2px solid #111827;

    border-radius: 12px;

    box-shadow:
        5px 5px 0 #111827;

    max-height: 240px;

    overflow-y: auto;

    display: none;
}

.book-search-item {

    padding: 12px 14px;

    cursor: pointer;

    border-bottom: 1px solid #e5e7eb;

    transition:
        background .15s ease;
}

.book-search-item:last-child {
    border-bottom: none;
}

.book-search-item:hover {
    background: #fff7ed;
}

.book-search-title {

    display: block;

    font-size: 14px;

    font-weight: 900;

    color: #111827;
}

.book-search-author {

    display: block;

    margin-top: 3px;

    font-size: 12px;

    color: #6b7280;
}


/* =========================================================
   BUKU TERPILIH
========================================================= */

.selected-book {

    display: none;

    align-items: center;

    gap: 10px;

    margin-top: 10px;

    padding: 10px 12px;

    border-radius: 10px;

    background: #ecfdf5;

    border: 2px solid #10b981;

}

.selected-book.show {
    display: flex;
}

.selected-book-icon {

    width: 32px;
    height: 32px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 8px;

    background: #d1fae5;

}

.selected-book-content {
    flex: 1;
    min-width: 0;
}

.selected-book-label {

    display: block;

    font-size: 10px;

    font-weight: 800;

    color: #047857;

}

.selected-book-name {

    display: block;

    margin-top: 2px;

    font-size: 13px;

    font-weight: 900;

    color: #111827;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}

.clear-book {

    border: none;

    background: #fee2e2;

    color: #b91c1c;

    width: 30px;
    height: 30px;

    border-radius: 8px;

    cursor: pointer;

    font-weight: 900;

    font-size: 16px;
}

.clear-book:hover {
    background: #fecaca;
}


/* =========================================================
   INFO BUKU
========================================================= */

.poster-book-info {

    margin-top: 8px;

    padding: 11px 14px;

    border-radius: 10px;

    background: #fff7ed;

    border: 1px solid #fed7aa;

    color: #9a3412;

    font-size: 13px;

    line-height: 1.5;
}


/* =========================================================
   CURRENT POSTER
========================================================= */

.current-poster {

    margin-bottom: 28px;

    padding: 18px;

    border: 2px solid #e5e7eb;

    border-radius: 16px;

    background: #fafafa;
}

.current-poster-title {

    display: block;

    margin-bottom: 15px;

    font-size: 14px;

    font-weight: 900;

    color: #111827;
}

.current-poster img {

    display: block;

    width: 280px;

    max-width: 100%;

    max-height: 350px;

    object-fit: cover;

    border-radius: 15px;

    border: 2px solid #111827;

    box-shadow:
        5px 5px 0 #111827;
}


/* =========================================================
   CURRENT BOOK
========================================================= */

.current-book-info {

    display: flex;

    align-items: center;

    gap: 12px;

    margin-top: 18px;

    padding: 12px 14px;

    border: 2px solid #fed7aa;

    border-radius: 12px;

    background: #fff7ed;
}

.current-book-icon {

    width: 38px;
    height: 38px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 10px;

    background: #ffedd5;

    font-size: 20px;
}

.current-book-text {
    min-width: 0;
}

.current-book-label {

    display: block;

    margin-bottom: 2px;

    font-size: 11px;

    font-weight: 700;

    color: #9a3412;
}

.current-book-name {

    display: block;

    font-size: 14px;

    font-weight: 900;

    color: #111827;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}


/* =========================================================
   FILE
========================================================= */

.poster-file-info {

    display: block;

    margin-top: 7px;

    font-size: 12px;

    color: #6b7280;
}


/* =========================================================
   STATUS
========================================================= */

.poster-switch {

    display: flex;

    align-items: center;

    gap: 10px;

    min-height: 45px;

    padding: 10px 13px;

    border: 2px solid #e5e7eb;

    border-radius: 12px;

    background: #fafafa;
}

.poster-switch input {

    width: 20px;
    height: 20px;

    margin: 0;

    accent-color: #f97316;

    cursor: pointer;
}

.poster-switch label {

    margin: 0;

    font-size: 14px;

    font-weight: 700;

    color: #374151;

    cursor: pointer;
}


/* =========================================================
   ACTION
========================================================= */

.poster-actions {

    display: flex;

    gap: 12px;

    margin-top: 30px;

    padding-top: 25px;

    border-top: 2px solid #f3f4f6;
}

.btn-save {

    background: #f97316;

    color: white;

    border: 2px solid #111827;

    padding: 12px 20px;

    border-radius: 12px;

    font-family: inherit;

    font-size: 14px;

    font-weight: 900;

    cursor: pointer;

    box-shadow:
        4px 4px 0 #111827;

    transition:
        transform .15s ease,
        box-shadow .15s ease;
}

.btn-save:hover {

    transform:
        translate(2px, 2px);

    box-shadow:
        2px 2px 0 #111827;

    background: #ea580c;
}

.btn-save:active {

    transform:
        translate(4px, 4px);

    box-shadow: none;
}

.btn-cancel {

    display: inline-flex;

    align-items: center;
    justify-content: center;

    background: white;

    color: #111827;

    border: 2px solid #111827;

    padding: 12px 20px;

    border-radius: 12px;

    font-family: inherit;

    font-size: 14px;

    font-weight: 900;

    text-decoration: none;

    transition:
        background .2s ease;
}

.btn-cancel:hover {
    background: #f3f4f6;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 600px) {

    .poster-edit-page {

        padding:
            0 15px 30px;
    }

    .poster-edit-card {

        padding: 20px;

        border-radius: 18px;

        box-shadow:
            5px 5px 0 #111827;
    }

    .poster-edit-header h1 {
        font-size: 23px;
    }

    .current-poster img {

        width: 100%;

        max-width: 300px;
    }

    .poster-actions {

        flex-direction: column;
    }

    .btn-save,
    .btn-cancel {

        width: 100%;

        box-sizing: border-box;
    }

}

</style>

@endsection


@section('content')

<div class="poster-edit-page">

    <div class="poster-edit-card">


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="poster-edit-header">

            <h1>
                ✏️ Edit Pengumuman
            </h1>

            <p>
                Perbarui informasi Pengumuman. 
            </p>

        </div>


        {{-- =====================================================
             ERROR
        ====================================================== --}}

        @if ($errors->any())

            <div class="poster-errors">

                <strong>
                    Terdapat kesalahan:
                </strong>

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             FORM
        ====================================================== --}}

        <form
            action="{{ route('petugas.posters.update', $poster->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            @method('PUT')


            {{-- =================================================
                 BUKU OPSIONAL
            ================================================== --}}

            <div class="poster-form-group">

                <label for="book_search">

                    📚 Tautkan Buku

                    <span
                        style="
                            color:#6b7280;
                            font-weight:600;
                        "
                    >
                        (Opsional)
                    </span>

                </label>


                <div class="book-search-wrapper">

                    {{-- INPUT YANG DIKETIK PETUGAS --}}

                    <input
                        type="text"
                        id="book_search"
                        class="book-search-input"
                        placeholder="Ketik judul buku..."
                        autocomplete="off"
                        value="{{ old('book_id') ? optional($books->firstWhere('id', old('book_id')))->judul : optional($poster->book)->judul }}"
                    >


                    {{-- ID BUKU YANG DIKIRIM KE CONTROLLER --}}

                    <input
                        type="hidden"
                        name="book_id"
                        id="book_id"
                        value="{{ old('book_id', $poster->book_id) }}"
                    >


                    {{-- HASIL PENCARIAN --}}

                    <div
                        id="book_search_results"
                        class="book-search-results"
                    ></div>

                </div>


                {{-- BUKU YANG TERPILIH --}}

                <div
                    id="selected_book"
                    class="selected-book"
                >

                    <div class="selected-book-icon">
                        📚
                    </div>

                    <div class="selected-book-content">

                        <span class="selected-book-label">
                            BUKU TERPILIH
                        </span>

                        <span
                            id="selected_book_name"
                            class="selected-book-name"
                        >
                        </span>

                    </div>

                    <button
                        type="button"
                        id="clear_book"
                        class="clear-book"
                        title="Hapus tautan buku"
                    >
                        ×
                    </button>

                </div>


                <div class="poster-book-info">

                    Ketik judul buku untuk mencari dan menautkan
                    Pengumuman dengan buku tertentu.

                    Jika Pengumuman merupakan informasi umum,
                    biarkan kolom ini kosong.

                </div>

            </div>


            {{-- =================================================
                 POSTER SEKARANG
            ================================================== --}}

            <div class="current-poster">

                <span class="current-poster-title">

                    🖼️ Pengumuman Saat Ini

                </span>


                @if ($poster->gambar)

                    <img
                        src="{{ asset('storage/' . $poster->gambar) }}"
                        alt="{{ $poster->judul }}"
                    >

                @else

                    <div>
                        Gambar Pengumuman tidak tersedia.
                    </div>

                @endif


                {{-- BUKU SAAT INI --}}

                @if ($poster->book)

                    <div class="current-book-info">

                        <div class="current-book-icon">
                            📚
                        </div>

                        <div class="current-book-text">

                            <span class="current-book-label">
                                TERHUBUNG DENGAN BUKU
                            </span>

                            <span class="current-book-name">
                                {{ $poster->book->judul }}
                            </span>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =================================================
                 JUDUL POSTER
            ================================================== --}}

            <div class="poster-form-group">

                <label for="judul">
                    Judul Pengumuman
                </label>

                <input
                    type="text"
                    id="judul"
                    name="judul"
                    value="{{ old('judul', $poster->judul) }}"
                    placeholder="Masukkan judul poster"
                    required
                >

            </div>


            {{-- =================================================
                 DESKRIPSI
            ================================================== --}}

            <div class="poster-form-group">

                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    placeholder="Masukkan deskripsi poster..."
                >{{ old('deskripsi', $poster->deskripsi) }}</textarea>

            </div>


            {{-- =================================================
                 GAMBAR
            ================================================== --}}

            <div class="poster-form-group">

                <label for="gambar">
                    Ganti Gambar
                </label>

                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small class="poster-file-info">

                    Kosongkan jika tidak ingin mengganti gambar.

                    Format JPG, JPEG, PNG, atau WEBP.
                    Maksimal 5 MB.

                </small>

            </div>


            {{-- =================================================
                 URUTAN
            ================================================== --}}

            <div class="poster-form-group">

                <label for="urutan">
                    Urutan
                </label>

                <input
                    type="number"
                    id="urutan"
                    name="urutan"
                    min="0"
                    value="{{ old('urutan', $poster->urutan) }}"
                >

                <small class="poster-file-info">

                    Angka yang lebih kecil akan ditampilkan
                    lebih dahulu.

                </small>

            </div>


            {{-- =================================================
                 STATUS
            ================================================== --}}

            <div class="poster-form-group">

                <label>
                    Status
                </label>

                <div class="poster-switch">

                    <input
                        type="checkbox"
                        name="status"
                        id="status"
                        value="1"
                        @checked(
                            old(
                                'status',
                                $poster->status
                            )
                        )
                    >

                    <label for="status">
                        Tampilkan kepada siswa
                    </label>

                </div>

            </div>


            {{-- =================================================
                 BUTTON
            ================================================== --}}

            <div class="poster-actions">

                <button
                    type="submit"
                    class="btn-save"
                >

                    💾 Simpan Perubahan

                </button>


                <a
                    href="{{ route('petugas.posters.index') }}"
                    class="btn-cancel"
                >

                    ← Batal

                </a>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT PENCARIAN BUKU
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const bookSearch =
            document.getElementById(
                'book_search'
            );

        const bookId =
            document.getElementById(
                'book_id'
            );

        const results =
            document.getElementById(
                'book_search_results'
            );

        const selectedBook =
            document.getElementById(
                'selected_book'
            );

        const selectedBookName =
            document.getElementById(
                'selected_book_name'
            );

        const clearBook =
            document.getElementById(
                'clear_book'
            );


        if (
            !bookSearch ||
            !bookId ||
            !results
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DATA BUKU
        |--------------------------------------------------------------------------
        */

        const books = [

            @foreach($books as $book)

                {
                    id: "{{ $book->id }}",
                    judul: @json($book->judul),
                    penulis: @json($book->penulis ?? '')
                },

            @endforeach

        ];


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN BUKU TERPILIH
        |--------------------------------------------------------------------------
        */

        function showSelectedBook(book) {

            if (!book) {

                selectedBook.classList.remove(
                    'show'
                );

                selectedBookName.textContent =
                    '';

                return;
            }


            selectedBookName.textContent =
                book.judul;


            selectedBook.classList.add(
                'show'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CARI BUKU
        |--------------------------------------------------------------------------
        */

        function searchBooks(keyword) {

            keyword =
                keyword
                    .toLowerCase()
                    .trim();


            results.innerHTML =
                '';


            if (!keyword) {

                results.style.display =
                    'none';

                return;
            }


            const filtered =
                books.filter(
                    function (book) {

                        return (
                            book.judul
                                .toLowerCase()
                                .includes(keyword)
                            ||
                            book.penulis
                                .toLowerCase()
                                .includes(keyword)
                        );

                    }
                ).slice(0, 10);


            if (filtered.length === 0) {

                results.innerHTML = `

                    <div
                        style="
                            padding:14px;
                            color:#6b7280;
                            font-size:13px;
                        "
                    >
                        Buku tidak ditemukan.
                    </div>

                `;

                results.style.display =
                    'block';

                return;
            }


            filtered.forEach(
                function (book) {

                    const item =
                        document.createElement(
                            'div'
                        );


                    item.className =
                        'book-search-item';


                    item.innerHTML = `

                        <span
                            class="book-search-title"
                        >
                            ${escapeHtml(book.judul)}
                        </span>

                        ${
                            book.penulis
                                ? `
                                    <span
                                        class="book-search-author"
                                    >
                                        ${escapeHtml(book.penulis)}
                                    </span>
                                  `
                                : ''
                        }

                    `;


                    item.addEventListener(
                        'click',
                        function () {

                            bookSearch.value =
                                book.judul;

                            bookId.value =
                                book.id;

                            showSelectedBook(
                                book
                            );

                            results.style.display =
                                'none';

                        }
                    );


                    results.appendChild(
                        item
                    );

                }
            );


            results.style.display =
                'block';
        }


        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(text) {

            const div =
                document.createElement(
                    'div'
                );

            div.textContent =
                text;

            return div.innerHTML;
        }


        /*
        |--------------------------------------------------------------------------
        | SAAT MENGETIK
        |--------------------------------------------------------------------------
        */

        bookSearch.addEventListener(
            'input',
            function () {

                /*
                | Jika judul diubah manual,
                | ID buku lama dihapus.
                */

                bookId.value =
                    '';

                selectedBook.classList.remove(
                    'show'
                );

                searchBooks(
                    this.value
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | FOKUS
        |--------------------------------------------------------------------------
        */

        bookSearch.addEventListener(
            'focus',
            function () {

                if (
                    this.value.trim()
                ) {

                    searchBooks(
                        this.value
                    );

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | HAPUS BUKU
        |--------------------------------------------------------------------------
        */

        clearBook.addEventListener(
            'click',
            function () {

                bookSearch.value =
                    '';

                bookId.value =
                    '';

                selectedBook.classList.remove(
                    'show'
                );

                selectedBookName.textContent =
                    '';

                results.innerHTML =
                    '';

                results.style.display =
                    'none';

                bookSearch.focus();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | KLIK DI LUAR
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            function (event) {

                if (
                    !event.target.closest(
                        '.book-search-wrapper'
                    )
                ) {

                    results.style.display =
                        'none';

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN BUKU LAMA
        |--------------------------------------------------------------------------
        */

        const currentBookId =
            bookId.value;


        if (currentBookId) {

            const currentBook =
                books.find(
                    function (book) {

                        return String(book.id) ===
                            String(currentBookId);

                    }
                );


            if (currentBook) {

                bookSearch.value =
                    currentBook.judul;

                showSelectedBook(
                    currentBook
                );

            }

        }

    }
);

</script>

@endsection