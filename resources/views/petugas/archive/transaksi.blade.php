@extends('layouts.petugas')

@section('title', 'Arsip Transaksi')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/arsip-transaksi.css') }}">
@endsection

@section('content')

<div class="arsip-transaksi-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="arsip-transaksi-header">

        <div class="arsip-transaksi-header-content">

            <span class="arsip-transaksi-label">
                <i class="fa-solid fa-box-archive"></i>
                Arsip Perpustakaan
            </span>

            <h3>Arsip Transaksi Peminjaman</h3>

            <p>
                Menampilkan seluruh riwayat peminjaman dan
                pengembalian buku yang tersimpan pada sistem.
            </p>

        </div>

        <a
            href="{{ route('petugas.archive') }}"
            class="arsip-transaksi-back"
        >
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

    </div>


    {{-- =====================================================
         FILTER
    ====================================================== --}}
    <div class="arsip-transaksi-filter-card">

        <form
            action="{{ route('petugas.archive.transaksi') }}"
            method="GET"
            class="arsip-transaksi-filter-form"
        >

            {{-- =================================================
                 PENCARIAN
            ================================================== --}}
            <div class="arsip-transaksi-filter-search">

                <label for="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Pencarian
                </label>

                <div class="arsip-transaksi-search-box">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari siswa atau buku..."
                        autocomplete="off"
                    >

                </div>

            </div>


            {{-- =================================================
                 STATUS
            ================================================== --}}
            <div class="arsip-transaksi-filter-group">

                <label for="status">
                    <i class="fa-solid fa-filter"></i>
                    Status
                </label>

                <select
                    name="status"
                    id="status"
                    class="arsip-transaksi-select"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="menunggu"
                        {{ request('status') === 'menunggu' ? 'selected' : '' }}
                    >
                        Menunggu Verifikasi
                    </option>

                    <option
                        value="dipinjam"
                        {{ request('status') === 'dipinjam' ? 'selected' : '' }}
                    >
                        Sedang Dipinjam
                    </option>

                    <option
                        value="menunggu_pengembalian"
                        {{ request('status') === 'menunggu_pengembalian' ? 'selected' : '' }}
                    >
                        Menunggu Pengembalian
                    </option>

                    <option
                        value="dikembalikan"
                        {{ request('status') === 'dikembalikan' ? 'selected' : '' }}
                    >
                        Dikembalikan
                    </option>

                    <option
                        value="ditolak"
                        {{ request('status') === 'ditolak' ? 'selected' : '' }}
                    >
                        Ditolak
                    </option>

                </select>

            </div>


            {{-- =================================================
                 JENIS BUKU
            ================================================== --}}
            <div class="arsip-transaksi-filter-group">

                <label for="jenis">

                    <i class="fa-solid fa-book"></i>

                    Jenis Buku

                </label>

                <select
                    name="jenis"
                    id="jenis"
                    class="arsip-transaksi-select"
                >

                    <option value="">
                        Semua Jenis
                    </option>

                    <option
                        value="literasi"
                        {{ request('jenis') === 'literasi' ? 'selected' : '' }}
                    >
                        Literasi
                    </option>

                    <option
                        value="paket"
                        {{ request('jenis') === 'paket' ? 'selected' : '' }}
                    >
                        Paket
                    </option>

                </select>

            </div>

{{-- =================================================
     FILTER TANGGAL
================================================== --}}

<div class="arsip-transaksi-filter-group arsip-transaksi-date-group">

    <label>
        <i class="fa-regular fa-calendar"></i>
        Tanggal
    </label>

    <div class="arsip-transaksi-date-inputs">

        {{-- TANGGAL --}}
        <select
            name="hari"
            id="hari"
            class="arsip-transaksi-select"
        >
            <option value="">Tanggal</option>

            @for($i = 1; $i <= 31; $i++)
                <option
                    value="{{ $i }}"
                    {{ request('hari') == $i ? 'selected' : '' }}
                >
                    {{ sprintf('%02d', $i) }}
                </option>
            @endfor

        </select>


        {{-- BULAN --}}
        <select
            name="bulan"
            id="bulan"
            class="arsip-transaksi-select"
        >
            <option value="">Bulan</option>

            @php
                $bulan = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ];
            @endphp

            @foreach($bulan as $nomor => $nama)
                <option
                    value="{{ $nomor }}"
                    {{ request('bulan') == $nomor ? 'selected' : '' }}
                >
                    {{ $nama }}
                </option>
            @endforeach

        </select>


        {{-- TAHUN --}}
        <select
            name="tahun"
            id="tahun"
            class="arsip-transaksi-select"
        >
            <option value="">Tahun</option>

            @for($tahun = date('Y'); $tahun >= 2020; $tahun--)
                <option
                    value="{{ $tahun }}"
                    {{ request('tahun') == $tahun ? 'selected' : '' }}
                >
                    {{ $tahun }}
                </option>
            @endfor

        </select>

    </div>

</div>


            {{-- =================================================
                 TOMBOL CARI
            ================================================== --}}
            <button
                type="submit"
                class="arsip-transaksi-filter-button"
            >

                <i class="fa-solid fa-magnifying-glass"></i>

                <span>Cari</span>

            </button>


            {{-- =================================================
                 RESET
            ================================================== --}}
            <a
                href="{{ route('petugas.archive.transaksi') }}"
                class="arsip-transaksi-reset-button"
            >

                <i class="fa-solid fa-rotate-right"></i>

                <span>Reset</span>

            </a>

        </form>


        {{-- =====================================================
             HASIL FILTER
        ====================================================== --}}
        <div class="arsip-transaksi-filter-result">

            <i class="fa-solid fa-circle-info"></i>

            <span>

                Menampilkan

                <strong>
                    {{ $borrowings->count() }}
                </strong>

                transaksi


                {{-- SEARCH --}}
                @if(request('search'))

                    untuk pencarian

                    <strong>
                        "{{ request('search') }}"
                    </strong>

                @endif


                {{-- STATUS --}}
                @if(request('status'))

                    dengan status

                    <strong>

                        @switch(request('status'))

                            @case('menunggu')
                                Menunggu Verifikasi
                                @break

                            @case('dipinjam')
                                Sedang Dipinjam
                                @break

                            @case('menunggu_pengembalian')
                                Menunggu Pengembalian
                                @break

                            @case('dikembalikan')
                                Dikembalikan
                                @break

                            @case('ditolak')
                                Ditolak
                                @break

                            @default
                                {{ request('status') }}

                        @endswitch

                    </strong>

                @endif


                {{-- JENIS --}}
                @if(request('jenis'))

                    dengan jenis

                    <strong>

                        @switch(request('jenis'))

                            @case('literasi')
                                Literasi
                                @break

                            @case('paket')
                                Paket
                                @break

                            @default
                                {{ request('jenis') }}

                        @endswitch

                    </strong>

                @endif

{{-- TANGGAL --}}
@if(request('hari') || request('bulan') || request('tahun'))

    pada

    <strong>

        @if(request('hari'))
            {{ sprintf('%02d', request('hari')) }}
        @endif

        @if(request('bulan'))
            {{ $bulan[request('bulan')] ?? '' }}
        @endif

        @if(request('tahun'))
            {{ request('tahun') }}
        @endif

    </strong>

