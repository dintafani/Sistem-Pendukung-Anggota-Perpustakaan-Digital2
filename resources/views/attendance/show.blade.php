@extends('layouts.petugas')

@section('title', 'Semua Kunjungan')

@section('content')

<style>
    /* =========================================================
       HALAMAN SEMUA KUNJUNGAN
    ========================================================= */

    .attendance-page {
        width: 100%;
        padding: 24px;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .attendance-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .attendance-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .attendance-back {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff;
        color: #374151;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: .2s ease;
    }

    .attendance-back:hover {
        background: #f9fafb;
        color: #111827;
        transform: translateX(-2px);
    }

    .attendance-title h1 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #111827;
    }

    .attendance-title p {
        margin: 5px 0 0;
        font-size: 14px;
        color: #6b7280;
    }


    /* =========================================================
       BUTTON
    ========================================================= */

    .attendance-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-attendance {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: .2s ease;
    }

    .btn-excel {
        background: #16a34a;
        color: #fff;
    }

    .btn-excel:hover {
        background: #15803d;
        color: #fff;
    }

    .btn-reset {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .btn-reset:hover {
        background: #e5e7eb;
        color: #111827;
    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    .attendance-summary {
        display: grid;
        grid-template-columns: repeat(2, minmax(220px, 280px));
        gap: 16px;
        margin-bottom: 22px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .summary-icon.green {
        background: #ecfdf5;
        color: #16a34a;
    }

    .summary-text {
        min-width: 0;
    }

    .summary-label {
        margin: 0 0 4px;
        color: #6b7280;
        font-size: 13px;
    }

    .summary-value {
        margin: 0;
        color: #111827;
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
    }


    /* =========================================================
       FILTER
    ========================================================= */

    .attendance-filter {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    .filter-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .filter-title i {
        color: #f97316;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto auto;
        gap: 12px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        font-size: 14px;
        outline: none;
        transition: .2s ease;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, .10);
    }

    .filter-group input::placeholder {
        color: #9ca3af;
    }

    .btn-filter {
        height: 42px;
        padding: 0 17px;
        border: none;
        border-radius: 10px;
        background: #f97316;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: .2s ease;
    }

    .btn-filter:hover {
        background: #ea580c;
    }


    /* =========================================================
       TABLE CARD
    ========================================================= */

    .attendance-table-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    .table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .table-header h2 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #111827;
    }

    .table-header span {
        font-size: 13px;
        color: #6b7280;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .attendance-table thead {
        background: #f9fafb;
    }

    .attendance-table th {
        padding: 13px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
        border-bottom: 1px solid #e5e7eb;
    }

    .attendance-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: #374151;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .attendance-table tbody tr {
        transition: .15s ease;
    }

    .attendance-table tbody tr:hover {
        background: #fafafa;
    }

    .attendance-table tbody tr:last-child td {
        border-bottom: none;
    }

    .number-cell {
        width: 55px;
        color: #9ca3af !important;
        font-weight: 600;
    }

    .name-cell {
        font-weight: 700;
        color: #111827 !important;
    }

    .empty-cell {
        padding: 55px 20px !important;
        text-align: center !important;
        color: #9ca3af !important;
    }

    .empty-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 21px;
    }

    .empty-text {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }


    /* =========================================================
       BADGE
    ========================================================= */

    .attendance-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-siswa {
        background: #eff6ff;
        color: #2563eb;
    }

    .badge-guru {
        background: #f5f3ff;
        color: #7c3aed;
    }

    .badge-petugas {
        background: #fff7ed;
        color: #ea580c;
    }

    .badge-magang {
        background: #ecfdf5;
        color: #16a34a;
    }

    .badge-tamu {
        background: #fef2f2;
        color: #dc2626;
    }

    .badge-lainnya {
        background: #f3f4f6;
        color: #4b5563;
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-wrapper {
        padding: 18px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .pagination-info {
        font-size: 13px;
        color: #6b7280;
    }

    .pagination-wrapper nav {
        display: flex;
        align-items: center;
    }

    .pagination-wrapper nav > div:first-child {
        display: none;
    }

    .pagination-wrapper nav > div:last-child {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .pagination-wrapper a,
    .pagination-wrapper span {
        min-width: 34px;
        height: 34px;
        padding: 0 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        font-size: 13px;
    }

    .pagination-wrapper a {
        color: #374151;
        background: #fff;
    }

    .pagination-wrapper a:hover {
        background: #f9fafb;
    }

    .pagination-wrapper span[aria-current="page"] {
        background: #f97316;
        color: #fff;
        border-color: #f97316;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .filter-form {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .btn-filter,
        .btn-reset-filter {
            width: 100%;
        }
    }


    @media (max-width: 900px) {

        .filter-form {
            grid-template-columns: 1fr 1fr;
        }
    }


    @media (max-width: 700px) {

        .attendance-page {
            padding: 16px;
        }

        .attendance-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .attendance-header-left {
            width: 100%;
        }

        .attendance-title h1 {
            font-size: 22px;
        }

        .attendance-actions {
            width: 100%;
        }

        .attendance-actions .btn-attendance {
            flex: 1;
        }

        .attendance-summary {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .table-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .pagination-wrapper {
            align-items: flex-start;
            flex-direction: column;
        }

        .pagination-wrapper nav {
            width: 100%;
            overflow-x: auto;
        }
    }


    @media (max-width: 480px) {

        .attendance-page {
            padding: 12px;
        }

        .attendance-title h1 {
            font-size: 20px;
        }

        .attendance-title p {
            font-size: 12px;
        }

        .attendance-back {
            width: 38px;
            height: 38px;
        }

        .summary-card {
            padding: 15px;
        }

        .summary-icon {
            width: 43px;
            height: 43px;
            min-width: 43px;
        }

        .summary-value {
            font-size: 21px;
        }

        .attendance-filter {
            padding: 14px;
        }

        .table-header {
            padding: 15px;
        }

        .pagination-wrapper {
            padding: 15px;
        }
    }
</style>


<div class="attendance-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="attendance-header">

        <div class="attendance-header-left">

            <a
                href="{{ route('petugas.attendance.index') }}"
                class="attendance-back"
                title="Kembali"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </a>


            <div class="attendance-title">

                <h1>
                    Semua Kunjungan
                </h1>

                <p>
                    Rekap seluruh data kunjungan perpustakaan
                </p>

            </div>

        </div>


        <div class="attendance-actions">

            {{-- =================================================
                 UNDUH EXCEL
            ================================================== --}}

            <a
                href="{{ route('petugas.attendance.exportAll', request()->query()) }}"
                class="btn-attendance btn-excel"
            >

                <i class="fa-solid fa-file-excel"></i>

                <span>
                    Unduh Excel
                </span>

            </a>

        </div>

    </div>



    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="attendance-summary">

        <div class="summary-card">

            <div class="summary-icon green">

                <i class="fa-solid fa-users"></i>

            </div>


            <div class="summary-text">

                <p class="summary-label">
                    Total Kunjungan
                </p>

                <p class="summary-value">
                    {{ $attendances->total() }}
                </p>

            </div>

        </div>

    </div>



    {{-- =====================================================
         FILTER
    ====================================================== --}}

    <div class="attendance-filter">

        <div class="filter-title">

            <i class="fa-solid fa-filter"></i>

            <span>
                Filter Kunjungan
            </span>

        </div>


        <form
            action="{{ route('petugas.attendance.all') }}"
            method="GET"
            class="filter-form"
        >


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
                >

                    <option value="">
                        Semua Bulan
                    </option>


                    <option
                        value="1"
                        {{ request('bulan') == '1' ? 'selected' : '' }}
                    >
                        Januari
                    </option>


                    <option
                        value="2"
                        {{ request('bulan') == '2' ? 'selected' : '' }}
                    >
                        Februari
                    </option>


                    <option
                        value="3"
                        {{ request('bulan') == '3' ? 'selected' : '' }}
                    >
                        Maret
                    </option>


                    <option
                        value="4"
                        {{ request('bulan') == '4' ? 'selected' : '' }}
                    >
                        April
                    </option>


                    <option
                        value="5"
                        {{ request('bulan') == '5' ? 'selected' : '' }}
                    >
                        Mei
                    </option>


                    <option
                        value="6"
                        {{ request('bulan') == '6' ? 'selected' : '' }}
                    >
                        Juni
                    </option>


                    <option
                        value="7"
                        {{ request('bulan') == '7' ? 'selected' : '' }}
                    >
                        Juli
                    </option>


                    <option
                        value="8"
                        {{ request('bulan') == '8' ? 'selected' : '' }}
                    >
                        Agustus
                    </option>


                    <option
                        value="9"
                        {{ request('bulan') == '9' ? 'selected' : '' }}
                    >
                        September
                    </option>


                    <option
                        value="10"
                        {{ request('bulan') == '10' ? 'selected' : '' }}
                    >
                        Oktober
                    </option>


                    <option
                        value="11"
                        {{ request('bulan') == '11' ? 'selected' : '' }}
                    >
                        November
                    </option>


                    <option
                        value="12"
                        {{ request('bulan') == '12' ? 'selected' : '' }}
                    >
                        Desember
                    </option>

                </select>

            </div>



            {{-- =================================================
                 TAHUN - BISA DIKETIK BEBAS
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
                    placeholder="Contoh: 2026"
                    min="1900"
                    max="2100"
                >

            </div>



            {{-- =================================================
                 JENIS KUNJUNGAN
            ================================================== --}}

            <div class="filter-group">

                <label for="jenis_pengunjung">
                    Jenis Kunjungan
                </label>


                <select
                    name="jenis_pengunjung"
                    id="jenis_pengunjung"
                >

                    <option value="">
                        Semua Jenis
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
                 TERAPKAN
            ================================================== --}}

            <button
                type="submit"
                class="btn-filter"
            >

                <i class="fa-solid fa-filter"></i>

                Terapkan

            </button>



            {{-- =================================================
                 RESET
            ================================================== --}}

            <a
                href="{{ route('petugas.attendance.all') }}"
                class="btn-attendance btn-reset btn-reset-filter"
            >

                <i class="fa-solid fa-rotate-left"></i>

                Reset

            </a>

        </form>

    </div>



    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="attendance-table-card">


        <div class="table-header">

            <div>

                <h2>
                    Data Kunjungan
                </h2>

            </div>


            <span>

                Menampilkan data sesuai filter

            </span>

        </div>



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
                            Instansi
                        </th>

                        <th>
                            Jenis Pengunjung
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

                    @forelse($attendances as $index => $attendance)

                        <tr>


                            <td class="number-cell">

                                {{ $attendances->firstItem() + $index }}

                            </td>



                            <td class="name-cell">

                                {{ $attendance->nama }}

                            </td>



                            <td>

                                {{ $attendance->instansi ?: '-' }}

                            </td>



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
                                    class="attendance-badge {{ $badgeClass }}"
                                >

                                    {{ $attendance->jenis_pengunjung }}

                                </span>

                            </td>



                            <td>

                                {{ $attendance->kelas ?: '-' }}

                            </td>



                            <td>

                                {{ $attendance->tujuan ?: '-' }}

                            </td>



                            <td>

                                {{
                                    $attendance->waktu_masuk
                                        ? $attendance->waktu_masuk->format('d/m/Y H:i')
                                        : '-'
                                }}

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-cell"
                            >

                                <div class="empty-icon">

                                    <i class="fa-solid fa-clipboard-user"></i>

                                </div>


                                <p class="empty-text">

                                    Tidak ada data kunjungan
                                    sesuai filter.

                                </p>

                            </td>

                        </tr>

                    @endforelse

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

                    kunjungan

                </div>



                <div>

                    {{ $attendances->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection