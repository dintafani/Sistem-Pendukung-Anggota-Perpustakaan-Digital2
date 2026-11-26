@extends('layouts.petugas')

@section('title', 'Daftar Hadir')

@section('content')

<div class="attendance-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="page-header">

        <div class="page-header-left">

            <div class="page-icon">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>

            <div>
                <h1>Daftar Hadir</h1>

                <p>
                    Riwayat pengunjung perpustakaan dan aktivitas kunjungan.
                </p>
            </div>

        </div>

        {{-- EXPORT --}}
        <a
            href="{{ route('petugas.attendance.export', request()->query()) }}"
            class="export-btn"
            title="Unduh laporan daftar hadir"
        >
            <i class="fa-solid fa-file-excel"></i>
            <span>Unduh Excel</span>
        </a>

    </div>


    {{-- =====================================================
        STATISTIK
    ====================================================== --}}
    <div class="attendance-stat">

        {{-- =================================================
            TOTAL PENGUNJUNG
        ================================================== --}}
        <div class="stat-card">

            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="stat-info">

                <span>
                    Total Pengunjung
                </span>

                <strong>
                    {{ $attendances->total() }}
                </strong>

            </div>

        </div>


        {{-- =================================================
            SEMUA KUNJUNGAN
            KLIK → HALAMAN SHOW
        ================================================== --}}
        <a
            href="{{ route('petugas.attendance.all') }}"
            class="stat-card all-visits-card"
            title="Lihat seluruh data kunjungan"
        >

            <div class="stat-icon all-visits-icon">

                <i class="fa-solid fa-chart-column"></i>

            </div>

            <div class="stat-info">

                <span>
                    Semua Kunjungan
                </span>

                <strong>
                    {{ $allVisits }}
                </strong>

            </div>

            <div class="stat-arrow">

                <i class="fa-solid fa-chevron-right"></i>

            </div>

        </a>

    </div>


    {{-- =====================================================
        CARD RIWAYAT
    ====================================================== --}}
    <div class="attendance-card">

        {{-- =================================================
            HEADER
        ================================================== --}}
        <div class="card-header">

            <div>

                <h2>
                    Riwayat Daftar Hadir
                </h2>

                <p>
                    Secara default menampilkan kunjungan selama
                    7 hari terakhir.
                </p>

            </div>

        </div>


        {{-- =================================================
            FILTER
        ================================================== --}}
        <form
            action="{{ route('petugas.attendance.index') }}"
            method="GET"
            class="filter-form"
        >

            {{-- SEARCH --}}
            <div class="filter-group search-group">

                <label for="search">
                    Pencarian
                </label>

                <div class="search-wrapper">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, instansi, kelas atau tujuan..."
                        class="filter-input search-input"
                    >

                </div>

            </div>


            {{-- =================================================
                JENIS PENGUNJUNG
            ================================================== --}}
            <div class="filter-group">

                <label for="jenis_pengunjung">
                    Jenis Pengunjung
                </label>

                <select
                    name="jenis_pengunjung"
                    id="jenis_pengunjung"
                    class="filter-input"
                >

                    <option value="">
                        Semua
                    </option>

                    <option
                        value="Siswa"
                        {{ request('jenis_pengunjung') == 'Siswa' ? 'selected' : '' }}
                    >
                        Siswa
                    </option>

                    <option
                        value="Guru"
                        {{ request('jenis_pengunjung') == 'Guru' ? 'selected' : '' }}
                    >
                        Guru
                    </option>

                    <option
                        value="Petugas"
                        {{ request('jenis_pengunjung') == 'Petugas' ? 'selected' : '' }}
                    >
                        Petugas
                    </option>

                    <option
                        value="Magang/PKL"
                        {{ request('jenis_pengunjung') == 'Magang/PKL' ? 'selected' : '' }}
                    >
                        Magang/PKL
                    </option>

                    <option
                        value="Tamu/Kunjungan"
                        {{ request('jenis_pengunjung') == 'Tamu/Kunjungan' ? 'selected' : '' }}
                    >
                        Tamu/Kunjungan
                    </option>

                    <option
                        value="Lainnya"
                        {{ request('jenis_pengunjung') == 'Lainnya' ? 'selected' : '' }}
                    >
                        Lainnya
                    </option>

                </select>

            </div>


            {{-- =================================================
                TANGGAL
            ================================================== --}}
            <div class="filter-group">

                <label for="tanggal">
                    Tanggal
                </label>

                <select
                    name="tanggal"
                    id="tanggal"
                    class="filter-input"
                >

                    <option value="">
                        Tanggal
                    </option>

                    @for($i = 1; $i <= 31; $i++)

                        <option
                            value="{{ $i }}"
                            {{ request('tanggal') == $i ? 'selected' : '' }}
                        >
                            {{ $i }}
                        </option>

                    @endfor

                </select>

            </div>


            {{-- =================================================
                BULAN
            ================================================== --}}
            <div class="filter-group">

                <label for="bulan">
                    Bulan
                </label>

                <select
                    name="bulan"
                    id="bulan"
                    class="filter-input"
                >

                    <option value="">
                        Bulan
                    </option>

                    @php

                        $months = [
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

                    @foreach($months as $number => $month)

                        <option
                            value="{{ $number }}"
                            {{ request('bulan') == $number ? 'selected' : '' }}
                        >
                            {{ $month }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- =================================================
                TAHUN
            ================================================== --}}
            <div class="filter-group">

                <label for="tahun">
                    Tahun
                </label>

                <input
                    type="number"
                    name="tahun"
                    id="tahun"
                    value="{{ request('tahun') }}"
                    min="2000"
                    max="{{ now()->year }}"
                    placeholder="Tahun"
                    class="filter-input"
                >

            </div>


            {{-- =================================================
                BUTTON
            ================================================== --}}
            <div class="filter-actions">

                <button
                    type="submit"
                    class="filter-btn"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Cari
                </button>

                <a
                    href="{{ route('petugas.attendance.index') }}"
                    class="reset-btn"
                >
                    <i class="fa-solid fa-rotate-right"></i>
                    Reset
                </a>

            </div>

        </form>


        {{-- =================================================
            FILTER INFO
        ================================================== --}}
        @if(
            request()->filled('search') ||
            request()->filled('jenis_pengunjung') ||
            request()->filled('tanggal') ||
            request()->filled('bulan') ||
            request()->filled('tahun')
        )

            <div class="active-filter">

                <div class="active-filter-left">

                    <i class="fa-solid fa-circle-info"></i>

                    <span>
                        Menampilkan data berdasarkan filter yang dipilih.
                    </span>

                </div>

                <a
                    href="{{ route('petugas.attendance.index') }}"
                >
                    Hapus Filter
                </a>

            </div>

        @else

            <div class="default-info">

                <i class="fa-solid fa-calendar-week"></i>

                <span>
                    Menampilkan daftar hadir selama
                    <strong>7 hari terakhir</strong>.
                </span>

            </div>

        @endif


        {{-- =================================================
            TABLE
        ================================================== --}}
        @if($attendances->count() > 0)

            <div class="table-wrapper">

                <table class="attendance-table">

                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Jenis Pengunjung
                            </th>

                            <th>
                                Instansi
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th>
                                Tujuan
                            </th>

                            <th>
                                Waktu Masuk
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($attendances as $attendance)

                            <tr>

                                {{-- NOMOR --}}
                                <td class="number-cell">

                                    {{ $attendances->firstItem() + $loop->index }}

                                </td>


                                {{-- NAMA --}}
                                <td>

                                    <div class="visitor-name">

                                        <div class="visitor-avatar">

                                            <i class="fa-solid fa-user"></i>

                                        </div>

                                        <span>
                                            {{ $attendance->nama }}
                                        </span>

                                    </div>

                                </td>


                                {{-- JENIS PENGUNJUNG --}}
                                <td>

                                    @php

                                        $badgeClass = match(
                                            $attendance->jenis_pengunjung
                                        ) {

                                            'Siswa' =>
                                                'badge-siswa',

                                            'Guru' =>
                                                'badge-guru',

                                            'Petugas' =>
                                                'badge-petugas',

                                            'Magang/PKL' =>
                                                'badge-magang',

                                            'Tamu/Kunjungan' =>
                                                'badge-tamu',

                                            default =>
                                                'badge-lainnya',

                                        };

                                    @endphp

                                    <span
                                        class="visitor-badge {{ $badgeClass }}"
                                    >
                                        {{ $attendance->jenis_pengunjung }}
                                    </span>

                                </td>


                                {{-- INSTANSI --}}
                                <td>

                                    <span class="table-text">

                                        {{ $attendance->instansi ?: '-' }}

                                    </span>

                                </td>


                                {{-- KELAS --}}
                                <td>

                                    <span class="table-text">

                                        {{ $attendance->kelas ?: '-' }}

                                    </span>

                                </td>


                                {{-- TUJUAN --}}
                                <td>

                                    <span
                                        class="purpose-text"
                                        title="{{ $attendance->tujuan }}"
                                    >

                                        {{ $attendance->tujuan ?: '-' }}

                                    </span>

                                </td>


                                {{-- WAKTU --}}
                                <td>

                                    <div class="time-info">

                                        <strong>
                                            {{ $attendance->waktu_masuk->format('d/m/Y') }}
                                        </strong>

                                        <span>
                                            {{ $attendance->waktu_masuk->format('H:i') }}
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                PAGINATION
            ================================================== --}}
            @if($attendances->hasPages())

                <div class="pagination-wrapper">

                    <div class="pagination-info">

                        Menampilkan

                        <strong>
                            {{ $attendances->firstItem() }}
                        </strong>

                        -

                        <strong>
                            {{ $attendances->lastItem() }}
                        </strong>

                        dari

                        <strong>
                            {{ $attendances->total() }}
                        </strong>

                        data

                    </div>


                    <div class="pagination">

                        {{-- PREVIOUS --}}
                        @if($attendances->onFirstPage())

                            <span class="page-btn disabled">

                                <i class="fa-solid fa-chevron-left"></i>

                            </span>

                        @else

                            <a
                                href="{{ $attendances->previousPageUrl() }}"
                                class="page-btn"
                            >

                                <i class="fa-solid fa-chevron-left"></i>

                            </a>

                        @endif


                        {{-- HALAMAN --}}
                        @foreach(
                            $attendances->getUrlRange(
                                1,
                                $attendances->lastPage()
                            )
                            as $page => $url
                        )

                            @if(
                                $page ==
                                $attendances->currentPage()
                            )

                                <span class="page-btn active">
                                    {{ $page }}
                                </span>

                            @else

                                <a
                                    href="{{ $url }}"
                                    class="page-btn"
                                >
                                    {{ $page }}
                                </a>

                            @endif

                        @endforeach


                        {{-- NEXT --}}
                        @if($attendances->hasMorePages())

                            <a
                                href="{{ $attendances->nextPageUrl() }}"
                                class="page-btn"
                            >

                                <i class="fa-solid fa-chevron-right"></i>

                            </a>

                        @else

                            <span class="page-btn disabled">

                                <i class="fa-solid fa-chevron-right"></i>

                            </span>

                        @endif

                    </div>

                </div>

            @endif


        @else

            {{-- =================================================
                EMPTY
            ================================================== --}}

            <div class="empty-state">

                <div class="empty-icon">

                    <i class="fa-solid fa-clipboard-list"></i>

                </div>

                <h3>
                    Tidak Ada Data
                </h3>

                <p>
                    Tidak ditemukan daftar hadir berdasarkan
                    filter yang dipilih.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
    STYLE
