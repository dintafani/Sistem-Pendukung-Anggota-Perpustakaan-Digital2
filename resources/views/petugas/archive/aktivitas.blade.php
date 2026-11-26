@extends('layouts.petugas')

@section('title', 'Histori Aktivitas')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/aktivitas.css') }}"
>

@endsection

@section('content')

<div class="activity-history-page">

<div class="activity-history-card">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="activity-history-header">

        <div class="activity-history-heading">

            <h3>
                Histori Aktivitas Sistem
            </h3>

            <p>
                Menampilkan seluruh aktivitas yang dilakukan
                pada sistem perpustakaan.
            </p>

        </div>


        <div class="activity-history-actions">

            {{-- Excel --}}
            <a
                href="{{ route('petugas.archive.aktivitas.export', request()->query()) }}"
                class="activity-btn activity-btn-excel"
                title="Unduh laporan histori aktivitas"
            >

                <i class="fa-solid fa-file-excel"></i>

                <span>
                    Unduh Excel
                </span>

            </a>


            {{-- Kembali --}}
            <a
                href="{{ route('petugas.archive') }}"
                class="activity-btn activity-btn-back"
            >

                <i class="fa-solid fa-arrow-left"></i>

                <span>
                    Kembali
                </span>

            </a>

        </div>

    </div>




    {{-- =====================================================
         FILTER
    ====================================================== --}}

    <form
        method="GET"
        class="activity-filter"
    >

        {{-- SEARCH --}}
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari petugas, aktivitas atau keterangan..."
            class="activity-input"
        >


        {{-- =================================================
             FILTER AKTIVITAS
        ================================================== --}}

        <select
            name="aktivitas"
            class="activity-select"
        >

            <option value="">
                Semua
            </option>


            {{-- =================================================
                 BUKU
            ================================================== --}}

            <option
                value="Tambah Buku"
                {{ request('aktivitas') == 'Tambah Buku' ? 'selected' : '' }}
            >
                Tambah Buku
            </option>

            <option
                value="Edit Buku"
                {{ request('aktivitas') == 'Edit Buku' ? 'selected' : '' }}
            >
                Edit Buku
            </option>

            <option
                value="Hapus Buku"
                {{ request('aktivitas') == 'Hapus Buku' ? 'selected' : '' }}
            >
                Hapus Buku
            </option>

            <option
                value="Import Buku"
                {{ request('aktivitas') == 'Import Buku' ? 'selected' : '' }}
            >
                Import Buku
            </option>



            {{-- =================================================
                 POSTER
            ================================================== --}}

            <option
                value="Tambah Poster"
                {{ request('aktivitas') == 'Tambah Poster' ? 'selected' : '' }}
            >
                Tambah Pengumuman
            </option>

            <option
                value="Edit Poster"
                {{ request('aktivitas') == 'Edit Poster' ? 'selected' : '' }}
            >
                Edit Pengumuman
            </option>

            <option
                value="Hapus Poster"
                {{ request('aktivitas') == 'Hapus Poster' ? 'selected' : '' }}
            >
                Hapus Pengumuman
            </option>



            {{-- =================================================
                 INVENTARIS
                 TAMBAHAN BARU
            ================================================== --}}

            <option
                value="Tambah Inventaris"
                {{ request('aktivitas') == 'Tambah Inventaris' ? 'selected' : '' }}
            >
                Tambah Inventaris
            </option>

            <option
                value="Edit Inventaris"
                {{ request('aktivitas') == 'Edit Inventaris' ? 'selected' : '' }}
            >
                Edit Inventaris
            </option>

            <option
                value="Hapus Inventaris"
                {{ request('aktivitas') == 'Hapus Inventaris' ? 'selected' : '' }}
            >
                Hapus Inventaris
            </option>



            {{-- =================================================
                 ANGGOTA
            ================================================== --}}

            <option
                value="Verifikasi Anggota"
                {{ request('aktivitas') == 'Verifikasi Anggota' ? 'selected' : '' }}
            >
                Verifikasi Anggota
            </option>

<option
    value="Hapus Anggota"
    {{ request('aktivitas') == 'Hapus Anggota' ? 'selected' : '' }}
>
    Hapus Anggota
</option>

            {{-- =================================================
                 TRANSAKSI
            ================================================== --}}

            <option
                value="Peminjaman"
                {{ request('aktivitas') == 'Peminjaman' ? 'selected' : '' }}
            >
                Peminjaman
            </option>

            <option
                value="Pengembalian"
                {{ request('aktivitas') == 'Pengembalian' ? 'selected' : '' }}
            >
                Pengembalian
            </option>

            <option
                value="Tukar Reward"
                {{ request('aktivitas') == 'Tukar Reward' ? 'selected' : '' }}
            >
                Tukar Reward
            </option>

        </select>
{{-- =================================================
     FILTER TANGGAL
================================================== --}}

