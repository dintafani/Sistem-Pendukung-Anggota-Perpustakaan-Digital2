@extends('layouts.petugas')

@section('title', 'Arsip Perpustakaan')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/arsip.css') }}"
>

@endsection

@section('content')

<div class="archive-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="welcome-box">

        <div class="welcome-content">

            <span class="page-label">
                <i class="fa-solid fa-folder-open"></i>
                Arsip
            </span>

            <h1>
                Arsip Perpustakaan 📚
            </h1>

            <p>
                Ringkasan aktivitas
                perpustakaan pada <strong>bulan berjalan</strong>.
                Untuk melihat arsip transaksi bulan atau tahun
                sebelumnya, silakan pilih menu
                <strong>Arsip Transaksi</strong>.
            </p>

        </div>

        <div class="welcome-icon">

            <div class="icon-circle">
                <i class="fa-solid fa-box-archive"></i>
            </div>

        </div>

    </div>


    {{-- =====================================================
        STATISTIK
    ====================================================== --}}

    <div class="card-grid">

        {{-- =========================================
            TOTAL TRANSAKSI
        ========================================== --}}
        <div class="card">

            <div class="card-top">

                <div class="card-icon blue">
                    <i class="fa-solid fa-book"></i>
                </div>

                <div class="period-badge month">
                    <i class="fa-solid fa-calendar-days"></i>
                    Bulan Ini
                </div>

            </div>

            <div class="card-content">

                <small>Total Transaksi</small>

                <h2>
                    {{ $totalTransaksi }}
                </h2>

            </div>

        </div>


        {{-- =========================================
            SEDANG DIPINJAM
        ========================================== --}}
        <div class="card">

            <div class="card-top">

                <div class="card-icon orange">
                    <i class="fa-solid fa-book-open-reader"></i>
                </div>

                <div class="period-badge current">
                    <i class="fa-solid fa-circle"></i>
                    Saat Ini
                </div>

            </div>

            <div class="card-content">

                <small>Sedang Dipinjam</small>

                <h2>
                    {{ $totalDipinjam }}
                </h2>

            </div>

        </div>


        {{-- =========================================
            SUDAH DIKEMBALIKAN
        ========================================== --}}
        <div class="card">

            <div class="card-top">

                <div class="card-icon green">
                    <i class="fa-solid fa-check"></i>
                </div>

                <div class="period-badge success">
                    <i class="fa-solid fa-check-circle"></i>
                    Bulan Ini
                </div>

            </div>

            <div class="card-content">

                <small>Sudah Dikembalikan</small>

                <h2>
                    {{ $totalKembali }}
                </h2>

            </div>

        </div>

    </div>



    {{-- =====================================================
        CONTENT
    ====================================================== --}}

    <div class="dashboard-grid">


        {{-- =================================================
            TRANSAKSI TERBARU
        ================================================== --}}

        <div class="table-card">

            <div class="card-header">

                <div>

                    <span class="section-label">

                        <i class="fa-solid fa-clock-rotate-left"></i>

                        Riwayat Bulan Ini

                    </span>

                    <h3>
                        Transaksi Terbaru
                    </h3>

                </div>

                <a
                    href="{{ route('petugas.archive.transaksi') }}"
                    class="btn-view-all"
                >
                    Lihat Semua
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Nama Siswa</th>

                            <th>Judul Buku</th>

                            <th>Status</th>

                            <th>Tanggal</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($transaksiTerbaru as $key => $item)

                        <tr>

                            <td>{{ $key + 1 }}</td>

                            <td>
                                {{ $item->user->name ?? '-' }}
                            </td>

                            <td>
                                {{ $item->book->judul ?? '-' }}
                            </td>

                            <td>

                                @if($item->status == 'menunggu')

                                    <span class="badge waiting">
                                        Menunggu
                                    </span>

                                @elseif($item->status == 'dipinjam')

                                    <span class="badge success">
                                        Dipinjam
                                    </span>

                                @elseif($item->status == 'menunggu_pengembalian')

                                    <span class="badge warning">
                                        Menunggu Pengembalian
                                    </span>

                                @elseif($item->status == 'dikembalikan')

                                    <span class="badge info">
                                        Dikembalikan
                                    </span>

                                @else

                                    <span class="badge danger">
                                        Ditolak
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($item->tanggal_pinjam)

                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="empty-table">

                                    <div class="empty-table-icon">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>

                                    <strong>
                                        Belum Ada Transaksi Bulan Ini
                                    </strong>

                                    <span>
                                        Data transaksi bulan berjalan
                                        akan ditampilkan di sini.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =================================================
            MENU ARSIP
        ================================================== --}}

        <div class="activity-card">

            <div class="menu-header">

                <span class="section-label">

                    <i class="fa-solid fa-layer-group"></i>

                    Menu Utama

                </span>

                <h3>
                    Kelola Arsip Perpustakaan
                </h3>

                <p class="section-desc">
                    Pilih menu di bawah ini untuk mengelola seluruh data
                    arsip perpustakaan.
                </p>

            </div>


            <div class="archive-menu">


                {{-- =========================================
                    ARSIP TRANSAKSI
                ========================================== --}}
                <a
                    href="{{ route('petugas.archive.transaksi') }}"
                    class="archive-item"
                >

                    <div class="archive-item-icon blue">

                        <i class="fa-solid fa-book"></i>

                    </div>


                    <div class="archive-item-content">

                        <h4>
                            Arsip Transaksi
                        </h4>

                    </div>


                    <div class="archive-item-arrow">

                        <i class="fa-solid fa-arrow-right"></i>

                    </div>

                </a>



                {{-- =========================================
                    DAFTAR ANGGOTA
                ========================================== --}}
                <a
                    href="{{ route('petugas.archive.anggota') }}"
                    class="archive-item"
                >

                    <div class="archive-item-icon green">

                        <i class="fa-solid fa-users"></i>

                    </div>


                    <div class="archive-item-content">

                        <h4>
                            Daftar Anggota
                        </h4>

                    </div>


                    <div class="archive-item-arrow">

                        <i class="fa-solid fa-arrow-right"></i>

                    </div>

                </a>



                {{-- =========================================
                    HISTORI AKTIVITAS
                ========================================== --}}
                <a
                    href="{{ route('petugas.archive.aktivitas') }}"
                    class="archive-item"
                >

                    <div class="archive-item-icon orange">

                        <i class="fa-solid fa-clock-rotate-left"></i>

                    </div>


                    <div class="archive-item-content">

                        <h4>
                            Histori Aktivitas
                        </h4>

                    </div>


                    <div class="archive-item-arrow">

                        <i class="fa-solid fa-arrow-right"></i>

                    </div>

                </a>



                {{-- =========================================
                    ARSIP INVENTARIS
                    BARU
                ========================================== --}}
                <a
                    href="{{ route('petugas.archive.inventaris') }}"
                    class="archive-item"
                >

                    <div class="archive-item-icon purple">

                        <i class="fa-solid fa-boxes-stacked"></i>

                    </div>


                    <div class="archive-item-content">

                        <h4>
                            Arsip Inventaris
                        </h4>

                        <p>
                            Kelola data aset dan fasilitas perpustakaan
                        </p>

                    </div>


                    <div class="archive-item-arrow">

                        <i class="fa-solid fa-arrow-right"></i>

                    </div>

                </a>


            </div>



            {{-- =========================================
                INFO
            ========================================== --}}

            <div class="archive-information">

                <div class="archive-information-icon">

                    <i class="fa-solid fa-circle-info"></i>

                </div>


                <div class="archive-information-content">

                    <h4>
                        Informasi Dashboard
                    </h4>

                    <p>
                        Dashboard ini hanya menampilkan ringkasan
                        transaksi <strong>bulan berjalan</strong>.
                        Untuk melihat data pada bulan atau tahun lain,
                        silakan buka menu
                        <strong>Arsip Transaksi</strong> kemudian pilih
                        filter bulan dan tahun sesuai kebutuhan.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection