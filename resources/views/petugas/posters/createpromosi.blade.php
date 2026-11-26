
@extends('layouts.petugas')

@section('title', 'Tambah Promosi Buku')

@section('styles')
<style>
    .promo-form-page {
        max-width: 900px;
        margin: 0 auto;
    }

    .promo-form-card {
        background: #fff;
        border: 2px solid #111827;
        border-radius: 22px;
        padding: 30px;
        box-shadow: 8px 8px 0 #111827;
    }

    .promo-form-header {
        margin-bottom: 28px;
    }

    .promo-form-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 900;
        color: #111827;
    }

    .promo-form-header p {
        margin: 7px 0 0;
        color: #6b7280;
        line-height: 1.6;
    }

    .promo-form-group {
        margin-bottom: 22px;
    }

    .promo-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
        color: #111827;
    }

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
        background: #fff;
        color: #111827;
    }

    .book-search-input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 4px #ffedd5;
    }

    .book-search-results {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        z-index: 1000;
        background: #fff;
        border: 2px solid #111827;
        border-radius: 12px;
        box-shadow: 5px 5px 0 #111827;
        max-height: 250px;
        overflow-y: auto;
        display: none;
    }

    .book-search-item {
        padding: 13px 15px;
        cursor: pointer;
        border-bottom: 1px solid #e5e7eb;
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

    .book-search-empty {
        padding: 14px 15px;
        font-size: 13px;
        color: #6b7280;
        text-align: center;
    }

    .selected-book {
        display: none;
        margin-top: 12px;
        padding: 14px;
        border-radius: 12px;
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

    .promo-info {
        margin-top: 12px;
        padding: 13px 15px;
        border-radius: 10px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        font-size: 13px;
        line-height: 1.6;
    }

    .promo-actions {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-promo-save,
    .btn-promo-cancel {
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
    }

    .btn-promo-save {
        background: #218568;
        color: #fff;
        box-shadow: 4px 4px 0 #111827;
    }

    .btn-promo-save:hover {
        background: #176b53;
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0 #111827;
    }

    .btn-promo-cancel {
        background: #fff;
        color: #111827;
    }

    @media(max-width: 600px) {
        .promo-form-card {
            padding: 20px;
            border-radius: 18px;
            box-shadow: 5px 5px 0 #111827;
        }

        .promo-form-header h1 {
            font-size: 24px;
        }

        .promo-actions {
            flex-direction: column;
        }

        .btn-promo-save,
        .btn-promo-cancel {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>
@endsection

@section('content')

<div class="promo-form-page">
    <div class="promo-form-card">

        <div class="promo-form-header">
            <h1>📚 Tambah Promosi Buku</h1>
            <p>
                Pilih buku dari koleksi perpustakaan yang ingin
                ditampilkan sebagai promosi kepada siswa.
            </p>
        </div>

        @if($errors->any())
            <div style="background:#fee2e2;border:2px solid #dc2626;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin-bottom:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('petugas.posters.store') }}"
            method="POST"
        >
            @csrf

            <input type="hidden" name="jenis" value="promosi">

            <div class="promo-form-group">

                <label for="book_search">
                    📚 Pilih Buku
                </label>

                <div class="book-search-wrapper">

                    <input
                        type="text"
                        id="book_search"
                        class="book-search-input"
                        placeholder="Ketik judul atau nama penulis buku..."
                        autocomplete="off"
                    >

                    <input
                        type="hidden"
                        name="book_id"
                        id="book_id"
                        value="{{ old('book_id') }}"
                    >

                    <div
                        id="book_search_results"
                        class="book-search-results"
                    ></div>

                </div>

                <div id="selected_book" class="selected-book">
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

                <div class="promo-info">
                    Cover dan judul promosi akan otomatis menggunakan
                    data buku yang sudah tersimpan di koleksi perpustakaan.
                    Kamu tidak perlu mengunggah gambar atau mengisi deskripsi.
                </div>

            </div>

            <div class="promo-actions">

                <button
                    type="submit"
                    class="btn-promo-save"
                >
                    <i class="fa-solid fa-book-open"></i>
                    Simpan Promosi
                </button>

                <a
                    href="{{ route('petugas.posters.index') }}"
                    class="btn-promo-cancel"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                    Batal
                </a>

            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const bookSearch = document.getElementById('book_search');
    const bookId = document.getElementById('book_id');
    const searchResults = document.getElementById('book_search_results');
    const selectedBook = document.getElementById('selected_book');
    const selectedBookName = document.getElementById('selected_book_name');
    const clearBook = document.getElementById('clear_book');

    const books = @json($books);

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function selectBook(book) {
        bookId.value = book.id;
        bookSearch.value = book.judul;
        selectedBookName.textContent = book.judul;
        selectedBook.style.display = 'block';
        searchResults.innerHTML = '';
        searchResults.style.display = 'none';
    }

    function showBookResults(keyword) {

        searchResults.innerHTML = '';

        const search = keyword.trim().toLowerCase();

        if (!search) {
            searchResults.style.display = 'none';
            return;
        }

        const filteredBooks = books.filter(function (book) {
            const judul = (book.judul || '').toLowerCase();
            const penulis = (book.penulis || '').toLowerCase();

            return judul.includes(search) || penulis.includes(search);
        });

        if (filteredBooks.length === 0) {
            searchResults.innerHTML =
                '<div class="book-search-empty">📚 Buku tidak ditemukan.</div>';

            searchResults.style.display = 'block';
            return;
        }

        filteredBooks.slice(0, 10).forEach(function (book) {

            const item = document.createElement('div');
            item.className = 'book-search-item';

            item.innerHTML = `
                <div class="book-search-title">
                    ${escapeHtml(book.judul)}
                </div>
                <div class="book-search-author">
                    Penulis: ${escapeHtml(book.penulis || '-')}
                </div>
            `;

            item.addEventListener('click', function () {
                selectBook(book);
            });

            searchResults.appendChild(item);
        });

        searchResults.style.display = 'block';
    }

    bookSearch.addEventListener('input', function () {
        bookId.value = '';
        selectedBook.style.display = 'none';
        showBookResults(this.value);
    });

    bookSearch.addEventListener('focus', function () {
        if (this.value.trim() !== '') {
            showBookResults(this.value);
        }
    });

    clearBook.addEventListener('click', function () {
        bookId.value = '';
        bookSearch.value = '';
        selectedBookName.textContent = '';
        selectedBook.style.display = 'none';
        searchResults.innerHTML = '';
        searchResults.style.display = 'none';
        bookSearch.focus();
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.book-search-wrapper')) {
            searchResults.style.display = 'none';
        }
    });

    const oldBookId = @json(old('book_id'));

    if (oldBookId) {
        const oldBook = books.find(function (book) {
            return String(book.id) === String(oldBookId);
        });

        if (oldBook) {
            selectBook(oldBook);
        }
    }

});
</script>

@endsection