========================================================= --}}

<style>

    /* =====================================================
       PAGE
    ====================================================== */

    .attendance-page {
        width: 100%;
        padding: 10px 0 30px;
    }


    /* =====================================================
       HEADER
    ====================================================== */

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        margin-bottom: 24px;
    }

    .page-header-left {
        display: flex;
        align-items: center;

        gap: 15px;
    }

    .page-icon {
        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: linear-gradient(
            135deg,
            #6366f1,
            #8b5cf6
        );

        color: #ffffff;

        font-size: 21px;

        box-shadow:
            0 8px 18px rgba(99, 102, 241, 0.20);
    }

    .page-header h1 {
        margin: 0 0 4px;

        font-size: 26px;
        font-weight: 800;

        color: #24313f;
    }

    .page-header p {
        margin: 0;

        font-size: 14px;

        color: #7b8794;
    }


    /* =====================================================
       EXPORT
    ====================================================== */

    .export-btn {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 8px;

        padding: 11px 16px;

        border-radius: 11px;

        text-decoration: none;

        font-size: 13px;
        font-weight: 700;

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #16a34a,
            #15803d
        );

        box-shadow:
            0 7px 16px rgba(22, 163, 74, 0.18);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .export-btn:hover {
        color: #ffffff;

        transform: translateY(-2px);

        box-shadow:
            0 10px 20px rgba(22, 163, 74, 0.25);
    }


    /* =====================================================
       STATISTIK
    ====================================================== */

    .attendance-stat {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(220px, 280px));

        gap: 16px;

        margin-bottom: 22px;
    }

    .stat-card {
        position: relative;

        display: flex;

        align-items: center;

        gap: 14px;

        padding: 18px;

        background: #ffffff;

        border: 1px solid #edf0f3;

        border-radius: 16px;

        box-shadow:
            0 5px 18px rgba(0, 0, 0, 0.05);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);

        box-shadow:
            0 8px 22px rgba(0, 0, 0, 0.07);
    }


    /* =====================================================
       SEMUA KUNJUNGAN
       CARD BISA DIKLIK
    ====================================================== */

    .all-visits-card {
        text-decoration: none;

        color: inherit;

        cursor: pointer;

        padding-right: 42px;
    }

    .all-visits-card:hover {
        text-decoration: none;

        color: inherit;

        transform: translateY(-3px);

        box-shadow:
            0 10px 25px rgba(0, 0, 0, 0.09);
    }

    .all-visits-card:active {
        transform: translateY(-1px);
    }

    .all-visits-icon {
        background: #ecfdf5;

        color: #16a34a;
    }


    /* =====================================================
       PANAH KLIK
    ====================================================== */

    .stat-arrow {
        position: absolute;

        right: 18px;
        top: 50%;

        transform: translateY(-50%);

        width: 28px;
        height: 28px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 8px;

        color: #9ca3af;

        font-size: 12px;

        transition:
            transform 0.2s ease,
            color 0.2s ease,
            background 0.2s ease;
    }

    .all-visits-card:hover .stat-arrow {
        color: #16a34a;

        background: #ecfdf5;

        transform:
            translate(3px, -50%);
    }


    /* =====================================================
       STAT INFO
    ====================================================== */

    .stat-icon {
        width: 46px;
        height: 46px;

        flex-shrink: 0;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #eef2ff;

        color: #6366f1;

        font-size: 19px;
    }

    .stat-info {
        display: flex;

        flex-direction: column;

        gap: 3px;
    }

    .stat-info span {
        font-size: 13px;

        color: #7b8794;
    }

    .stat-info strong {
        font-size: 22px;
        font-weight: 800;

        color: #24313f;
    }


    /* =====================================================
       CARD
    ====================================================== */

    .attendance-card {
        width: 100%;

        background: #ffffff;

        border: 1px solid #edf0f3;

        border-radius: 18px;

        overflow: hidden;

        box-shadow:
            0 6px 24px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        padding: 22px 24px;

        border-bottom: 1px solid #edf0f3;
    }

    .card-header h2 {
        margin: 0 0 5px;

        font-size: 18px;
        font-weight: 800;

        color: #24313f;
    }

    .card-header p {
        margin: 0;

        font-size: 13px;

        color: #87919c;
    }


    /* =====================================================
       FILTER
    ====================================================== */

    .filter-form {
        display: grid;

        grid-template-columns:
            1.6fr
            1.15fr
            .8fr
            1fr
            .9fr
            auto;

        align-items: end;

        gap: 10px;

        padding: 18px 24px;

        background: #fafbfc;

        border-bottom: 1px solid #edf0f3;
    }

    .filter-group {
        display: flex;

        flex-direction: column;

        gap: 6px;

        min-width: 0;
    }

    .filter-group label {
        font-size: 11px;

        font-weight: 800;

        color: #66727f;

        text-transform: uppercase;
    }


    /* =====================================================
       SEARCH
    ====================================================== */

    .search-wrapper {
        position: relative;

        width: 100%;
    }

    .search-wrapper > i {
        position: absolute;

        left: 12px;
        top: 50%;

        transform: translateY(-50%);

        color: #94a3b8;

        font-size: 12px;

        pointer-events: none;
    }

    .search-input {
        padding-left: 34px !important;
    }


    /* =====================================================
       INPUT
    ====================================================== */

    .filter-input {
        width: 100%;

        height: 40px;

        padding: 0 11px;

        border: 1px solid #dfe4e8;

        border-radius: 9px;

        background: #ffffff;

        color: #344150;

        font-size: 12px;

        outline: none;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .filter-input::placeholder {
        color: #a0aab5;
    }

    .filter-input:focus {
        border-color: #6366f1;

        box-shadow:
            0 0 0 3px rgba(99, 102, 241, 0.10);
    }


    /* =====================================================
       BUTTON
    ====================================================== */

    .filter-actions {
        display: flex;

        align-items: center;

        gap: 7px;
    }

    .filter-btn,
    .reset-btn {
        height: 40px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 7px;

        padding: 0 13px;

        border-radius: 9px;

        font-size: 12px;
        font-weight: 700;

        text-decoration: none;

        cursor: pointer;

        white-space: nowrap;
    }

    .filter-btn {
        border: 1px solid #6366f1;

        color: #ffffff;

        background: #6366f1;

        transition:
            background 0.2s ease,
            transform 0.2s ease;
    }

    .filter-btn:hover {
        background: #4f46e5;

        transform: translateY(-1px);
    }

    .reset-btn {
        border: 1px solid #e2e8f0;

        color: #64748b;

        background: #ffffff;
    }

    .reset-btn:hover {
        color: #6366f1;

        border-color: #c7d2fe;

        background: #f5f3ff;
    }


    /* =====================================================
       INFO FILTER
    ====================================================== */

    .default-info,
    .active-filter {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        margin: 16px 24px 0;

        padding: 10px 13px;

        border-radius: 9px;

        font-size: 12px;
    }

    .default-info {
        color: #475569;

        background: #f8fafc;

        border: 1px solid #e2e8f0;
    }

    .active-filter {
        color: #4338ca;

        background: #eef2ff;

        border: 1px solid #c7d2fe;
    }

    .active-filter-left {
        display: flex;

        align-items: center;

        gap: 8px;
    }

    .active-filter a {
        color: #4f46e5;

        font-weight: 700;

        text-decoration: none;

        white-space: nowrap;
    }


    /* =====================================================
       TABLE
    ====================================================== */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;

        margin-top: 16px;
    }

    .attendance-table {
        width: 100%;

        border-collapse: collapse;

        min-width: 950px;
    }

    .attendance-table thead {
        background: #fafbfc;
    }

    .attendance-table th {
        padding: 15px 16px;

        text-align: left;

        font-size: 12px;
        font-weight: 800;

        color: #66727f;

        text-transform: uppercase;

        white-space: nowrap;

        border-bottom: 1px solid #edf0f3;
    }

    .attendance-table td {
        padding: 15px 16px;

        font-size: 13px;

        color: #4b5563;

        border-bottom: 1px solid #f0f2f4;

        vertical-align: middle;
    }

    .attendance-table tbody tr {
        transition: background 0.2s ease;
    }

    .attendance-table tbody tr:hover {
        background: #fafbff;
    }

    .attendance-table tbody tr:last-child td {
        border-bottom: none;
    }

    .number-cell {
        width: 55px;

        color: #929ba5 !important;

        font-weight: 700;
    }


    /* =====================================================
       VISITOR NAME
    ====================================================== */

    .visitor-name {
        display: flex;

        align-items: center;

        gap: 10px;

        min-width: 170px;
    }

    .visitor-avatar {
        width: 34px;
        height: 34px;

        flex-shrink: 0;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #f1f5ff;

        color: #6366f1;

        font-size: 13px;
    }

    .visitor-name span {
        font-weight: 700;

        color: #344150;

        white-space: nowrap;
    }


    /* =====================================================
       BADGE
    ====================================================== */

    .visitor-badge {
        display: inline-flex;

        align-items: center;

        padding: 6px 10px;

        border-radius: 8px;

        font-size: 11px;

        font-weight: 700;

        white-space: nowrap;
    }

    .badge-siswa {
        color: #c2410c;

        background: #fff1e8;
    }

    .badge-guru {
        color: #047857;

        background: #ecfdf5;
    }

    .badge-petugas {
        color: #0f766e;

        background: #ecfeff;
    }

    .badge-magang {
        color: #6d28d9;

        background: #f5f3ff;
    }

    .badge-tamu {
        color: #1d4ed8;

        background: #eff6ff;
    }

    .badge-lainnya {
        color: #475569;

        background: #f1f5f9;
    }


    /* =====================================================
       TEXT
    ====================================================== */

    .table-text {
        white-space: nowrap;
    }

    .purpose-text {
        display: block;

        max-width: 190px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;
    }


    /* =====================================================
       TIME
    ====================================================== */

    .time-info {
        display: flex;

        flex-direction: column;

        gap: 3px;

        white-space: nowrap;
    }

    .time-info strong {
        font-size: 13px;

        color: #344150;
    }

    .time-info span {
        font-size: 12px;

        color: #8b96a3;
    }


    /* =====================================================
       PAGINATION
    ====================================================== */

    .pagination-wrapper {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        padding: 18px 24px;

        border-top: 1px solid #edf0f3;
    }

    .pagination-info {
        font-size: 12px;

        color: #7b8794;
    }

    .pagination-info strong {
        color: #344150;
    }

    .pagination {
        display: flex;

        align-items: center;

        gap: 5px;
    }

    .page-btn {
        width: 34px;
        height: 34px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 9px;

        text-decoration: none;

        font-size: 12px;
        font-weight: 700;

        color: #64748b;

        background: #ffffff;

        border: 1px solid #e2e8f0;

        transition:
            background 0.2s ease,
            color 0.2s ease,
            border-color 0.2s ease;
    }

    .page-btn:hover {
        background: #f5f3ff;

        color: #6366f1;

        border-color: #c7d2fe;
    }

    .page-btn.active {
        background: #6366f1;

        color: #ffffff;

        border-color: #6366f1;
    }

    .page-btn.disabled {
        opacity: 0.45;

        cursor: not-allowed;
    }


    /* =====================================================
       EMPTY
    ====================================================== */

    .empty-state {
        padding: 70px 25px;

        text-align: center;
    }

    .empty-icon {
        width: 72px;
        height: 72px;

        margin: 0 auto 18px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #f5f3ff;

        color: #8b5cf6;

        font-size: 28px;
    }

    .empty-state h3 {
        margin: 0 0 8px;

        font-size: 18px;

        color: #344150;
    }

    .empty-state p {
        margin: 0;

        font-size: 13px;

        color: #8b96a3;
    }


    /* =====================================================
       RESPONSIVE TABLET
    ====================================================== */

    @media (max-width: 1200px) {

        .filter-form {
            grid-template-columns:
                1.5fr
                1fr
                .8fr;
        }

        .filter-actions {
            grid-column: span 3;
        }

    }


    /* =====================================================
       RESPONSIVE MOBILE
    ====================================================== */

    @media (max-width: 768px) {

        .attendance-page {
            padding: 5px 0 25px;
        }


        /* HEADER */

        .page-header {
            align-items: flex-start;

            flex-direction: column;
        }

        .page-header h1 {
            font-size: 22px;
        }

        .page-header p {
            font-size: 12px;
        }

        .page-icon {
            width: 46px;
            height: 46px;

            font-size: 18px;
        }

        .export-btn {
            width: 100%;
        }


        /* STATISTIK */

        .attendance-stat {
            grid-template-columns: 1fr;

            gap: 12px;
        }

        .stat-card {
            width: 100%;
        }


        /* CARD */

        .card-header {
            padding: 18px;
        }


        /* FILTER */

        .filter-form {
            grid-template-columns: 1fr;

            padding: 16px 18px;
        }

        .filter-actions {
            grid-column: auto;

            width: 100%;
        }

        .filter-btn,
        .reset-btn {
            flex: 1;
        }


        /* INFO */

        .default-info,
        .active-filter {
            margin-left: 18px;
            margin-right: 18px;

            align-items: flex-start;

            flex-direction: column;
        }


        /* TABLE */

        .table-wrapper {
            margin-top: 16px;
        }


        /* PAGINATION */

        .pagination-wrapper {
            flex-direction: column;

            align-items: flex-start;
        }

        .pagination {
            width: 100%;

            overflow-x: auto;

            padding-bottom: 2px;
        }

    }

</style>

@endsection