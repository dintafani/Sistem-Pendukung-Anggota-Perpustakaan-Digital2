@extends('layouts.petugas')

@section('title', 'Tambah Poster')

@section('styles')

<style>

/* =========================================================
   PAGE
========================================================= */

.poster-form-page {
    max-width: 900px;
    margin: 0 auto;
}

.poster-form-card {
    background: #ffffff;
    border: 2px solid #111827;
    border-radius: 22px;
    padding: 30px;
    box-shadow: 8px 8px 0 #111827;
}

.poster-form-header {
    margin-bottom: 28px;
}

.poster-form-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 900;
    color: #111827;
}

.poster-form-header p {
    margin: 7px 0 0;
    color: #6b7280;
    line-height: 1.6;
}


/* =========================================================
   FORM GROUP
========================================================= */

.poster-form-group {
    margin-bottom: 22px;
}

.poster-form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 800;
    color: #111827;
}


/* =========================================================
   INPUT
========================================================= */

.poster-form-group input[type="text"],
.poster-form-group input[type="number"],
.poster-form-group textarea {

    width: 100%;
    box-sizing: border-box;

    padding: 13px 15px;

    border: 2px solid #111827;
    border-radius: 12px;

    outline: none;

    font-family: inherit;
    font-size: 15px;

    background: #ffffff;
    color: #111827;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}


.poster-form-group textarea {
    min-height: 120px;
    resize: vertical;
}


.poster-form-group input[type="text"]:focus,
.poster-form-group input[type="number"]:focus,
.poster-form-group textarea:focus {

    border-color: #f97316;

    box-shadow:
        0 0 0 4px #ffedd5;
}


/* =========================================================
   BUKU - SEARCH
========================================================= */

.book-search-wrapper {
    position: relative;
    width: 100%;
}

.book-search-input {
    width: 100%;
    box-sizing: border-box;

    padding: 13px 15px;

    border: 2px solid #111827;
    border-radius: 12px;

    outline: none;

    font-family: inherit;
    font-size: 15px;

    background: #ffffff;
    color: #111827;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

.book-search-input:focus {
    border-color: #f97316;

    box-shadow:
        0 0 0 4px #ffedd5;
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

    max-height: 250px;

    overflow-y: auto;

    display: none;
}


/* =========================================================
   ITEM HASIL
========================================================= */

.book-search-item {

    padding: 13px 15px;

    cursor: pointer;

    border-bottom: 1px solid #e5e7eb;

    transition:
        background 0.15s ease;
}

.book-search-item:last-child {
    border-bottom: none;
}

.book-search-item:hover {
    background: #fff7ed;
}

.book-search-title {

    font-size: 14px;

    font-weight: 900;

    color: #111827;

    margin-bottom: 3px;
}

.book-search-author {

    font-size: 12px;

    color: #6b7280;
}


/* =========================================================
   EMPTY SEARCH
========================================================= */

.book-search-empty {

    padding: 14px 15px;

    font-size: 13px;

    color: #6b7280;

    text-align: center;
}


/* =========================================================
   BUKU TERPILIH
========================================================= */

.selected-book {

    display: none;

    margin-top: 10px;

    padding: 11px 14px;

    border-radius: 10px;

    background: #ecfdf5;

    border: 1px solid #86efac;

    color: #166534;

    font-size: 13px;

    line-height: 1.5;
}

.selected-book strong {
    color: #14532d;
}

.clear-book {

    display: inline-block;

    margin-left: 8px;

    color: #dc2626;

    font-weight: 800;

    cursor: pointer;

    text-decoration: none;
}

.clear-book:hover {
    text-decoration: underline;
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
   HINT
========================================================= */

.poster-form-group small {

    display: block;

    margin-top: 7px;

    color: #6b7280;

    font-size: 13px;

    line-height: 1.5;
}


/* =========================================================
   UPLOAD
========================================================= */

.poster-upload {

    border: 2px dashed #f97316;

    background: #fff7ed;

    padding: 25px;

    border-radius: 16px;

    text-align: center;
}


.poster-upload input[type="file"] {

    width: 100%;

    border: none;

    box-shadow: none;

    padding: 0;

    background: transparent;

    cursor: pointer;
}


.poster-upload input[type="file"]:focus {

    border: none;

    box-shadow: none;
}


.poster-upload p {

    margin: 12px 0 0;

    color: #9a3412;

    font-size: 13px;

    line-height: 1.5;
}


/* =========================================================
   PREVIEW
========================================================= */

.poster-preview {

    margin-top: 18px;

    display: none;
}


.poster-preview-title {

    margin-bottom: 10px;

    font-size: 13px;

    font-weight: 800;

    color: #7c2d12;
}


.poster-preview img {

    display: block;

    max-width: 100%;

    max-height: 400px;

    margin: 0 auto;

    object-fit: contain;

    border-radius: 15px;

    border: 2px solid #111827;

    background: #ffffff;

    box-shadow:
        4px 4px 0 rgba(17, 24, 39, 0.12);
}


/* =========================================================
   STATUS
========================================================= */

.poster-switch {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 13px 15px;

    border: 2px solid #111827;

    border-radius: 12px;

    background: #ffffff;
}


.poster-switch input {

    width: 20px;
    height: 20px;

    margin: 0;

    accent-color: #f97316;

    cursor: pointer;
}


/* =========================================================
   ACTION
========================================================= */

.poster-actions {

    display: flex;

    gap: 12px;

    margin-top: 30px;
}


.btn-poster-save,
.btn-poster-cancel {

    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 8px;

    border: 2px solid #111827;

    border-radius: 12px;

    padding: 12px 20px;

    font-weight: 900;

    font-size: 14px;

    text-decoration: none;

    cursor: pointer;

    transition:
        transform 0.15s ease,
        box-shadow 0.15s ease;
}


.btn-poster-save {

    background: #f97316;

    color: white;

    box-shadow:
        4px 4px 0 #111827;
}


.btn-poster-save:hover {

    transform:
        translate(2px, 2px);

    box-shadow:
        2px 2px 0 #111827;
}


.btn-poster-cancel {

    background: white;

    color: #111827;
}


.btn-poster-cancel:hover {

    background: #f9fafb;
}


/* =========================================================
   MOBILE
========================================================= */

@media(max-width: 600px) {

    .poster-form-page {
        width: 100%;
    }

    .poster-form-card {

        padding: 20px;

        border-radius: 18px;

        box-shadow:
            5px 5px 0 #111827;
    }


    .poster-form-header h1 {

        font-size: 24px;
    }


    .poster-actions {

        flex-direction: column;
    }


    .btn-poster-save,
    .btn-poster-cancel {

        width: 100%;

        box-sizing: border-box;

        text-align: center;
    }

}

</style>

@endsection


@section('content')

<div class="poster-form-page">

    <div class="poster-form-card">


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="poster-form-header">

            <h1>
                🖼️ Tambah Pengumuman
            </h1>

            <p>
                Tambahkan Spanduk informasi atau poster promosi
                yang akan ditampilkan kepada siswa.
            </p>

        </div>


        {{-- =====================================================
             ERROR
        ====================================================== --}}

        @if($errors->any())

            <div style="
                background:#fee2e2;
                border:2px solid #dc2626;
                color:#991b1b;
                padding:15px;
                border-radius:12px;
                margin-bottom:20px;
            ">

                <strong>
                    Terjadi kesalahan:
                </strong>

                <ul style="margin-bottom:0;">

                    @foreach($errors->all() as $error)

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
            action="{{ route('petugas.posters.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            {{-- =================================================
                 BUKU OPSIONAL
            ================================================== --}}

            <div class="poster-form-group">

                <label for="book_search">

                    📚 Tautkan Buku

                    <span style="
                        color:#6b7280;
                        font-weight:600;
                    ">
                        (Opsional)
                    </span>

                </label>


                <div class="book-search-wrapper">

                    {{-- INPUT UNTUK MENGETIK JUDUL --}}

                    <input
                        type="text"
                        id="book_search"
                        class="book-search-input"
                        placeholder="Ketik judul buku..."
                        autocomplete="off"
                        value=""
                    >


                    {{-- ID BUKU YANG DIKIRIM KE CONTROLLER --}}

                    <input
                        type="hidden"
                        name="book_id"
                        id="book_id"
                        value="{{ old('book_id') }}"
                    >


                    {{-- HASIL PENCARIAN --}}

                    <div
                        id="book_search_results"
                        class="book-search-results"
                    ></div>

                </div>


                {{-- BUKU YANG DIPILIH --}}

                <div
                    id="selected_book"
                    class="selected-book"
                >

                    📚 Buku terpilih:

                    <strong id="selected_book_name"></strong>

                    <a
                        href="javascript:void(0)"
                        id="clear_book"
                        class="clear-book"
                    >
                        Hapus
                    </a>

                </div>


                {{-- INFO --}}

                <div class="poster-book-info">

                    Ketik judul buku untuk menautkan Spanduk/poster
                    dengan buku tertentu.

                    Jika Spanduk/poster hanya merupakan media informasi umum,
                    kolom ini boleh dikosongkan.

                </div>

            </div>


            {{-- =================================================
                 JUDUL
            ================================================== --}}

            <div class="poster-form-group">

                <label for="judul">
                    Judul Pengumuman
                </label>

                <input
                    type="text"
                    name="judul"
                    id="judul"
                    value="{{ old('judul') }}"
                    placeholder="Contoh: Yuk Membaca Setiap Hari"
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
                    name="deskripsi"
                    id="deskripsi"
                    placeholder="Deskripsi singkat pengumuman..."
                >{{ old('deskripsi') }}</textarea>

            </div>


            {{-- =================================================
                 GAMBAR
            ================================================== --}}

            <div class="poster-form-group">

                <label for="posterImage">
                    Gambar Pengumuman
                </label>

                <div class="poster-upload">

                    <input
                        type="file"
                        name="gambar"
                        id="posterImage"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                    >


                    <p>
                        JPG, JPEG, PNG, WEBP — maksimal 5 MB
                    </p>


                    {{-- PREVIEW --}}

                    <div
                        class="poster-preview"
                        id="posterPreview"
                    >

                        <div class="poster-preview-title">
                            Preview Pengumuman
                        </div>

                        <img
                            id="posterPreviewImage"
                            src=""
                            alt="Preview Poster"
                        >

                    </div>

                </div>

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
                    name="urutan"
                    id="urutan"
                    value="{{ old('urutan', 0) }}"
                    min="0"
                >

                <small>
                    Angka lebih kecil akan tampil lebih dahulu.
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
                        {{ old('status', true) ? 'checked' : '' }}
                    >

                    <label
                        for="status"
                        style="
                            margin:0;
                            cursor:pointer;
                        "
                    >
                        Tampilkan pengumuman kepada siswa
                    </label>

                </div>

            </div>


            {{-- =================================================
                 ACTION
            ================================================== --}}

            <div class="poster-actions">

                <button
                    type="submit"
                    class="btn-poster-save"
                >

                    <i class="fa-solid fa-cloud-arrow-up"></i>

                    Simpan Pengumuman

                </button>


                <a
                    href="{{ route('petugas.posters.index') }}"
                    class="btn-poster-cancel"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    Batal

                </a>

            </div>


        </form>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* =====================================================
           ELEMENT BUKU
        ===================================================== */

        const bookSearch =
            document.getElementById(
                'book_search'
            );

        const bookId =
            document.getElementById(
                'book_id'
            );

        const searchResults =
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

/* =====================================================
   DATA BUKU DARI LARAVEL
===================================================== */

const books = {!! $books->toJson() !!};



        /* =====================================================
           TAMPILKAN HASIL PENCARIAN
        ===================================================== */

        function showBookResults(keyword) {

            searchResults.innerHTML = '';


            const search =
                keyword
                    .trim()
                    .toLowerCase();


            if (!search) {

                searchResults.style.display =
                    'none';

                return;
            }


            const filteredBooks =
                books.filter(function (book) {

                    const judul =
                        (book.judul || '')
                            .toLowerCase();

                    const penulis =
                        (book.penulis || '')
                            .toLowerCase();


                    return (
                        judul.includes(search) ||
                        penulis.includes(search)
                    );

                });


            if (
                filteredBooks.length === 0
            ) {

                searchResults.innerHTML = `

                    <div class="book-search-empty">

                        📚 Buku tidak ditemukan.

                    </div>

                `;

                searchResults.style.display =
                    'block';

                return;
            }


            /*
             * Maksimal 10 hasil
             */

            filteredBooks
                .slice(0, 10)
                .forEach(function (book) {


                    const item =
                        document.createElement(
                            'div'
                        );


                    item.className =
                        'book-search-item';


                    item.innerHTML = `

                        <div class="book-search-title">

                            ${escapeHtml(
                                book.judul
                            )}

                        </div>

                        ${
                            book.penulis
                            ? `
                                <div class="book-search-author">

                                    Penulis:
                                    ${escapeHtml(
                                        book.penulis
                                    )}

                                </div>
                              `
                            : ''
                        }

                    `;


                    item.addEventListener(
                        'click',
                        function () {

                            selectBook(
                                book
                            );

                        }
                    );


                    searchResults.appendChild(
                        item
                    );

                });


            searchResults.style.display =
                'block';

        }


        /* =====================================================
           PILIH BUKU
        ===================================================== */

        function selectBook(book) {

            bookId.value =
                book.id;


            bookSearch.value =
                book.judul;


            selectedBookName.textContent =
                book.judul;


            selectedBook.style.display =
                'block';


            searchResults.innerHTML =
                '';


            searchResults.style.display =
                'none';

        }


        /* =====================================================
           HAPUS BUKU
        ===================================================== */

        clearBook.addEventListener(
            'click',
            function () {

                bookId.value =
                    '';

                bookSearch.value =
                    '';

                selectedBookName.textContent =
                    '';

                selectedBook.style.display =
                    'none';

                searchResults.innerHTML =
                    '';

                searchResults.style.display =
                    'none';

                bookSearch.focus();

            }
        );


        /* =====================================================
           KETIK JUDUL
        ===================================================== */

        bookSearch.addEventListener(
            'input',
            function () {

                /*
                 * Jika petugas mengubah teks setelah
                 * memilih buku, ID lama dihapus.
                 */

                bookId.value =
                    '';

                selectedBook.style.display =
                    'none';

                showBookResults(
                    this.value
                );

            }
        );


        /* =====================================================
           FOCUS
        ===================================================== */

        bookSearch.addEventListener(
            'focus',
            function () {

                if (
                    this.value.trim() !== ''
                ) {

                    showBookResults(
                        this.value
                    );

                }

            }
        );


        /* =====================================================
           KLIK DI LUAR
        ===================================================== */

        document.addEventListener(
            'click',
            function (event) {

                if (
                    !event.target.closest(
                        '.book-search-wrapper'
                    )
                ) {

                    searchResults.style.display =
                        'none';

                }

            }
        );


        /* =====================================================
           ESCAPE HTML
        ===================================================== */

        function escapeHtml(value) {

            const div =
                document.createElement(
                    'div'
                );

            div.textContent =
                value ?? '';

            return div.innerHTML;

        }


        /* =====================================================
           RESTORE OLD BOOK
        ===================================================== */

        const oldBookId =
            @json(old('book_id'));


        if (oldBookId) {

            const oldBook =
                books.find(
                    function (book) {

                        return String(
                            book.id
                        ) === String(
                            oldBookId
                        );

                    }
                );


            if (oldBook) {

                selectBook(
                    oldBook
                );

            }

        }


        /* =====================================================
           PREVIEW GAMBAR
        ===================================================== */

        const input =
            document.getElementById(
                'posterImage'
            );

        const preview =
            document.getElementById(
                'posterPreview'
            );

        const image =
            document.getElementById(
                'posterPreviewImage'
            );


        if (
            input &&
            preview &&
            image
        ) {

            input.addEventListener(
                'change',
                function () {

                    const file =
                        this.files &&
                        this.files[0];


                    if (!file) {

                        preview.style.display =
                            'none';

                        image.src =
                            '';

                        return;

                    }


                    /* =========================================
                       VALIDASI UKURAN
                    ========================================= */

                    const maxSize =
                        5 * 1024 * 1024;


                    if (
                        file.size >
                        maxSize
                    ) {

                        alert(
                            'Ukuran gambar maksimal 5 MB.'
                        );

                        this.value =
                            '';

                        preview.style.display =
                            'none';

                        image.src =
                            '';

                        return;

                    }


                    /* =========================================
                       VALIDASI TIPE
                    ========================================= */

                    const allowedTypes = [

                        'image/jpeg',
                        'image/png',
                        'image/webp'

                    ];


                    if (
                        !allowedTypes.includes(
                            file.type
                        )
                    ) {

                        alert(
                            'Format gambar harus JPG, JPEG, PNG, atau WEBP.'
                        );

                        this.value =
                            '';

                        preview.style.display =
                            'none';

                        image.src =
                            '';

                        return;

                    }


                    /* =========================================
                       PREVIEW
                    ========================================= */

                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {

                            image.src =
                                event.target.result;

                            preview.style.display =
                                'block';

                        };


                    reader.readAsDataURL(
                        file
                    );

                }
            );

        }

    }
);

</script>

@endsection