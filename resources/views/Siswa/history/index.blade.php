
@extends('layouts.siswa')

@section('title', 'Histori Peminjaman')

@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/siswa/histori.css') }}"
>
@endpush

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | DATA USER
    |--------------------------------------------------------------------------
    */

    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | STATISTIK PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    $totalPinjam = $borrowings->count();

    $aktif = $borrowings
        ->where('status', 'dipinjam')
        ->count();

    $kembali = $borrowings
        ->where('status', 'dikembalikan')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | TERLAMBAT
    |--------------------------------------------------------------------------
    */

    $terlambat = $borrowings
        ->filter(function ($borrowing) {

            if (
                $borrowing->status !== 'dipinjam'
                ||
                !$borrowing->tanggal_jatuh_tempo
            ) {
                return false;
            }

            $tanggalJatuhTempo = \Carbon\Carbon::parse(
                $borrowing->tanggal_jatuh_tempo
            )->startOfDay();

            return now()->startOfDay()->gt($tanggalJatuhTempo);

        })
        ->count();

    /*
    |--------------------------------------------------------------------------
    | POIN
    |--------------------------------------------------------------------------
    */

    $totalPoin = $user->poin ?? 0;

    $progress = min(
        100,
        ($totalPoin / 1000) * 100
    );

@endphp