@endif


                {{-- SEMUA --}}
              @if(
    !request('search') &&
    !request('status') &&
    !request('jenis') &&
    !request('hari') &&
    !request('bulan') &&
    !request('tahun')
)

                    dari semua transaksi

                @endif

            </span>

        </div>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}
    <div class="arsip-transaksi-table-card">

        {{-- TABLE HEADER --}}
        <div class="arsip-transaksi-table-header">

            <div>

                <span class="arsip-transaksi-section-label">

                    <i class="fa-solid fa-clock-rotate-left"></i>

                    Riwayat Transaksi

                </span>

                <h4>
                    Daftar Arsip Peminjaman
                </h4>

            </div>


            <div class="arsip-transaksi-header-actions">

                {{-- TOTAL --}}
                <div class="arsip-transaksi-total">

                    <span>Total</span>

                    <strong>
                        {{ $borrowings->count() }}
                    </strong>

                    <small>
                        transaksi
                    </small>

                </div>


                {{-- EXPORT --}}
                <a
                    href="{{ route(
                        'petugas.archive.transaksi.export',
                        request()->query()
                    ) }}"
                    class="arsip-transaksi-download-button"
                    title="Unduh laporan transaksi"
                >

                    <i class="fa-solid fa-file-excel"></i>

                    <span>
                        Unduh Excel
                    </span>

                </a>

            </div>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}
        <div class="arsip-transaksi-table-wrapper">

            <table class="arsip-transaksi-table">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Nama Siswa</th>

                        <th>Judul Buku</th>

                        <th>Jenis</th>

                        <th>Tanggal Pinjam</th>

                        <th>Tanggal Kembali</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($borrowings as $key => $item)

                        <tr>

                            {{-- NO --}}
                            <td class="arsip-transaksi-number">

                                {{ $key + 1 }}

                            </td>


                            {{-- SISWA --}}
                            <td class="arsip-transaksi-student">

                                <div class="arsip-transaksi-person">

                                    <span class="arsip-transaksi-avatar">

                                        <i class="fa-solid fa-user"></i>

                                    </span>

                                    <span>

                                        {{ $item->user->name ?? '-' }}

                                    </span>

                                </div>

                            </td>


                            {{-- BUKU --}}
                            <td class="arsip-transaksi-book">

                                <div class="arsip-transaksi-book-name">

                                    <i class="fa-solid fa-book"></i>

                                    <span>

                                        {{ $item->book->judul ?? '-' }}

                                    </span>

                                </div>

                            </td>


                            {{-- =================================================
                                 JENIS
                            ================================================== --}}
                            <td class="arsip-transaksi-type">

                                @if(($item->book->jenis_buku ?? '') === 'literasi')

                                    <span class="arsip-transaksi-type-badge literasi">

                                        <i class="fa-solid fa-book-open"></i>

                                        Literasi

                                    </span>

                                @elseif(($item->book->jenis_buku ?? '') === 'paket')

                                    <span class="arsip-transaksi-type-badge paket">

                                        <i class="fa-solid fa-book"></i>

                                        Paket

                                    </span>

                                @else

                                    <span class="arsip-transaksi-type-badge default">

                                        -

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 TANGGAL PINJAM
                            ================================================== --}}
                            <td class="arsip-transaksi-date">

                                @if($item->tanggal_pinjam)

                                    <span class="date-icon orange">

                                        <i class="fa-regular fa-calendar"></i>

                                    </span>

                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- =================================================
                                 TANGGAL KEMBALI
                            ================================================== --}}
                            <td class="arsip-transaksi-date">

                                @if($item->tanggal_kembali)

                                    <span class="date-icon green">

                                        <i class="fa-regular fa-calendar-check"></i>

                                    </span>

                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}

                                @else

                                    <span class="date-empty">

                                        <i class="fa-regular fa-clock"></i>

                                        Belum kembali

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td>

                                @switch($item->status)

                                    @case('menunggu')

                                        <span class="arsip-transaksi-status waiting">

                                            <i class="fa-solid fa-hourglass-half"></i>

                                            Menunggu Verifikasi

                                        </span>

                                        @break


                                    @case('dipinjam')

                                        <span class="arsip-transaksi-status success">

                                            <i class="fa-solid fa-book-open"></i>

                                            Sedang Dipinjam

                                        </span>

                                        @break


                                    @case('menunggu_pengembalian')

                                        <span class="arsip-transaksi-status warning">

                                            <i class="fa-solid fa-clock"></i>

                                            Menunggu Pengembalian

                                        </span>

                                        @break


                                    @case('dikembalikan')

                                        <span class="arsip-transaksi-status info">

                                            <i class="fa-solid fa-circle-check"></i>

                                            Dikembalikan

                                        </span>

                                        @break


                                    @case('ditolak')

                                        <span class="arsip-transaksi-status danger">

                                            <i class="fa-solid fa-circle-xmark"></i>

                                            Ditolak

                                        </span>

                                        @break


                                    @default

                                        <span class="arsip-transaksi-status default">

                                            {{ $item->status ?? '-' }}

                                        </span>

                                @endswitch

                            </td>

                        </tr>


                    @empty

                        {{-- EMPTY STATE --}}
                        <tr>

                            <td
                                colspan="7"
                                class="arsip-transaksi-empty"
                            >

                                <span class="arsip-transaksi-empty-icon">

                                    <i class="fa-solid fa-box-open"></i>

                                </span>

                                <strong>
                                    Tidak ada transaksi
                                </strong>

                                <span>
                                    Tidak ditemukan transaksi
                                    sesuai dengan filter yang dipilih.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection