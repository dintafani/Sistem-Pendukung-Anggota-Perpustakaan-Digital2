@extends('layouts.siswa')

@section('title', 'Histori Peminjaman')

@push('styles')
<style>
    * {
        box-sizing: border-box;
    }

    .history-page {
        padding: 25px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .history-header {
        margin-bottom: 25px;
    }

    .history-header h1 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        color: #2f2a26;
    }

    .history-header p {
        margin: 0;
        color: #777;
        font-size: 14px;
    }

    /*
    |--------------------------------------------------------------------------
    | ALERT
    |--------------------------------------------------------------------------
    */

    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .alert-success {
        background: #e9f8ef;
        color: #237a43;
        border: 1px solid #bfe8ce;
    }

    .alert-error {
        background: #fff0f0;
        color: #c0392b;
        border: 1px solid #f2c1bd;
    }

    .validation-error {
        background: #fff0f0;
        color: #c0392b;
        border: 1px solid #f2c1bd;
        padding: 15px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .validation-error ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    /*
    |--------------------------------------------------------------------------
    | STATISTIK
    |--------------------------------------------------------------------------
    */

    .history-statistics {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #eee;
        box-shadow: 0 5px 18px rgba(0, 0, 0, .05);
    }

    .stat-card .stat-label {
        font-size: 13px;
        color: #777;
        margin-bottom: 7px;
    }

    .stat-card .stat-value {
        font-size: 27px;
        font-weight: 800;
        color: #2f2a26;
    }

    /*
    |--------------------------------------------------------------------------
    | CARD PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    .borrowings-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .borrowing-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 6px 22px rgba(0, 0, 0, .05);
    }

    .borrowing-top {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: flex-start;
    }

    .book-information {
        display: flex;
        gap: 16px;
        min-width: 0;
    }

    .book-cover {
        width: 85px;
        height: 115px;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
        background: #f2f2f2;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
        font-size: 12px;
        text-align: center;
        padding: 8px;
    }

    .book-info h3 {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 800;
        color: #302b27;
    }

    .book-info p {
        margin: 4px 0;
        font-size: 13px;
        color: #777;
    }

    .book-type {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 8px;
    }

    .book-type.literasi {
        background: #e8f8ee;
        color: #24824a;
    }

    .book-type.paket {
        background: #fff1df;
        color: #b86c19;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-menunggu {
        background: #fff5d9;
        color: #a66b00;
    }

    .status-dipinjam {
        background: #e8f1ff;
        color: #2864b8;
    }

    .status-menunggu-pengembalian {
        background: #f3eaff;
        color: #7b3fb2;
    }

    .status-dikembalikan {
        background: #e8f8ee;
        color: #24824a;
    }

    .status-ditolak {
        background: #ffeaea;
        color: #c0392b;
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL TANGGAL
    |--------------------------------------------------------------------------
    */

    .borrowing-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid #eee;
    }

    .detail-item {
        background: #fafafa;
        border-radius: 11px;
        padding: 12px;
    }

    .detail-item span {
        display: block;
        font-size: 11px;
        color: #999;
        margin-bottom: 4px;
    }

    .detail-item strong {
        font-size: 13px;
        color: #3a342f;
    }

    /*
    |--------------------------------------------------------------------------
    | DUE DATE
    |--------------------------------------------------------------------------
    */

    .due-warning {
        margin-top: 15px;
        padding: 11px 14px;
        border-radius: 10px;
        background: #fff5dd;
        color: #9b6500;
        font-size: 12px;
        font-weight: 700;
    }

    .due-late {
        margin-top: 15px;
        padding: 11px 14px;
        border-radius: 10px;
        background: #ffe9e9;
        color: #c0392b;
        font-size: 12px;
        font-weight: 700;
    }

    /*
    |--------------------------------------------------------------------------
    | REVIEW
    |--------------------------------------------------------------------------
    */

    .saved-review {
        margin-top: 18px;
        padding: 16px;
        border-radius: 12px;
        background: #fffaf0;
        border: 1px solid #f1dfb5;
    }

    .saved-review-title {
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #59472d;
    }

    .rating-display {
        color: #f3a600;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .saved-review p {
        margin: 0;
        color: #6f665c;
        font-size: 13px;
        line-height: 1.6;
    }

    /*
    |--------------------------------------------------------------------------
    | JOURNAL
    |--------------------------------------------------------------------------
    */

    .saved-journal {
        margin-top: 15px;
        padding: 16px;
        border-radius: 12px;
        background: #f6fbf8;
        border: 1px solid #d8eee0;
    }

    .saved-journal-title {
        font-size: 13px;
        font-weight: 800;
        color: #287145;
        margin-bottom: 12px;
    }

    .journal-item {
        margin-bottom: 10px;
    }

    .journal-item:last-child {
        margin-bottom: 0;
    }

    .journal-item strong {
        display: block;
        font-size: 12px;
        color: #555;
        margin-bottom: 3px;
    }

    .journal-item p {
        margin: 0;
        color: #777;
        font-size: 12px;
        line-height: 1.6;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTION
    |--------------------------------------------------------------------------
    */

    .borrowing-actions {
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn {
        border: none;
        border-radius: 10px;
        padding: 11px 17px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: .2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn-return {
        background: #287b4d;
        color: #fff;
    }

    .btn-return:hover {
        background: #21663f;
    }

    .btn-disabled {
        background: #eee;
        color: #999;
        cursor: not-allowed;
    }

    /*
    |--------------------------------------------------------------------------
    | EMPTY
    |--------------------------------------------------------------------------
    */

    .empty-history {
        background: #fff;
        border: 1px dashed #ddd;
        border-radius: 18px;
        padding: 60px 20px;
        text-align: center;
    }

    .empty-history h3 {
        margin: 0 0 8px;
        color: #444;
    }

    .empty-history p {
        margin: 0;
        color: #888;
        font-size: 14px;
    }

    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    .return-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, .55);
    }

    .return-modal.active {
        display: flex;
    }

    .return-modal-box {
        width: 100%;
        max-width: 720px;
        max-height: 92vh;
        overflow-y: auto;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
    }

    .return-modal-header {
        padding: 20px 22px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 2;
    }

    .return-modal-header h2 {
        margin: 0;
        font-size: 19px;
        font-weight: 800;
        color: #302b27;
    }

    .modal-close {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: none;
        background: #f2f2f2;
        color: #555;
        font-size: 20px;
        cursor: pointer;
    }

    .return-modal-body {
        padding: 22px;
    }

    .modal-book {
        display: flex;
        gap: 14px;
        padding: 14px;
        background: #fafafa;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .modal-book-cover {
        width: 58px;
        height: 78px;
        border-radius: 8px;
        overflow: hidden;
        background: #eee;
        flex-shrink: 0;
    }

    .modal-book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-book-info {
        align-self: center;
    }

    .modal-book-info strong {
        display: block;
        color: #333;
        margin-bottom: 5px;
        font-size: 15px;
    }

    .modal-book-info span {
        font-size: 12px;
        color: #888;
    }

    .form-section {
        margin-bottom: 22px;
    }

    .form-section-title {
        font-size: 15px;
        font-weight: 800;
        color: #342f2b;
        margin-bottom: 13px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        font-weight: 800;
        color: #514a44;
    }

    .required {
        color: #d33;
    }

    .form-control {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 11px 13px;
        font-family: inherit;
        font-size: 13px;
        color: #333;
        outline: none;
        background: #fff;
        transition: .2s ease;
    }

    .form-control:focus {
        border-color: #5a9f73;
        box-shadow: 0 0 0 3px rgba(90, 159, 115, .1);
    }

    textarea.form-control {
        min-height: 105px;
        resize: vertical;
        line-height: 1.6;
    }

    .rating-wrapper {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 4px;
    }

    .rating-wrapper input {
        display: none;
    }

    .rating-wrapper label {
        font-size: 31px;
        color: #ddd;
        cursor: pointer;
        margin: 0;
        transition: .15s ease;
    }

    .rating-wrapper label:hover,
    .rating-wrapper label:hover ~ label,
    .rating-wrapper input:checked ~ label {
        color: #f4b400;
    }

    .journal-note {
        padding: 12px 14px;
        border-radius: 10px;
        background: #edf8f1;
        color: #287145;
        font-size: 12px;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .package-note {
        padding: 12px 14px;
        border-radius: 10px;
        background: #fff6e8;
        color: #98631c;
        font-size: 12px;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .return-modal-footer {
        padding: 18px 22px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        position: sticky;
        bottom: 0;
        background: #fff;
    }

    .btn-cancel {
        background: #eee;
        color: #555;
    }

    .btn-submit-return {
        background: #287b4d;
        color: #fff;
    }

    .btn-submit-return:hover {
        background: #21663f;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 900px) {
        .history-statistics {
            grid-template-columns: repeat(2, 1fr);
        }

        .borrowing-top {
            flex-direction: column;
        }

        .borrowing-details {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .history-page {
            padding: 15px;
        }

        .history-statistics {
            grid-template-columns: 1fr;
        }

        .book-information {
            align-items: flex-start;
        }

        .book-cover {
            width: 70px;
            height: 95px;
        }

        .return-modal {
            padding: 10px;
        }

        .return-modal-box {
            max-height: 95vh;
            border-radius: 16px;
        }

        .return-modal-header,
        .return-modal-body,
        .return-modal-footer {
            padding: 16px;
        }

        .return-modal-footer {
            flex-direction: column;
        }

        .return-modal-footer .btn {
            width: 100%;
        }
    }
</style>
@endpush


@section('content')

<div class="history-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="history-header">
        <h1>Histori Peminjaman</h1>

        <p>
            Lihat seluruh riwayat peminjaman buku dan lakukan
            pengembalian buku yang masih dipinjam.
        </p>
    </div>


    {{-- =========================================================
         PESAN SUCCESS
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- =========================================================
         PESAN ERROR
    ========================================================== --}}

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif


    {{-- =========================================================
         ERROR VALIDASI
    ========================================================== --}}

    @if($errors->any())

        <div class="validation-error">

            <strong>
                Pengembalian belum dapat diproses:
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    {{-- =========================================================
         STATISTIK
    ========================================================== --}}

    @php

        $totalPinjam = $borrowings->count();

        $aktif = $borrowings
            ->whereIn('status', [
                'menunggu',
                'dipinjam',
                'menunggu_pengembalian'
            ])
            ->count();

        $dikembalikan = $borrowings
            ->where('status', 'dikembalikan')
            ->count();

        $ditolak = $borrowings
            ->where('status', 'ditolak')
            ->count();

    @endphp


    <div class="history-statistics">

        <div class="stat-card">
            <div class="stat-label">
                Total Peminjaman
            </div>

            <div class="stat-value">
                {{ $totalPinjam }}
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-label">
                Peminjaman Aktif
            </div>

            <div class="stat-value">
                {{ $aktif }}
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-label">
                Sudah Dikembalikan
            </div>

            <div class="stat-value">
                {{ $dikembalikan }}
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-label">
                Ditolak
            </div>

            <div class="stat-value">
                {{ $ditolak }}
            </div>
        </div>

    </div>


    {{-- =========================================================
         DAFTAR HISTORI
    ========================================================== --}}

    @if($borrowings->count() > 0)

        <div class="borrowings-list">

            @foreach($borrowings as $borrowing)

                @php

                    $book = $borrowing->book;

                    $jenisBuku = optional($book)->jenis_buku;

                    $isLiterasi =
                        $jenisBuku === 'literasi';

                    $isPaket =
                        $jenisBuku === 'paket';

                    $canReturn =
                        $borrowing->status === 'dipinjam';

                    $statusClass = match($borrowing->status) {

                        'menunggu'
                            => 'status-menunggu',

                        'dipinjam'
                            => 'status-dipinjam',

                        'menunggu_pengembalian'
                            => 'status-menunggu-pengembalian',

                        'dikembalikan'
                            => 'status-dikembalikan',

                        'ditolak'
                            => 'status-ditolak',

                        default
                            => 'status-menunggu',

                    };

                    $statusText = match($borrowing->status) {

                        'menunggu'
                            => 'Menunggu Persetujuan',

                        'dipinjam'
                            => 'Sedang Dipinjam',

                        'menunggu_pengembalian'
                            => 'Menunggu Verifikasi Pengembalian',

                        'dikembalikan'
                            => 'Sudah Dikembalikan',

                        'ditolak'
                            => 'Ditolak',

                        default
                            => ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $borrowing->status
                                )
                            ),

                    };

                @endphp


                <div class="borrowing-card">

                    {{-- =================================================
                         INFORMASI BUKU
                    ================================================== --}}

                    <div class="borrowing-top">

                        <div class="book-information">

                            <div class="book-cover">

                                @if(
                                    $book &&
                                    !empty($book->cover)
                                )

                                    <img
                                        src="{{ asset('storage/' . ltrim($book->cover, '/')) }}"
                                        alt="{{ $book->judul }}"
                                        onerror="this.style.display='none';"
                                    >

                                @elseif(
                                    $book &&
                                    !empty($book->gambar)
                                )

                                    <img
                                        src="{{ asset('storage/' . ltrim($book->gambar, '/')) }}"
                                        alt="{{ $book->judul }}"
                                        onerror="this.style.display='none';"
                                    >

                                @else

                                    <div class="book-placeholder">
                                        Tidak ada sampul
                                    </div>

                                @endif

                            </div>


                            <div class="book-info">

                                <h3>
                                    {{ optional($book)->judul ?? 'Buku tidak ditemukan' }}
                                </h3>

                                @if($book)

                                    <p>
                                        <strong>
                                            Penulis:
                                        </strong>

                                        {{ $book->penulis ?? '-' }}
                                    </p>

                                    <p>
                                        <strong>
                                            Penerbit:
                                        </strong>

                                        {{ $book->penerbit ?? '-' }}
                                    </p>

                                @endif


                                @if($isLiterasi)

                                    <span class="book-type literasi">
                                        Buku Literasi
                                    </span>

                                @elseif($isPaket)

                                    <span class="book-type paket">
                                        Buku Paket
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- STATUS --}}

                        <div>

                            <span class="status-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>

                        </div>

                    </div>


                    {{-- =================================================
                         DETAIL PEMINJAMAN
                    ================================================== --}}

                    <div class="borrowing-details">

                        <div class="detail-item">

                            <span>
                                Tanggal Pinjam
                            </span>

                            <strong>
                                {{ optional($borrowing->tanggal_pinjam)->format('d-m-Y') ?? '-' }}
                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Jatuh Tempo
                            </span>

                            <strong>
                                {{ optional($borrowing->tanggal_jatuh_tempo)->format('d-m-Y') ?? '-' }}
                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Tanggal Kembali
                            </span>

                            <strong>
                                {{ optional($borrowing->tanggal_kembali)->format('d-m-Y') ?? '-' }}
                            </strong>

                        </div>

                    </div>


                    {{-- =================================================
                         INFORMASI JATUH TEMPO
                    ================================================== --}}

                    @if(
                        $borrowing->tanggal_jatuh_tempo &&
                        in_array(
                            $borrowing->status,
                            [
                                'dipinjam',
                                'menunggu_pengembalian'
                            ],
                            true
                        )
                    )

                        @php

                            $today =
                                \Carbon\Carbon::now()
                                    ->startOfDay();

                            $dueDate =
                                \Carbon\Carbon::parse(
                                    $borrowing->tanggal_jatuh_tempo
                                )
                                ->startOfDay();

                        @endphp


                        @if($today->greaterThan($dueDate))

                            <div class="due-late">

                                Buku ini terlambat
                                {{ $dueDate->diffInDays($today) }}
                                hari.

                            </div>

                        @elseif($today->equalTo($dueDate))

                            <div class="due-warning">

                                Hari ini adalah batas
                                pengembalian buku.

                            </div>

                        @else

                            <div class="due-warning">

                                Batas pengembalian:
                                {{ $dueDate->format('d-m-Y') }}

                            </div>

                        @endif

                    @endif


                    {{-- =================================================
                         REVIEW YANG SUDAH TERSIMPAN
                    ================================================== --}}

                    @if($borrowing->review)

                        <div class="saved-review">

                            <div class="saved-review-title">
                                Rating & Ulasan
                            </div>

                            <div class="rating-display">

                                @for(
                                    $star = 1;
                                    $star <= 5;
                                    $star++
                                )

                                    @if(
                                        $star <=
                                        (int) $borrowing->review->rating
                                    )

                                        ★

                                    @else

                                        ☆

                                    @endif

                                @endfor

                                {{ $borrowing->review->rating }}/5

                            </div>


                            @if(
                                !empty(
                                    $borrowing->review->ulasan
                                )
                            )

                                <p>
                                    {{ $borrowing->review->ulasan }}
                                </p>

                            @else

                                <p>
                                    Tidak ada ulasan.
                                </p>

                            @endif

                        </div>

                    @endif


                    {{-- =================================================
                         JURNAL YANG SUDAH TERSIMPAN
                    ================================================== --}}

                    @if(
                        $borrowing->readingJournal
                    )

                        <div class="saved-journal">

                            <div class="saved-journal-title">
                                Jurnal Literasi
                            </div>


                            <div class="journal-item">

                                <strong>
                                    Ringkasan
                                </strong>

                                <p>
                                    {{ $borrowing->readingJournal->ringkasan }}
                                </p>

                            </div>


                            <div class="journal-item">

                                <strong>
                                    Yang Dipelajari
                                </strong>

                                <p>
                                    {{ $borrowing->readingJournal->yang_dipelajari }}
                                </p>

                            </div>


                            <div class="journal-item">

                                <strong>
                                    Bagian Menarik
                                </strong>

                                <p>
                                    {{ $borrowing->readingJournal->bagian_menarik }}
                                </p>

                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                         ACTION
                    ================================================== --}}

                    <div class="borrowing-actions">

                        @if($canReturn)

                            <button
                                type="button"
                                class="btn btn-return"
                                onclick="openReturnModal({{ $borrowing->id }})"
                            >
                                Kembalikan Sekarang
                            </button>

                        @elseif(
                            $borrowing->status ===
                            'menunggu_pengembalian'
                        )

                            <span class="btn btn-disabled">
                                Menunggu Verifikasi Petugas
                            </span>

                        @elseif(
                            $borrowing->status ===
                            'menunggu'
                        )

                            <span class="btn btn-disabled">
                                Menunggu Persetujuan Petugas
                            </span>

                        @elseif(
                            $borrowing->status ===
                            'dikembalikan'
                        )

                            <span class="btn btn-disabled">
                                Buku Sudah Dikembalikan
                            </span>

                        @endif

                    </div>

                </div>


                {{-- =====================================================
                     MODAL PENGEMBALIAN
                ====================================================== --}}

                @if($canReturn)

                    <div
                        class="return-modal"
                        id="returnModal{{ $borrowing->id }}"
                    >

                        <div class="return-modal-box">


                            {{-- HEADER MODAL --}}

                            <div class="return-modal-header">

                                <h2>
                                    Pengembalian Buku
                                </h2>

                                <button
                                    type="button"
                                    class="modal-close"
                                    onclick="closeReturnModal({{ $borrowing->id }})"
                                >
                                    ×
                                </button>

                            </div>


                            {{-- BODY MODAL --}}

                            <div class="return-modal-body">


                                {{-- INFORMASI BUKU --}}

                                <div class="modal-book">

                                    <div class="modal-book-cover">

                                        @if(
                                            $book &&
                                            !empty($book->cover)
                                        )

                                            <img
                                                src="{{ asset('storage/' . ltrim($book->cover, '/')) }}"
                                                alt="{{ $book->judul }}"
                                                onerror="this.style.display='none';"
                                            >

                                        @elseif(
                                            $book &&
                                            !empty($book->gambar)
                                        )

                                            <img
                                                src="{{ asset('storage/' . ltrim($book->gambar, '/')) }}"
                                                alt="{{ $book->judul }}"
                                                onerror="this.style.display='none';"
                                            >

                                        @endif

                                    </div>


                                    <div class="modal-book-info">

                                        <strong>
                                            {{ optional($book)->judul }}
                                        </strong>

                                        <span>

                                            @if($isLiterasi)

                                                Buku Literasi

                                            @elseif($isPaket)

                                                Buku Paket

                                            @endif

                                        </span>

                                    </div>

                                </div>


                                {{-- =================================================
                                     FORM PENGEMBALIAN
                                ================================================== --}}

                                <form
                                    action="{{ route('siswa.borrowings.return', $borrowing->id) }}"
                                    method="POST"
                                >

                                    @csrf


                                    {{-- =================================================
                                         JURNAL LITERASI
                                    ================================================== --}}

                                    @if($isLiterasi)

                                        <div class="form-section">

                                            <div class="form-section-title">
                                                Jurnal Literasi
                                            </div>


                                            <div class="journal-note">

                                                Karena buku ini merupakan
                                                <strong>buku literasi</strong>,
                                                jurnal literasi wajib diisi
                                                sebelum buku dikembalikan.

                                            </div>


                                            {{-- RINGKASAN --}}

                                            <div class="form-group">

                                                <label>
                                                    Ringkasan
                                                    <span class="required">
                                                        *
                                                    </span>
                                                </label>

                                                <textarea
                                                    name="ringkasan"
                                                    class="form-control"
                                                    placeholder="Tuliskan ringkasan isi buku yang kamu baca..."
                                                    required
                                                ></textarea>

                                            </div>


                                            {{-- YANG DIPELAJARI --}}

                                            <div class="form-group">

                                                <label>
                                                    Yang Dipelajari
                                                    <span class="required">
                                                        *
                                                    </span>
                                                </label>

                                                <textarea
                                                    name="yang_dipelajari"
                                                    class="form-control"
                                                    placeholder="Tuliskan hal atau pengetahuan yang kamu pelajari dari buku ini..."
                                                    required
                                                ></textarea>

                                            </div>


                                            {{-- BAGIAN MENARIK --}}

                                            <div class="form-group">

                                                <label>
                                                    Bagian Menarik
                                                    <span class="required">
                                                        *
                                                    </span>
                                                </label>

                                                <textarea
                                                    name="bagian_menarik"
                                                    class="form-control"
                                                    placeholder="Tuliskan bagian yang paling menarik menurut kamu..."
                                                    required
                                                ></textarea>

                                            </div>

                                        </div>

                                    @else

                                        <div class="form-section">

                                            <div class="package-note">

                                                Buku ini merupakan
                                                <strong>buku paket</strong>.
                                                Jurnal literasi tidak diperlukan.
                                                Silakan langsung memberikan
                                                rating dan ulasan.

                                            </div>

                                        </div>

                                    @endif


                                    {{-- =================================================
                                         RATING
                                    ================================================== --}}

                                    <div class="form-section">

                                        <div class="form-section-title">

                                            Rating Buku

                                        </div>


                                        <div class="form-group">

                                            <label>
                                                Berikan Rating
                                                <span class="required">
                                                    *
                                                </span>
                                            </label>


                                            <div class="rating-wrapper">

                                                <input
                                                    type="radio"
                                                    id="rating5_{{ $borrowing->id }}"
                                                    name="rating"
                                                    value="5"
                                                    required
                                                >

                                                <label
                                                    for="rating5_{{ $borrowing->id }}"
                                                    title="5 - Sangat Baik"
                                                >
                                                    ★
                                                </label>


                                                <input
                                                    type="radio"
                                                    id="rating4_{{ $borrowing->id }}"
                                                    name="rating"
                                                    value="4"
                                                >

                                                <label
                                                    for="rating4_{{ $borrowing->id }}"
                                                    title="4 - Baik"
                                                >
                                                    ★
                                                </label>


                                                <input
                                                    type="radio"
                                                    id="rating3_{{ $borrowing->id }}"
                                                    name="rating"
                                                    value="3"
                                                >

                                                <label
                                                    for="rating3_{{ $borrowing->id }}"
                                                    title="3 - Cukup"
                                                >
                                                    ★
                                                </label>


                                                <input
                                                    type="radio"
                                                    id="rating2_{{ $borrowing->id }}"
                                                    name="rating"
                                                    value="2"
                                                >

                                                <label
                                                    for="rating2_{{ $borrowing->id }}"
                                                    title="2 - Kurang"
                                                >
                                                    ★
                                                </label>


                                                <input
                                                    type="radio"
                                                    id="rating1_{{ $borrowing->id }}"
                                                    name="rating"
                                                    value="1"
                                                >

                                                <label
                                                    for="rating1_{{ $borrowing->id }}"
                                                    title="1 - Sangat Kurang"
                                                >
                                                    ★
                                                </label>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                         ULASAN
                                    ================================================== --}}

                                    <div class="form-section">

                                        <div class="form-section-title">

                                            Ulasan Buku

                                        </div>


                                        <div class="form-group">

                                            <label>
                                                Ulasan
                                                <span
                                                    style="
                                                        font-weight:400;
                                                        color:#999;
                                                    "
                                                >
                                                    (opsional)
                                                </span>
                                            </label>


                                            <textarea
                                                name="ulasan"
                                                class="form-control"
                                                maxlength="1000"
                                                placeholder="Tuliskan pendapat atau pengalaman kamu setelah membaca buku ini..."
                                            ></textarea>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                         FOOTER MODAL
                                    ================================================== --}}

                                    <div class="return-modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-cancel"
                                            onclick="closeReturnModal({{ $borrowing->id }})"
                                        >
                                            Batal
                                        </button>


                                        <button
                                            type="submit"
                                            class="btn btn-submit-return"
                                        >
                                            Kembalikan Sekarang
                                        </button>

                                    </div>


                                </form>

                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    @else

        <div class="empty-history">

            <h3>
                Belum Ada Histori Peminjaman
            </h3>

            <p>
                Kamu belum memiliki transaksi peminjaman buku.
            </p>

        </div>

    @endif

</div>


{{-- =============================================================
     JAVASCRIPT MODAL
============================================================= --}}

<script>

    function openReturnModal(id) {

        const modal =
            document.getElementById(
                'returnModal' + id
            );

        if (!modal) {
            return;
        }

        modal.classList.add('active');

        document.body.style.overflow = 'hidden';
    }


    function closeReturnModal(id) {

        const modal =
            document.getElementById(
                'returnModal' + id
            );

        if (!modal) {
            return;
        }

        modal.classList.remove('active');

        document.body.style.overflow = '';
    }


    /*
    |--------------------------------------------------------------------------
    | KLIK AREA GELAP UNTUK MENUTUP MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function(event) {

            if (
                event.target.classList.contains(
                    'return-modal'
                )
            ) {

                event.target.classList.remove(
                    'active'
                );

                document.body.style.overflow = '';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ESC UNTUK MENUTUP MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key === 'Escape') {

                document
                    .querySelectorAll(
                        '.return-modal.active'
                    )
                    .forEach(function(modal) {

                        modal.classList.remove(
                            'active'
                        );

                    });

                document.body.style.overflow = '';

            }

        }
    );

</script>

@endsection