<div class="history-page">

    {{-- =========================================================
                            HERO
    ========================================================== --}}

    <section class="history-hero">

        <div class="hero-badge">
            📖 Perpustakaan Digital
        </div>

        <h1>
            Histori <span>Peminjaman</span>
        </h1>

        <p>
            Semua buku yang pernah kamu baca tersimpan
            dengan rapi di perpustakaan digital.
        </p>

        <div class="member-card">

            <div class="avatar">
                {{ strtoupper(substr($user->name ?? 'S', 0, 1)) }}
            </div>

            <div>
                <small>Anggota Aktif</small>

                <h4>
                    {{ $user->name ?? '-' }}
                </h4>
            </div>

            <span class="online-dot"></span>

        </div>

    </section>

    {{-- =========================================================
                            CONTENT
    ========================================================== --}}

    <div class="history-layout">

        {{-- =====================================================
                            LEFT
        ====================================================== --}}

        <div class="history-left">

            {{-- =================================================
                            STATISTIK
            ================================================== --}}

            <div class="stats-grid">

                <div class="stats-card">
                    <div class="icon orange">📚</div>
                    <h2>{{ $totalPinjam }}</h2>
                    <span>Total Dipinjam</span>
                </div>

                <div class="stats-card">
                    <div class="icon yellow">⏳</div>
                    <h2>{{ $aktif }}</h2>
                    <span>Sedang Aktif</span>
                </div>

                <div class="stats-card">
                    <div class="icon green">✔</div>
                    <h2>{{ $kembali }}</h2>
                    <span>Sudah Kembali</span>
                </div>

                <div class="stats-card">
                    <div class="icon red">⚠</div>
                    <h2>{{ $terlambat }}</h2>
                    <span>Terlambat</span>
                </div>

            </div>

            {{-- =================================================
                        SEARCH + FILTER
            ================================================== --}}

            <div class="history-toolbar">

                <div class="search-box">
                    <i class="fas fa-search"></i>

                    <input
                        type="text"
                        id="searchBook"
                        placeholder="Cari judul atau pengarang..."
                        autocomplete="off"
                    >
                </div>

                <div class="filter-group">

                    <button
                        type="button"
                        class="filter-btn active"
                        data-filter="all"
                    >
                        📚 Semua
                    </button>

                    <button
                        type="button"
                        class="filter-btn"
                        data-filter="dipinjam"
                    >
                        ⌛ Aktif
                    </button>

                    <button
                        type="button"
                        class="filter-btn"
                        data-filter="dikembalikan"
                    >
                        ✅ Kembali
                    </button>

                    <button
                        type="button"
                        class="filter-btn"
                        data-filter="terlambat"
                    >
                        ⚠ Terlambat
                    </button>

                </div>

            </div>

            {{-- =================================================
                            LIST BUKU
            ================================================== --}}

            <div class="history-list" id="historyList">

                @forelse($borrowings as $index => $item)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | BUKU
                        |--------------------------------------------------------------------------
                        */

                        $book = $item->book;

                        /*
                        |--------------------------------------------------------------------------
                        | DATA EKSEMPLAR
                        |--------------------------------------------------------------------------
                        */

                        $bookCopy = $item->bookCopy;

                        /*
                        |--------------------------------------------------------------------------
                        | REVIEW
                        |--------------------------------------------------------------------------
                        */

                        $review = $item->review;

                        /*
                        |--------------------------------------------------------------------------
                        | READING JOURNAL
                        |--------------------------------------------------------------------------
                        */

                        $journal = $item->readingJournal;

                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        $statusClass = 'waiting';
                        $statusText = 'Menunggu';

                        switch ($item->status) {

                            case 'dipinjam':

                                $statusClass = 'borrowed';
                                $statusText = 'Dipinjam';

                                break;

                            case 'dikembalikan':

                                $statusClass = 'returned';
                                $statusText = 'Dikembalikan';

                                break;

                            case 'menunggu':

                                $statusClass = 'waiting';
                                $statusText = 'Menunggu';

                                break;

                            case 'menunggu_pengembalian':

                                $statusClass = 'returning';
                                $statusText = 'Verifikasi';

                                break;

                            case 'ditolak':

                                $statusClass = 'reject';
                                $statusText = 'Ditolak';

                                break;

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | CEK TERLAMBAT
                        |--------------------------------------------------------------------------
                        */

                        $isTerlambat = false;

                        if (
                            $item->status === 'dipinjam'
                            &&
                            $item->tanggal_jatuh_tempo
                        ) {

                            $tanggalJatuhTempo = \Carbon\Carbon::parse(
                                $item->tanggal_jatuh_tempo
                            )->startOfDay();

                            $isTerlambat = now()
                                ->startOfDay()
                                ->gt($tanggalJatuhTempo);

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | FILTER STATUS
                        |--------------------------------------------------------------------------
                        */

                        $filterStatus = $isTerlambat
                            ? 'terlambat'
                            : $item->status;

                        /*
                        |--------------------------------------------------------------------------
                        | GENRE / KATEGORI
                        |--------------------------------------------------------------------------
                        */

                        $genre = '-';

                        if ($book) {

                            if (
                                isset($book->kategori)
                                &&
                                is_object($book->kategori)
                            ) {

                                $genre = $book->kategori->nama ?? '-';

                            } elseif (
                                isset($book->kategori)
                                &&
                                is_string($book->kategori)
                            ) {

                                $genre = $book->kategori ?: '-';

                            }

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | DURASI PEMINJAMAN
                        |--------------------------------------------------------------------------
                        */

                        $durasi = '-';

                        if ($item->tanggal_pinjam) {

                            $tanggalPinjam = \Carbon\Carbon::parse(
                                $item->tanggal_pinjam
                            );

                            if ($item->status === 'dikembalikan') {

                                if ($item->tanggal_kembali) {

                                    $tanggalAkhir = \Carbon\Carbon::parse(
                                        $item->tanggal_kembali
                                    );

                                } else {

                                    $tanggalAkhir = $item->updated_at
                                        ? \Carbon\Carbon::parse($item->updated_at)
                                        : now();

                                }

                            } else {

                                $tanggalAkhir = now();

                            }

                            $jumlahHari = $tanggalPinjam->diffInDays(
                                $tanggalAkhir
                            );

                            if ($jumlahHari < 1) {
                                $jumlahHari = 1;
                            }

                            $durasi = $jumlahHari . ' Hari';

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | COVER
                        |--------------------------------------------------------------------------
                        */

                        $cover = $book && $book->cover
                            ? asset(
                                'storage/' . ltrim($book->cover, '/')
                            )
                            : asset('images/default-book.png');

                        /*
                        |--------------------------------------------------------------------------
                        | JENIS BUKU
                        |--------------------------------------------------------------------------
                        */

                        $isLiterasi = $book
                            &&
                            strtolower(trim($book->jenis_buku ?? '')) === 'literasi';

                        /*
                        |--------------------------------------------------------------------------
                        | TOMBOL JURNAL
                        |--------------------------------------------------------------------------
                        */

                        $journalButtonText = $journal
                            ? 'Lihat / Edit Jurnal'
                            : 'Isi Jurnal';

                    @endphp

                    {{-- =================================================
                                BOOK CARD
                    ================================================== --}}

                    <div
                        class="
                            book-history-card
                            {{ $index >= 3 ? 'hidden-history' : '' }}
                        "
                        data-status="{{ $filterStatus }}"
                        data-index="{{ $index }}"
                        data-history-id="{{ $item->id }}"
                    >

                        {{-- =================================================
                                    BOOK HEADER
                        ================================================== --}}

                        <div class="book-header">

                            <div class="book-cover">

                                <img
                                    src="{{ $cover }}"
                                    alt="{{ $book->judul ?? 'Cover buku' }}"
                                    onerror="
                                        this.onerror=null;
                                        this.src='{{ asset('images/default-book.png') }}';
                                    "
                                >

                            </div>

                            <div class="book-info">

                                <h3>
                                    {{ $book->judul ?? 'Buku tidak ditemukan' }}
                                </h3>

                                <p class="author">
                                    {{ $book->penulis ?? '-' }}
                                </p>

                                <div class="book-meta">

                                    <span class="genre">
                                        {{ $genre }}
                                    </span>

                                    <span>
                                        📅
                                        {{ $item->tanggal_pinjam
                                            ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y')
                                            : '-'
                                        }}
                                    </span>

                                    <span>—</span>

                                    <span>
                                        {{ $item->tanggal_jatuh_tempo
                                            ? \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d M Y')
                                            : '-'
                                        }}
                                    </span>

                                </div>

                            </div>

                            <div class="book-status">

                                <span class="status {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>

                                <button
                                    type="button"
                                    class="toggle-detail"
                                    onclick="toggleBookDetail(this)"
                                    aria-label="Buka detail buku"
                                >
                                    <i class="fas fa-chevron-down"></i>
                                </button>

                                <button
                                    type="button"
                                    class="delete-history-btn"
                                    onclick="hideBorrowingHistory(this)"
                                    data-id="{{ $item->id }}"
                                    title="Hapus dari histori"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>

                            </div>

                        </div>

                        {{-- =================================================
                                    DETAIL BUKU
                        ================================================== --}}

                        <div class="book-detail">

                            <div class="detail-grid">

                                {{-- TANGGAL PINJAM --}}

                                <div class="detail-item">

                                    <small>📅 Tanggal Pinjam</small>

                                    <strong>
                                        {{ $item->tanggal_pinjam
                                            ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y')
                                            : '-'
                                        }}
                                    </strong>

                                </div>

                                {{-- BATAS KEMBALI --}}

                                <div class="detail-item">

                                    <small>🔄 Batas Kembali</small>

                                    <strong>
                                        {{ $item->tanggal_jatuh_tempo
                                            ? \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d M Y')
                                            : '-'
                                        }}
                                    </strong>

                                </div>

                                {{-- DURASI --}}

                                <div class="detail-item">

                                    <small>⏱ Durasi Peminjaman</small>

                                    <strong>{{ $durasi }}</strong>

                                </div>

                                {{-- GENRE --}}

                                <div class="detail-item">

                                    <small>📘 Genre</small>

                                    <strong>{{ $genre }}</strong>

                                </div>

                                {{-- =================================================
                                    INFORMASI EKSEMPLAR BUKU
                                ================================================== --}}

                                <div class="detail-item">

                                    <small>🆔 ID Eksemplar</small>

                                    <strong>
                                        {{ $bookCopy->id ?? '-' }}
                                    </strong>

                                </div>

                                <div class="detail-item">

                                    <small>📚 Kode Eksemplar</small>

                                    <strong>
                                        {{ $bookCopy->kode_eksemplar ?? '-' }}
                                    </strong>

                                </div>

                                <div class="detail-item">

                                    <small>🔢 Nomor Eksemplar</small>

                                    <strong>
                                        {{ $bookCopy->nomor_eksemplar ?? '-' }}
                                    </strong>

                                </div>

                                <div class="detail-item">

                                    <small>🗄️ Rak Eksemplar</small>

                                    <strong>
                                        {{ $bookCopy->rak ?? '-' }}
                                    </strong>

                                </div>

                                {{-- TANGGAL KEMBALI --}}

                                @if($item->tanggal_kembali)

                                    <div class="detail-item">

                                        <small>✅ Tanggal Dikembalikan</small>

                                        <strong>
                                            {{ \Carbon\Carbon::parse(
                                                $item->tanggal_kembali
                                            )->format('d M Y') }}
                                        </strong>

                                    </div>

                                @endif

                            </div>

                            {{-- =================================================
                                    TERLAMBAT
                            ================================================== --}}

                            @if(
                                $item->status === 'dipinjam'
                                &&
                                $isTerlambat
                            )

                                @php

                                    $hariTerlambat = \Carbon\Carbon::parse(
                                        $item->tanggal_jatuh_tempo
                                    )
                                    ->startOfDay()
                                    ->diffInDays(now()->startOfDay());

                                @endphp

                                <div class="late-alert">

                                    ⚠ Buku ini terlambat
                                    {{ $hariTerlambat }}
                                    hari.

                                </div>

                            @endif

                            {{-- =================================================
                                JURNAL LITERASI
                            ================================================== --}}

                            @if($isLiterasi)

                                <div class="journal-section">

                                    <div class="journal-section-header">

                                        <div>

                                            <span class="journal-mini-label">
                                                📖 JURNAL BACAAN
                                            </span>

                                            <strong>
                                                {{ $journal
                                                    ? 'Jurnal bacaan tersimpan'
                                                    : 'Sudah selesai membaca?'
                                                }}
                                            </strong>

                                            <small>

                                                @if($journal)

                                                    Jurnal sudah tersimpan.
                                                    Kamu dapat melihat atau
                                                    mengedit isinya.

                                                @else

                                                    Isi jurnal bacaanmu
                                                    sebelum mengembalikan
                                                    buku.

                                                @endif

                                            </small>

                                        </div>

                                        <button
                                            type="button"
                                            class="btn-journal-open"
                                            onclick="openJournalModal('journalModal-{{ $item->id }}')"
                                        >

                                            <i class="fas fa-book-open"></i>

                                            <span>
                                                {{ $journalButtonText }}
                                            </span>

                                        </button>

                                    </div>

                                </div>

                            @endif

                            {{-- =================================================
                                FORM PENGEMBALIAN
                            ================================================== --}}

                            @if($item->status === 'dipinjam')

                                <form
                                    action="{{ route('siswa.return.store', $item->id) }}"
                                    method="POST"
                                    class="return-form"
                                    data-borrowing-id="{{ $item->id }}"
                                    onsubmit="return confirmReturn(this)"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="borrowing_id"
                                        value="{{ $item->id }}"
                                    >

                                    {{-- RATING --}}

                                    <div class="form-group">

                                        <label>
                                            Rating Buku
                                            <span style="color:#dc2626;">*</span>
                                        </label>

                                        <select
                                            name="rating"
                                            class="review-rating"
                                            data-borrowing-id="{{ $item->id }}"
                                            required
                                        >

                                            <option value="">Pilih Rating</option>

                                            <option
                                                value="5"
                                                {{ old('rating', $review->rating ?? '') == 5 ? 'selected' : '' }}
                                            >
                                                ⭐⭐⭐⭐⭐
                                            </option>

                                            <option
                                                value="4"
                                                {{ old('rating', $review->rating ?? '') == 4 ? 'selected' : '' }}
                                            >
                                                ⭐⭐⭐⭐
                                            </option>

                                            <option
                                                value="3"
                                                {{ old('rating', $review->rating ?? '') == 3 ? 'selected' : '' }}
                                            >
                                                ⭐⭐⭐
                                            </option>

                                            <option
                                                value="2"
                                                {{ old('rating', $review->rating ?? '') == 2 ? 'selected' : '' }}
                                            >
                                                ⭐⭐
                                            </option>

                                            <option
                                                value="1"
                                                {{ old('rating', $review->rating ?? '') == 1 ? 'selected' : '' }}
                                            >
                                                ⭐
                                            </option>

                                        </select>

                                    </div>

                                    {{-- ULASAN --}}

                                    <div class="form-group">

                                        <label>
                                            Ulasan Buku
                                            <span
                                                style="
                                                    color:#94a3b8;
                                                    font-size:9px;
                                                    font-weight:500;
                                                "
                                            >
                                                (Opsional)
                                            </span>
                                        </label>

                                        <textarea
                                            name="ulasan"
                                            class="review-ulasan"
                                            data-borrowing-id="{{ $item->id }}"
                                            rows="4"
                                            maxlength="1000"
                                            placeholder="Tulis ulasan buku... (opsional)"
                                        >{{ old('ulasan', $review->ulasan ?? '') }}</textarea>

                                    </div>

                                    {{-- INFO REVIEW --}}

                                    @if($review)

                                        <div
                                            style="
                                                margin-top:8px;
                                                margin-bottom:10px;
                                                padding:9px 12px;
                                                border-radius:8px;
                                                background:#f0fdf4;
                                                color:#166534;
                                                font-size:10px;
                                            "
                                        >

                                            ✓ Rating tersimpan.

                                            Rating dapat diperbarui
                                            saat proses pengembalian.

                                        </div>

                                    @endif

                                    {{-- INFORMASI JURNAL --}}

                                    @if($isLiterasi && !$journal)

                                        <div
                                            style="
                                                margin-top:8px;
                                                margin-bottom:10px;
                                                padding:9px 12px;
                                                border-radius:8px;
                                                background:#fff7ed;
                                                color:#9a3412;
                                                border:1px solid #fed7aa;
                                                font-size:10px;
                                            "
                                        >

                                            📖 Untuk buku literasi,
                                            jurnal bacaan wajib diisi
                                            sebelum pengembalian.

                                        </div>

                                    @endif

                                    {{-- BUTTON RETURN --}}

                                    <div class="button-group">

                                        <button
                                            type="submit"
                                            class="btn-return"
                                        >

                                            <i class="fas fa-undo"></i>

                                            Kembalikan Sekarang

                                        </button>

                                    </div>

                                </form>

                            @endif

                            {{-- =================================================
                                STATUS SETELAH PENGEMBALIAN
                            ================================================== --}}

                            @if($item->status === 'dikembalikan')

                                <div class="returned-box">

                                    <div class="success-text">
                                        ✅ Buku berhasil dikembalikan
                                    </div>

                                    @if($review)

                                        <div class="review-box">

                                            <strong>Rating</strong>

                                            <div>
                                                {{ str_repeat('⭐', (int) $review->rating) }}
                                            </div>

                                            @if($review->ulasan)

                                                <p>{{ $review->ulasan }}</p>

                                            @else

                                                <p>Tidak ada ulasan.</p>

                                            @endif

                                            <div class="review-actions">

                                                <a
                                                    href="{{ route('siswa.review.edit', $review->id) }}"
                                                    class="btn-edit-review"
                                                >

                                                    <i class="fas fa-pen"></i>

                                                    Edit Ulasan

                                                </a>

                                                <form
                                                    action="{{ route('siswa.review.destroy', $review->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus rating dan ulasan ini?')"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn-delete-review"
                                                    >

                                                        <i class="fas fa-trash"></i>

                                                        Hapus

                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    @else

                                        <div class="review-box">

                                            <strong>Rating & Ulasan</strong>

                                            <p>
                                                Belum ada rating atau ulasan.
                                            </p>

                                        </div>

                                    @endif

                                </div>

                            @elseif($item->status === 'menunggu')

                                <div class="waiting-box">
                                    ⏳ Menunggu persetujuan petugas.
                                </div>

                            @elseif($item->status === 'menunggu_pengembalian')

                                <div class="waiting-box orange">

                                    📦 Pengembalian sudah diajukan.

                                    <br>

                                    Menunggu verifikasi petugas.

                                </div>

                            @elseif($item->status === 'ditolak')

                                <div class="reject-box">
                                    ❌ Permintaan peminjaman ditolak.
                                </div>

                            @endif

                        </div>

                    </div>

                    {{-- =================================================
                        MODAL READING JOURNAL
                    ================================================== --}}

                    @if($isLiterasi)

                        <div
                            id="journalModal-{{ $item->id }}"
                            class="journal-modal"
                            aria-hidden="true"
                        >

                            <div
                                class="journal-modal-overlay"
                                onclick="closeJournalModal('journalModal-{{ $item->id }}')"
                            ></div>

                            <div class="journal-modal-container">

                                <div class="journal-modal-header">

                                    <div>

                                        <span class="journal-modal-label">
                                            📖 JURNAL LITERASI
                                        </span>

                                        <h2>Jurnal Bacaan</h2>

                                        <p>
                                            {{ $book->judul ?? 'Buku' }}
                                        </p>

                                    </div>

                                    <button
                                        type="button"
                                        class="journal-modal-close"
                                        onclick="closeJournalModal('journalModal-{{ $item->id }}')"
                                        aria-label="Tutup"
                                    >

                                        <i class="fas fa-times"></i>

                                    </button>

                                </div>

                                {{-- FORM JOURNAL --}}

                                <form
                                    action="{{
                                        $journal
                                            ? route('siswa.jurnal.update', $journal->id)
                                            : route('siswa.jurnal.store', $item->id)
                                    }}"
                                    method="POST"
                                    class="student-journal-form"
                                >

                                    @csrf

                                    @if($journal)
                                        @method('PUT')
                                    @endif

                                    <input
                                        type="hidden"
                                        name="borrowing_id"
                                        value="{{ $item->id }}"
                                    >

                                    {{-- PERTANYAAN 1 --}}

                                    <div class="journal-question">

                                        <label>
                                            <span>01</span>
                                            Apa ringkasan dari buku
                                            yang kamu baca?
                                        </label>

                                        <small>
                                            Tuliskan inti cerita atau
                                            isi buku dengan bahasamu
                                            sendiri.
                                        </small>

                                        <textarea
                                            name="ringkasan"
                                            rows="5"
                                            maxlength="5000"
                                            placeholder="Tuliskan ringkasan buku..."
                                            required
                                        >{{ old('ringkasan', $journal->ringkasan ?? '') }}</textarea>

                                    </div>

                                    {{-- PERTANYAAN 2 --}}

                                    <div class="journal-question">

                                        <label>
                                            <span>02</span>
                                            Apa yang kamu pelajari
                                            dari buku ini?
                                        </label>

                                        <small>
                                            Ceritakan pengetahuan,
                                            informasi, atau nilai yang
                                            kamu dapatkan.
                                        </small>

                                        <textarea
                                            name="yang_dipelajari"
                                            rows="5"
                                            maxlength="5000"
                                            placeholder="Tuliskan hal yang kamu pelajari..."
                                            required
                                        >{{ old('yang_dipelajari', $journal->yang_dipelajari ?? '') }}</textarea>

                                    </div>

                                    {{-- PERTANYAAN 3 --}}

                                    <div class="journal-question">

                                        <label>
                                            <span>03</span>
                                            Bagian apa yang paling
                                            menarik dari buku ini?
                                        </label>

                                        <small>
                                            Jelaskan bagian, kejadian,
                                            tokoh, atau informasi yang
                                            paling berkesan bagimu.
                                        </small>

                                        <textarea
                                            name="bagian_menarik"
                                            rows="5"
                                            maxlength="5000"
                                            placeholder="Tuliskan bagian yang paling menarik..."
                                            required
                                        >{{ old('bagian_menarik', $journal->bagian_menarik ?? '') }}</textarea>

                                    </div>

                                    {{-- FOOTER --}}

                                    <div class="journal-modal-footer">

                                        <button
                                            type="button"
                                            class="btn-journal-cancel"
                                            onclick="closeJournalModal('journalModal-{{ $item->id }}')"
                                        >
                                            Batal
                                        </button>

                                        <button
                                            type="submit"
                                            class="btn-journal-submit"
                                        >

                                            <i class="fas fa-save"></i>

                                            {{ $journal ? 'Perbarui Jurnal' : 'Simpan Jurnal' }}

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    @endif

                @empty

                    <div class="empty-history">

                        <img
                            src="{{ asset('images/empty-book.svg') }}"
                            alt="Tidak ada histori"
                        >

                        <h3>Belum Ada Histori</h3>

                        <p>
                            Kamu belum pernah meminjam buku.
                        </p>

                    </div>

                @endforelse

            </div>

            {{-- =================================================
                            LIHAT SEMUA
            ================================================== --}}

            @if($borrowings->count() > 3)

                <div class="history-more">

                    <button
                        type="button"
                        id="toggleHistory"
                        class="btn-see-all"
                    >

                        <span id="toggleHistoryText">
                            Lihat Semua
                        </span>

                        <i
                            id="toggleHistoryIcon"
                            class="fas fa-chevron-down"
                        ></i>

                    </button>

                </div>

            @endif

        </div>

        {{-- =========================================================
                            SIDEBAR
        ========================================================== --}}

        <div class="history-right">

            {{-- POIN --}}

            <div class="point-card">

                <span class="point-title">
                    TOTAL POIN
                </span>

                <h2>
                    {{ number_format($totalPoin) }}
                </h2>

                <p>
                    🌟 Pembaca Aktif
                </p>

                <div class="progress">

                    <div
                        class="progress-bar"
                        style="width: {{ $progress }}%;"
                    ></div>

                </div>

                <small>
                    {{ number_format($progress, 0) }}%
                    Menuju Level Platinum
                </small>

            </div>

            {{-- HISTORI POIN --}}

            <div class="sidebar-card point-history-card">

                <div class="sidebar-title">
                    📜 Histori Poin
                </div>

                <div
                    class="history-point-list"
                    id="pointHistoryList"
                >

                    @forelse($historiPoin as $pointIndex => $poin)

                        @php

                            $isTambah = $poin->judul === 'Poin Bertambah';

                            $isPotong = $poin->judul === 'Penukaran Reward';

                            $jumlahPoin = 0;

                            if ($isTambah) {
                                $jumlahPoin = 10;
                            }

                            if ($isPotong) {

                                if (
                                    preg_match(
                                        '/(\d+)\s*poin/i',
                                        $poin->pesan,
                                        $matches
                                    )
                                ) {
                                    $jumlahPoin = (int) $matches[1];
                                }

                            }

                            $pointHidden = $pointIndex >= 4
                                ? 'point-hidden'
                                : '';

                        @endphp

                        <div
                            class="point-item {{ $pointHidden }}"
                            data-point-index="{{ $pointIndex }}"
                            data-point-id="{{ $poin->id }}"
                        >

                            <div class="point-icon {{ $isTambah ? 'success' : 'danger' }}">
                                {{ $isTambah ? '+' : '−' }}
                            </div>

                            <div class="point-info">

                                <strong>
                                    {{ $poin->judul }}
                                </strong>

                                <small>
                                    {{ $poin->pesan }}
                                </small>

                                <small>
                                    {{ $poin->created_at
                                        ? \Carbon\Carbon::parse($poin->created_at)->format('d M Y')
                                        : '-'
                                    }}
                                </small>

                            </div>

                            <div class="point-value {{ $isTambah ? 'green' : 'red' }}">

                                @if($isTambah)
                                    +{{ number_format($jumlahPoin) }}
                                @else
                                    -{{ number_format($jumlahPoin) }}
                                @endif

                            </div>

                            <button
                                type="button"
                                class="delete-point-btn"
                                onclick="hidePointHistory(this)"
                                data-id="{{ $poin->id }}"
                                title="Hapus dari histori poin"
                            >

                                <i class="fas fa-trash"></i>

                            </button>

                        </div>

                    @empty

                        <div class="empty-side">
                            Belum ada histori poin.
                        </div>

                    @endforelse

                </div>

                @if($historiPoin->count() > 4)

                    <div class="point-history-footer">

                        <button
                            type="button"
                            id="togglePointHistory"
                            class="btn-see-all point-see-all"
                        >

                            <span id="togglePointHistoryText">
                                Lihat Semua
                            </span>

                            <i
                                id="togglePointHistoryIcon"
                                class="fas fa-chevron-down"
                            ></i>

                        </button>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

{{-- ================================================================
                            JAVASCRIPT
================================================================ --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | LOCAL STORAGE
        |--------------------------------------------------------------------------
        */

        let deletedBorrowings = JSON.parse(
            localStorage.getItem('deletedBorrowingHistory')
        ) || [];

        let deletedPoints = JSON.parse(
            localStorage.getItem('deletedPointHistory')
        ) || [];

        /*
        |--------------------------------------------------------------------------
        | HAPUS HISTORI PEMINJAMAN DARI TAMPILAN
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.book-history-card')
            .forEach(function (card) {

                const button = card.querySelector('.delete-history-btn');

                if (!button) {
                    return;
                }

                const id = String(button.dataset.id);

                if (deletedBorrowings.includes(id)) {
                    card.remove();
                }

            });

        /*
        |--------------------------------------------------------------------------
        | HAPUS HISTORI POIN DARI TAMPILAN
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.point-item')
            .forEach(function (item) {

                const button = item.querySelector('.delete-point-btn');

                if (!button) {
                    return;
                }

                const id = String(button.dataset.id);

                if (deletedPoints.includes(id)) {
                    item.remove();
                }

            });

        /*
        |--------------------------------------------------------------------------
        | RESTORE DRAFT RATING + ULASAN
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.return-form')
            .forEach(function (form) {

                const borrowingId = form.dataset.borrowingId;

                if (!borrowingId) {
                    return;
                }

                const storageKey = 'reviewDraft_' + borrowingId;

                const savedDraft = localStorage.getItem(storageKey);

                if (!savedDraft) {
                    return;
                }

                try {

                    const draft = JSON.parse(savedDraft);

                    const rating = form.querySelector('.review-rating');

                    const ulasan = form.querySelector('.review-ulasan');

                    if (
                        rating &&
                        draft.rating !== undefined
                    ) {
                        rating.value = draft.rating;
                    }

                    if (
                        ulasan &&
                        draft.ulasan !== undefined
                    ) {
                        ulasan.value = draft.ulasan;
                    }

                } catch (error) {

                    console.error(
                        'Draft review tidak valid:',
                        error
                    );

                }

            });

        /*
        |--------------------------------------------------------------------------
        | SIMPAN OTOMATIS DRAFT RATING
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.review-rating')
            .forEach(function (select) {

                select.addEventListener('change', function () {
                    saveReviewDraft(this);
                });

            });

        /*
        |--------------------------------------------------------------------------
        | SIMPAN OTOMATIS DRAFT ULASAN
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.review-ulasan')
            .forEach(function (textarea) {

                textarea.addEventListener('input', function () {
                    saveReviewDraft(this);
                });

            });

        /*
        |--------------------------------------------------------------------------
        | HISTORI BUKU
        |--------------------------------------------------------------------------
        */

        let showAllBooks = false;

        const toggleHistory = document.getElementById('toggleHistory');

        const toggleHistoryText = document.getElementById('toggleHistoryText');

        const toggleHistoryIcon = document.getElementById('toggleHistoryIcon');

        const hiddenBookCards = document.querySelectorAll('.hidden-history');

        if (toggleHistory) {

            toggleHistory.addEventListener('click', function () {

                showAllBooks = !showAllBooks;

                hiddenBookCards.forEach(function (card) {

                    if (document.body.contains(card)) {

                        card.style.display = showAllBooks
                            ? 'block'
                            : 'none';

                    }

                });

                if (showAllBooks) {

                    toggleHistoryText.textContent = 'Sembunyikan';

                    toggleHistoryIcon.className = 'fas fa-chevron-up';

                    toggleHistory.classList.add('show-all');

                } else {

                    toggleHistoryText.textContent = 'Lihat Semua';

                    toggleHistoryIcon.className = 'fas fa-chevron-down';

                    toggleHistory.classList.remove('show-all');

                }

            });

        }

        /*
        |--------------------------------------------------------------------------
        | HISTORI POIN
        |--------------------------------------------------------------------------
        */

        let showAllPoints = false;

        const togglePointHistory = document.getElementById('togglePointHistory');

        const togglePointHistoryText = document.getElementById('togglePointHistoryText');

        const togglePointHistoryIcon = document.getElementById('togglePointHistoryIcon');

        const hiddenPointItems = document.querySelectorAll('.point-hidden');

        if (togglePointHistory) {

            togglePointHistory.addEventListener('click', function () {

                showAllPoints = !showAllPoints;

                hiddenPointItems.forEach(function (item) {

                    if (document.body.contains(item)) {

                        item.style.display = showAllPoints
                            ? 'flex'
                            : 'none';

                    }

                });

                if (showAllPoints) {

                    togglePointHistoryText.textContent = 'Sembunyikan';

                    togglePointHistoryIcon.className = 'fas fa-chevron-up';

                    togglePointHistory.classList.add('show-all');

                } else {

                    togglePointHistoryText.textContent = 'Lihat Semua';

                    togglePointHistoryIcon.className = 'fas fa-chevron-down';

                    togglePointHistory.classList.remove('show-all');

                }

            });

        }

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        const filterButtons = document.querySelectorAll('.filter-btn');

        const bookCards = document.querySelectorAll('.book-history-card');

        filterButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });

                this.classList.add('active');

                const filter = this.dataset.filter;

                bookCards.forEach(function (card) {

                    if (!document.body.contains(card)) {
                        return;
                    }

                    const status = card.dataset.status;

                    const index = parseInt(card.dataset.index);

                    if (filter === 'all') {

                        if (
                            index >= 3 &&
                            !showAllBooks
                        ) {
                            card.style.display = 'none';
                        } else {
                            card.style.display = 'block';
                        }

                        return;

                    }

                    card.style.display = status === filter
                        ? 'block'
                        : 'none';

                });

            });

        });

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        const searchBook = document.getElementById('searchBook');

        if (searchBook) {

            searchBook.addEventListener('input', function () {

                const keyword = this.value
                    .toLowerCase()
                    .trim();

                bookCards.forEach(function (card) {

                    if (!document.body.contains(card)) {
                        return;
                    }

                    const text = card.innerText.toLowerCase();

                    const index = parseInt(card.dataset.index);

                    if (keyword === '') {

                        if (
                            index >= 3 &&
                            !showAllBooks
                        ) {
                            card.style.display = 'none';
                        } else {
                            card.style.display = 'block';
                        }

                        return;

                    }

                    card.style.display = text.includes(keyword)
                        ? 'block'
                        : 'none';

                });

            });

        }

    }
);

/*
|--------------------------------------------------------------------------
| SIMPAN DRAFT REVIEW
|--------------------------------------------------------------------------
*/

function saveReviewDraft(element)
{

    const form = element.closest('.return-form');

    if (!form) {
        return;
    }

    const borrowingId = form.dataset.borrowingId;

    if (!borrowingId) {
        return;
    }

    const rating = form.querySelector('.review-rating');

    const ulasan = form.querySelector('.review-ulasan');

    const draft = {

        rating: rating
            ? rating.value
            : '',

        ulasan: ulasan
            ? ulasan.value
            : ''

    };

    localStorage.setItem(
        'reviewDraft_' + borrowingId,
        JSON.stringify(draft)
    );

}

/*
|--------------------------------------------------------------------------
| KONFIRMASI PENGEMBALIAN
|--------------------------------------------------------------------------
*/

function confirmReturn(form)
{

    const rating = form.querySelector('.review-rating');

    const ulasan = form.querySelector('.review-ulasan');

    if (
        !rating ||
        !rating.value
    ) {

        alert('Silakan pilih rating buku terlebih dahulu.');

        return false;

    }

    const card = form.closest('.book-history-card');

    if (card) {

        const journalSection = card.querySelector('.journal-section');

        if (journalSection) {

            const journalButton = journalSection.querySelector('.btn-journal-open');

            const journalButtonText = journalButton
                ? journalButton.innerText.toLowerCase()
                : '';

            if (journalButtonText.includes('isi jurnal')) {

                alert('Silakan isi dan simpan jurnal bacaan terlebih dahulu.');

                return false;

            }

        }

    }

    let pesanKonfirmasi =
        'Apakah kamu yakin ingin mengajukan pengembalian buku ini?\n\n' +
        'Rating akan disimpan bersama proses pengembalian.';

    if (
        ulasan &&
        ulasan.value.trim()
    ) {
        pesanKonfirmasi += '\nUlasan juga akan disimpan.';
    }

    pesanKonfirmasi +=
        '\n\nSetelah dikirim, status menjadi "Menunggu Verifikasi".';

    const confirmed = confirm(pesanKonfirmasi);

    if (!confirmed) {
        return false;
    }

    return true;

}

/*
|--------------------------------------------------------------------------
| DETAIL BUKU
|--------------------------------------------------------------------------
*/

function toggleBookDetail(button)
{

    const card = button.closest('.book-history-card');

    if (!card) {
        return;
    }

    card.classList.toggle('open');

}

/*
|--------------------------------------------------------------------------
| BUKA MODAL JOURNAL
|--------------------------------------------------------------------------
*/

function openJournalModal(modalId)
{

    const modal = document.getElementById(modalId);

    if (!modal) {

        console.error(
            'Modal jurnal tidak ditemukan:',
            modalId
        );

        return;

    }

    modal.classList.add('show');

    modal.setAttribute('aria-hidden', 'false');

    modal.style.display = 'flex';

    document.body.classList.add('journal-modal-open');

    document.body.style.overflow = 'hidden';

}

/*
|--------------------------------------------------------------------------
| TUTUP MODAL
|--------------------------------------------------------------------------
*/

function closeJournalModal(modalId)
{

    const modal = document.getElementById(modalId);

    if (!modal) {
        return;
    }

    modal.classList.remove('show');

    modal.setAttribute('aria-hidden', 'true');

    modal.style.display = 'none';

    document.body.classList.remove('journal-modal-open');

    document.body.style.overflow = '';

}

/*
|--------------------------------------------------------------------------
| ESC UNTUK MENUTUP MODAL
|--------------------------------------------------------------------------
*/

document.addEventListener('keydown', function (event) {

    if (event.key !== 'Escape') {
        return;
    }

    document
        .querySelectorAll('.journal-modal.show')
        .forEach(function (modal) {

            closeJournalModal(modal.id);

        });

});

/*
|--------------------------------------------------------------------------
| HAPUS HISTORI PEMINJAMAN DARI TAMPILAN
|--------------------------------------------------------------------------
*/

function hideBorrowingHistory(button)
{

    const id = String(button.dataset.id);

    const card = button.closest('.book-history-card');

    if (
        !id ||
        !card
    ) {
        return;
    }

    const confirmed = confirm(
        'Hapus histori peminjaman ini dari tampilan?\n\n' +
        'Data peminjaman tetap tersimpan di database.'
    );

    if (!confirmed) {
        return;
    }

    let deletedBorrowings = JSON.parse(
        localStorage.getItem('deletedBorrowingHistory')
    ) || [];

    if (!deletedBorrowings.includes(id)) {
        deletedBorrowings.push(id);
    }

    localStorage.setItem(
        'deletedBorrowingHistory',
        JSON.stringify(deletedBorrowings)
    );

    card.classList.add('history-item-removing');

    setTimeout(function () {
        card.remove();
    }, 250);

}

/*
|--------------------------------------------------------------------------
| HAPUS HISTORI POIN
|--------------------------------------------------------------------------
*/

