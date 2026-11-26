@extends('layouts.petugas')

@section('title', 'Arsip Inventaris')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/inventaris.css') }}"
>
@endsection

@section('content')

<div class="archive-page inventaris-page">


{{-- =====================================================
    HEADER
====================================================== --}}
<div class="welcome-box">

    <div class="welcome-content">

        <span class="page-label">
            <i class="fa-solid fa-boxes-stacked"></i>
            Arsip Inventaris
        </span>

        <h1>
            Inventaris Perpustakaan 📦
        </h1>

        <p>
            Kelola dan pantau seluruh aset serta fasilitas
            yang dimiliki perpustakaan, seperti rak, komputer,
            meja, kursi, printer, dan peralatan lainnya.
        </p>

    </div>

    <div class="welcome-icon">
        <div class="icon-circle">
            <i class="fa-solid fa-boxes-stacked"></i>
        </div>
    </div>

</div>


{{-- =====================================================
    TOMBOL KEMBALI
====================================================== --}}
<div class="inventory-back-wrapper">

    <a
        href="{{ route('petugas.archive') }}"
        class="inventory-back-button"
    >
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali</span>
    </a>

</div>


{{-- =====================================================
    STATISTIK
====================================================== --}}
<div class="card-grid">

    {{-- TOTAL --}}
    <div class="card">

        <div class="card-top">

            <div class="card-icon blue">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>

            <div class="period-badge month">
                <i class="fa-solid fa-database"></i>
                Total
            </div>

        </div>

        <div class="card-content">

            <small>
                Total Inventaris
            </small>

            <h2>
                {{ $totalInventaris ?? 0 }}
            </h2>

        </div>

    </div>


    {{-- BAIK --}}
    <div class="card">

        <div class="card-top">

            <div class="card-icon green">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="period-badge success">
                <i class="fa-solid fa-check"></i>
                Baik
            </div>

        </div>

        <div class="card-content">

            <small>
                Kondisi Baik
            </small>

            <h2>
                {{ $totalBaik ?? 0 }}
            </h2>

        </div>

    </div>


    {{-- RUSAK --}}
    <div class="card">

        <div class="card-top">

            <div class="card-icon orange">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div class="period-badge current">
                <i class="fa-solid fa-wrench"></i>
                Perlu Perhatian
            </div>

        </div>

        <div class="card-content">

            <small>
                Rusak / Perlu Perbaikan
            </small>

            <h2>
                {{ $totalRusak ?? 0 }}
            </h2>

        </div>

    </div>

</div>


{{-- =====================================================
    DATA INVENTARIS
====================================================== --}}
<div class="table-card">

    {{-- =================================================
        HEADER DATA
    ================================================== --}}
    <div class="card-header">

        <div>

            <span class="section-label">
                <i class="fa-solid fa-clipboard-list"></i>
                Data Inventaris
            </span>

            <h3>
                Daftar Aset Perpustakaan
            </h3>

        </div>


        <div class="inventory-header-actions">

            {{-- EXPORT EXCEL --}}
            <a
                href="{{ route(
                    'petugas.archive.inventaris.export',
                    request()->query()
                ) }}"
                class="btn-view-all inventory-export-btn"
                title="Unduh data inventaris"
            >

                <i class="fa-solid fa-file-excel"></i>

                <span>
                    Unduh Excel
                </span>

            </a>


            {{-- TAMBAH INVENTARIS --}}
            <a
                href="{{ route(
                    'petugas.archive.inventaris.create'
                ) }}"
                class="btn-view-all"
            >

                <i class="fa-solid fa-plus"></i>

                <span>
                    Tambah Inventaris
                </span>

            </a>

        </div>

    </div>


    {{-- =================================================
        FILTER
    ================================================== --}}
    <div class="archive-filter">

        <form
            method="GET"
            action="{{ route(
                'petugas.archive.inventaris'
            ) }}"
            class="inventory-filter-form"
        >

            {{-- SEARCH --}}
            <div class="filter-group filter-search">

                <label for="search">
                    Cari
                </label>

                <div class="search-box">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Nama / kode / lokasi..."
                    >

                </div>

            </div>


            {{-- KATEGORI --}}
            <div class="filter-group">

                <label for="kategori">
                    Kategori
                </label>

                <select
                    name="kategori"
                    id="kategori"
                >

                    <option value="">
                        Semua
                    </option>

                    <option
                        value="elektronik"
                        {{ request('kategori') == 'elektronik' ? 'selected' : '' }}
                    >
                        Elektronik
                    </option>

                    <option
                        value="furniture"
                        {{ request('kategori') == 'furniture' ? 'selected' : '' }}
                    >
                        Furniture
                    </option>

                    <option
                        value="peralatan"
                        {{ request('kategori') == 'peralatan' ? 'selected' : '' }}
                    >
                        Peralatan
                    </option>

                    <option
                        value="lainnya"
                        {{ request('kategori') == 'lainnya' ? 'selected' : '' }}
                    >
                        Lainnya
                    </option>

                </select>

            </div>


            {{-- KONDISI --}}
            <div class="filter-group">

                <label for="kondisi">
                    Kondisi
                </label>

                <select
                    name="kondisi"
                    id="kondisi"
                >

                    <option value="">
                        Semua
                    </option>

                    <option
                        value="baik"
                        {{ request('kondisi') == 'baik' ? 'selected' : '' }}
                    >
                        Baik
                    </option>

                    <option
                        value="rusak_ringan"
                        {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}
                    >
                        Rusak Ringan
                    </option>

                    <option
                        value="rusak_berat"
                        {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}
                    >
                        Rusak Berat
                    </option>

                </select>

            </div>


            {{-- TANGGAL --}}
            <div class="filter-group filter-date">

                <label for="tanggal">
                    Tanggal
                </label>

                <input
                    type="date"
                    name="tanggal"
                    id="tanggal"
                    value="{{ request('tanggal') }}"
                >

            </div>


            {{-- BULAN --}}
            <div class="filter-group">

                <label for="bulan">
                    Bulan
                </label>

                <select
                    name="bulan"
                    id="bulan"
                >

                    <option value="">
                        Semua
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


            {{-- TAHUN --}}
            <div class="filter-group">

                <label for="tahun">
                    Tahun
                </label>

                <select
                    name="tahun"
                    id="tahun"
                >

                    <option value="">
                        Semua
                    </option>

                    @for(
                        $year = date('Y');
                        $year >= date('Y') - 10;
                        $year--
                    )

                        <option
                            value="{{ $year }}"
                            {{ request('tahun') == $year ? 'selected' : '' }}
                        >
                            {{ $year }}
                        </option>

                    @endfor

                </select>

            </div>


            {{-- FILTER --}}
            <div class="filter-action">

                <button
                    type="submit"
                    class="filter-button"
                >

                    <i class="fa-solid fa-filter"></i>

                    <span>
                        Filter
                    </span>

                </button>

            </div>


            {{-- RESET --}}
            <div class="filter-action">

                <a
                    href="{{ route(
                        'petugas.archive.inventaris'
                    ) }}"
                    class="inventory-reset-filter"
                >

                    <i class="fa-solid fa-rotate-left"></i>

                    <span>
                        Reset
                    </span>

                </a>

            </div>

        </form>

    </div>


    {{-- =================================================
        TABLE
    ================================================== --}}
    <div class="table-wrapper">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Kode Inventaris</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Lokasi</th>
                    <th>Tanggal Pengadaan</th>
                    <th>Aksi</th>
                </tr>

            </thead>


            <tbody>

            @forelse($inventaris as $key => $item)

                <tr>

                    {{-- NO --}}
                    <td>
                        {{ $inventaris->firstItem() + $key }}
                    </td>


                    {{-- KODE --}}
                    <td>

                        <strong>
                            {{ $item->kode_inventaris ?? '-' }}
                        </strong>

                    </td>


                    {{-- NAMA --}}
                    <td>

                        <div class="inventory-name">

                            <div class="inventory-icon">

                                @if($item->kategori == 'elektronik')

                                    <i class="fa-solid fa-desktop"></i>

                                @elseif($item->kategori == 'furniture')

                                    <i class="fa-solid fa-chair"></i>

                                @elseif($item->kategori == 'peralatan')

                                    <i class="fa-solid fa-toolbox"></i>

                                @else

                                    <i class="fa-solid fa-box"></i>

                                @endif

                            </div>

                            <span>
                                {{ $item->nama_barang ?? '-' }}
                            </span>

                        </div>

                    </td>


                    {{-- KATEGORI --}}
                    <td>

                        @if($item->kategori == 'elektronik')

                            <span class="badge info">
                                Elektronik
                            </span>

                        @elseif($item->kategori == 'furniture')

                            <span class="badge success">
                                Furniture
                            </span>

                        @elseif($item->kategori == 'peralatan')

                            <span class="badge warning">
                                Peralatan
                            </span>

                        @else

                            <span class="badge">
                                Lainnya
                            </span>

                        @endif

                    </td>


                    {{-- JUMLAH --}}
                    <td>
                        {{ $item->jumlah ?? 0 }}
                    </td>


                    {{-- KONDISI --}}
                    <td>

                        @if($item->kondisi == 'baik')

                            <span class="badge success">
                                Baik
                            </span>

                        @elseif($item->kondisi == 'rusak_ringan')

                            <span class="badge warning">
                                Rusak Ringan
                            </span>

                        @elseif($item->kondisi == 'rusak_berat')

                            <span class="badge danger">
                                Rusak Berat
                            </span>

                        @else

                            <span class="badge">
                                -
                            </span>

                        @endif

                    </td>


                    {{-- LOKASI --}}
                    <td>
                        {{ $item->lokasi ?? '-' }}
                    </td>


                    {{-- TANGGAL --}}
                    <td>

                        @if($item->tanggal_pengadaan)

                            {{ \Carbon\Carbon::parse(
                                $item->tanggal_pengadaan
                            )->format('d M Y') }}

                        @else

                            -

                        @endif

                    </td>


                    {{-- AKSI --}}
                    <td>

                        <div class="action-buttons">

                            {{-- EDIT --}}
                            <a
                                href="{{ route(
                                    'petugas.archive.inventaris.edit',
                                    $item->id
                                ) }}"
                                class="action-button edit"
                                title="Edit Inventaris"
                            >

                                <i class="fa-solid fa-pen"></i>

                            </a>


                            {{-- HAPUS --}}
                            <form
                                action="{{ route(
                                    'petugas.archive.inventaris.destroy',
                                    $item->id
                                ) }}"
                                method="POST"
                                onsubmit="return confirm(
                                    'Apakah Anda yakin ingin menghapus inventaris ini?'
                                )"
                            >

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="action-button delete"
                                    title="Hapus Inventaris"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="9">

                        <div class="empty-table">

                            <div class="empty-table-icon">
                                <i class="fa-solid fa-box-open"></i>
                            </div>

                            <strong>
                                Belum Ada Data Inventaris
                            </strong>

                            <span>
                                Tidak ditemukan data inventaris
                                berdasarkan filter yang dipilih.
                            </span>

                            <a
                                href="{{ route(
                                    'petugas.archive.inventaris.create'
                                ) }}"
                                class="btn-view-all"
                            >

                                <i class="fa-solid fa-plus"></i>

                                Tambah Inventaris

                            </a>

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    {{-- =================================================
        PAGINATION
    ================================================== --}}
    @if($inventaris instanceof \Illuminate\Pagination\LengthAwarePaginator)

        <div class="pagination-wrapper">

            {{ $inventaris->withQueryString()->links() }}

        </div>

    @endif

</div>


{{-- =====================================================
    INFORMASI
====================================================== --}}
<div class="archive-information">

    <div class="archive-information-icon">

        <i class="fa-solid fa-circle-info"></i>

    </div>

    <div class="archive-information-content">

        <h4>
            Tentang Arsip Inventaris
        </h4>

        <p>
            Arsip inventaris digunakan untuk mencatat aset dan
            fasilitas perpustakaan seperti komputer, rak buku,
            meja, kursi, printer, lemari, dan peralatan lainnya.
            Data ini membantu petugas memantau jumlah, kondisi,
            lokasi, serta riwayat pengadaan setiap inventaris.
        </p>

    </div>

</div>


</div>

@endsection