<select
    name="tanggal"
    class="activity-date"
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


{{-- =================================================
     FILTER BULAN
================================================== --}}

<select
    name="bulan"
    class="activity-date"
>
    <option value="">
        Bulan
    </option>

    @for($i = 1; $i <= 12; $i++)
        <option
            value="{{ $i }}"
            {{ request('bulan') == $i ? 'selected' : '' }}
        >
            {{ $i }}
        </option>
    @endfor
</select>


{{-- =================================================
     FILTER TAHUN
================================================== --}}

<input
    type="number"
    name="tahun"
    value="{{ request('tahun') }}"
    min="2000"
    max="{{ now()->year }}"
    placeholder="Tahun"
    class="activity-date"
>



        {{-- =================================================
             CARI
        ================================================== --}}

        <button
            type="submit"
            class="activity-search-btn"
        >

            <i class="fa-solid fa-magnifying-glass"></i>

            Cari

        </button>



        {{-- =================================================
             RESET
        ================================================== --}}

        <a
            href="{{ route('petugas.archive.aktivitas') }}"
            class="activity-reset-btn"
        >

            <i class="fa-solid fa-rotate-right"></i>

            Reset

        </a>

    </form>




    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="activity-table-wrapper">

        <table class="activity-table">

            <thead>

                <tr>

                    <th class="activity-number">
                        No
                    </th>

                    <th>
                        Petugas
                    </th>

                    <th>
                        Aktivitas
                    </th>

                    <th>
                        Keterangan
                    </th>

                    <th>
                        Waktu
                    </th>

                </tr>

            </thead>



            <tbody>

            @forelse($activities as $key => $activity)

                <tr>


                    {{-- =================================================
                         NOMOR
                    ================================================== --}}

                    <td class="activity-number">

                        {{ $key + 1 }}

                    </td>



                    {{-- =================================================
                         PETUGAS
                    ================================================== --}}

                    <td>

                        <span class="activity-user">

                            {{ optional($activity->user)->name ?? 'System' }}

                        </span>

                    </td>



                    {{-- =================================================
                         AKTIVITAS
                    ================================================== --}}

                    <td>

                        @switch($activity->aktivitas)


                            {{-- =================================================
                                 BUKU
                            ================================================== --}}

                            @case('Tambah Buku')

                                <span class="activity-badge success">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Edit Buku')

                                <span class="activity-badge info">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break
                                
                                @case('Hapus Anggota')
    <span class="activity-badge danger">
        {{ $activity->aktivitas }}
    </span>
    @break


                            @case('Hapus Buku')

                                <span class="activity-badge danger">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Import Buku')

                                <span class="activity-badge info">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break



                            {{-- =================================================
                                 POSTER
                            ================================================== --}}

                            @case('Tambah Pengumuman')

                                <span class="activity-badge success">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Edit Pengumuman')

                                <span class="activity-badge info">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Hapus Pengumuman')

                                <span class="activity-badge danger">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break



                            {{-- =================================================
                                 INVENTARIS
                                 TAMBAHAN BARU
                            ================================================== --}}

                            @case('Tambah Inventaris')

                                <span class="activity-badge success">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Edit Inventaris')

                                <span class="activity-badge info">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Hapus Inventaris')

                                <span class="activity-badge danger">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break



                            {{-- =================================================
                                 ANGGOTA
                            ================================================== --}}

                            @case('Verifikasi Anggota')

                                <span class="activity-badge info">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break



                            {{-- =================================================
                                 TRANSAKSI
                            ================================================== --}}

                            @case('Peminjaman')

                                <span class="activity-badge waiting">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Pengembalian')

                                <span class="activity-badge warning">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break


                            @case('Tukar Reward')

                                <span class="activity-badge success">
                                    {{ $activity->aktivitas }}
                                </span>

                                @break



                            {{-- =================================================
                                 DEFAULT
                            ================================================== --}}

                            @default

                                <span class="activity-badge default">
                                    {{ $activity->aktivitas }}
                                </span>

                        @endswitch

                    </td>



                    {{-- =================================================
                         KETERANGAN
                    ================================================== --}}

                    <td class="activity-description">

                        {{ $activity->keterangan ?? '-' }}

                    </td>



                    {{-- =================================================
                         WAKTU
                    ================================================== --}}

                    <td class="activity-date-cell">

                        {{ optional($activity->created_at)->format('d-m-Y H:i') ?? '-' }}

                    </td>

                </tr>


            @empty

                <tr>

                    <td
                        colspan="5"
                        class="activity-empty"
                    >

                        <div class="activity-empty-icon">

                            <i class="fa-solid fa-clock-rotate-left"></i>

                        </div>


                        <strong>
                            Tidak ada aktivitas yang ditemukan
                        </strong>


                        <span>
                            Belum terdapat histori aktivitas sesuai filter.
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