function hidePointHistory(button)
{

    const id = String(button.dataset.id);

    const item = button.closest('.point-item');

    if (
        !id ||
        !item
    ) {
        return;
    }

    const confirmed = confirm(
        'Hapus histori poin ini dari tampilan?\n\n' +
        'Data poin tetap tersimpan di database.'
    );

    if (!confirmed) {
        return;
    }

    let deletedPoints = JSON.parse(
        localStorage.getItem('deletedPointHistory')
    ) || [];

    if (!deletedPoints.includes(id)) {
        deletedPoints.push(id);
    }

    localStorage.setItem(
        'deletedPointHistory',
        JSON.stringify(deletedPoints)
    );

    item.classList.add('history-item-removing');

    setTimeout(function () {
        item.remove();
    }, 250);

}

</script>

{{-- ================================================================
                            STYLE JURNAL
================================================================ --}}

<style>

.journal-section {
    margin-top: 18px;
    margin-bottom: 17px;
    padding: 13px;
    border: 1px solid #fed7aa;
    border-radius: 11px;
    background: #fffaf5;
}

.journal-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.journal-mini-label {
    display: block;
    margin-bottom: 3px;
    color: #ea580c;
    font-size: 8px;
    font-weight: 900;
    letter-spacing: .7px;
}

.journal-section-header strong {
    display: block;
    margin-bottom: 3px;
    color: #1e293b;
    font-size: 11px;
    font-weight: 800;
}

.journal-section-header small {
    display: block;
    color: #64748b;
    font-size: 9px;
    line-height: 1.5;
}

.btn-journal-open {
    position: relative;
    z-index: 20;
    min-width: 145px;
    min-height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 0 13px;
    flex-shrink: 0;
    border: 1px solid #f97316;
    border-radius: 8px;
    background: #fff7ed;
    color: #ea580c;
    font-family: inherit;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.btn-journal-open:hover {
    background: #f97316;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 5px 12px rgba(249, 115, 22, .16);
}

.journal-modal {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.journal-modal.show {
    display: flex;
}

.journal-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, .58);
    backdrop-filter: blur(3px);
}

.journal-modal-container {
    position: relative;
    z-index: 2;
    width: min(700px, 100%);
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    background: #ffffff;
    border: 1px solid #fed7aa;
    border-radius: 17px;
    box-shadow: 0 25px 70px rgba(15, 23, 42, .25);
    animation: journalModalIn .22s ease;
}

@keyframes journalModalIn {
    from {
        opacity: 0;
        transform: translateY(15px) scale(.97);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.journal-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    padding: 20px 22px;
    border-bottom: 1px solid #f1f5f9;
}

.journal-modal-label {
    display: block;
    margin-bottom: 4px;
    color: #ea580c;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .8px;
}

.journal-modal-header h2 {
    margin: 0;
    color: #1e293b;
    font-size: 20px;
    font-weight: 900;
}

.journal-modal-header p {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 10px;
}

.journal-modal-close {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 50%;
    background: #f8fafc;
    color: #64748b;
    cursor: pointer;
}

.journal-modal-close:hover {
    background: #fee2e2;
    color: #dc2626;
}

.journal-question {
    padding: 18px 22px 0;
}

.journal-question label {
    display: block;
    color: #1e293b;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.5;
}

.journal-question label span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    margin-right: 7px;
    border-radius: 7px;
    background: #fff7ed;
    color: #ea580c;
    font-size: 9px;
    font-weight: 900;
}

.journal-question small {
    display: block;
    margin: 7px 0 8px 33px;
    color: #94a3b8;
    font-size: 9px;
    line-height: 1.5;
}

.journal-question textarea {
    width: 100%;
    box-sizing: border-box;
    resize: vertical;
    min-height: 110px;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #ffffff;
    color: #1e293b;
    font-family: inherit;
    font-size: 10px;
    line-height: 1.6;
    outline: none;
    transition: .2s ease;
}

.journal-question textarea:focus {
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, .08);
}

.journal-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 20px 22px;
}

.btn-journal-cancel {
    min-height: 38px;
    padding: 0 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
    color: #64748b;
    font-family: inherit;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
}

.btn-journal-submit {
    min-height: 38px;
    padding: 0 17px;
    border: 0;
    border-radius: 8px;
    background: #f97316;
    color: #ffffff;
    font-family: inherit;
    font-size: 10px;
    font-weight: 800;
    cursor: pointer;
    transition: .2s ease;
}

.btn-journal-submit:hover {
    background: #ea580c;
    transform: translateY(-1px);
}

.history-item-removing {
    opacity: 0;
    transform: translateX(15px);
    transition: opacity .25s ease, transform .25s ease;
}

@media (max-width: 600px) {

    .journal-section-header {
        align-items: stretch;
        flex-direction: column;
    }

    .btn-journal-open {
        width: 100%;
    }

    .journal-modal {
        padding: 10px;
    }

    .journal-modal-container {
        max-height: calc(100vh - 20px);
        border-radius: 14px;
    }

    .journal-modal-header {
        padding: 16px;
    }

    .journal-question {
        padding: 15px 16px 0;
    }

    .journal-modal-footer {
        padding: 16px;
    }

}

</style>

@endsection
