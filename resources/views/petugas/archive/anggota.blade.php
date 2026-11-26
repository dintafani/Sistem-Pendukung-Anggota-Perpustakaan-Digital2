@extends('layouts.petugas')

@section('title', 'Arsip Anggota')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/petugas/anggota.css') }}">
@endsection

@section('content')

<div class="member-page">

    <div class="member-card">

        {{-- HEADER --}}
        <div class="member-header">

            <div class="member-heading">

                <div>
                    <h3>
                        Daftar Anggota Perpustakaan
                    </h3>

                    <p>
                        Menampilkan data anggota perpustakaan.
                    </p>
                </div>

            </div>

            <a
                href="{{ route('petugas.archive') }}"
                class="member-back-btn"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>

        </div>


        {{-- ALERT SUCCESS --}}
        @if(session('success'))

            <div class="member-alert success">

                <i class="fa-solid fa-circle-check"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        {{-- ALERT ERROR --}}
        @if(session('error'))

            <div class="member-alert error">

                <i class="fa-solid fa-circle-exclamation"></i>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        @endif


        {{-- =====================================================
             FILTER
        ====================================================== --}}

        <form
            action="{{ route('petugas.archive.anggota') }}"
            method="GET"
            class="member-filter"
        >

            {{-- SEARCH --}}
            <div class="member-filter-group search">

              
                <div class="member-search-box">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="member-filter-input"
                        value="{{ request('search') }}"
                        placeholder="Nama, NIS, kelas, atau email..."
                    >

                </div>

            </div>


           {{-- TANGGAL --}}
<div class="member-filter-group date">

    <select
        id="tanggal"
        name="tanggal"
        class="member-filter-input"
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


{{-- BULAN --}}
<div class="member-filter-group date">
 
    <select
        id="bulan"
        name="bulan"
        class="member-filter-input"
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

</div>


{{-- TAHUN --}}
<div class="member-filter-group date">


    <input
        type="number"
        id="tahun"
        name="tahun"
        class="member-filter-input"
        value="{{ request('tahun') }}"
        min="2000"
        max="{{ now()->year }}"
        placeholder="Tahun"
    >

</div>


            {{-- STATUS --}}
            <div class="member-filter-group status">

            
                <select
                    id="status"
                    name="status"
                    class="member-filter-select"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="aktif"
                        {{ request('status') == 'aktif' ? 'selected' : '' }}
                    >
                        Terverifikasi
                    </option>

                    <option
                        value="pending"
                        {{ request('status') == 'pending' ? 'selected' : '' }}
                    >
                        Belum Terverifikasi
                    </option>

                    <option
                        value="ditolak"
                        {{ request('status') == 'ditolak' ? 'selected' : '' }}
                    >
                        Ditolak
                    </option>

                </select>

            </div>


            {{-- BUTTON --}}
            <div class="member-filter-actions">

                <button
                    type="submit"
                    class="member-filter-btn"
                >
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>

                <a
                    href="{{ route('petugas.archive.anggota') }}"
                    class="member-reset-btn"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </a>

            </div>

        </form>


        {{-- =====================================================
             FILTER AKTIF
        ====================================================== --}}

        @if(
            request('search') ||
            request('tanggal') ||
            request('status')
        )

            <div class="member-filter-summary">

                <strong>
                    <i class="fa-solid fa-filter"></i>
                    Filter aktif:
                </strong>


                @if(request('search'))

                    <span class="member-filter-badge">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        {{ request('search') }}

                    </span>

                @endif


               @if(request('tanggal'))
    <span class="member-filter-badge">
        <i class="fa-regular fa-calendar"></i>
        Tanggal: {{ request('tanggal') }}
    </span>
@endif

@if(request('bulan'))
    <span class="member-filter-badge">
        <i class="fa-regular fa-calendar"></i>
        Bulan: {{ request('bulan') }}
    </span>
@endif

@if(request('tahun'))
    <span class="member-filter-badge">
        <i class="fa-regular fa-calendar"></i>
        Tahun: {{ request('tahun') }}
    </span>
@endif


                @if(request('status') == 'aktif')

                    <span class="member-filter-badge verified">

                        <i class="fa-solid fa-circle-check"></i>

                        Terverifikasi

                    </span>

                @elseif(request('status') == 'pending')

                    <span class="member-filter-badge unverified">

                        <i class="fa-solid fa-clock"></i>

                        Belum Terverifikasi

                    </span>

                @elseif(request('status') == 'ditolak')

                    <span class="member-filter-badge rejected">

                        <i class="fa-solid fa-circle-xmark"></i>

                        Ditolak

                    </span>

                @endif

            </div>

        @endif


        {{-- =====================================================
             SUMMARY
        ====================================================== --}}

        <div class="member-summary">

            <div class="summary-left">

                <div class="summary-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

                <div>

                    <strong>
                        {{ $anggota->count() }}
                    </strong>

                    <span>
                        anggota ditemukan
                    </span>

                </div>

            </div>


            {{-- EXPORT --}}
            <a
                href="{{ route(
                    'petugas.archive.anggota.export',
                    [
    'search' => request('search'),
    'tanggal' => request('tanggal'),
    'bulan' => request('bulan'),
    'tahun' => request('tahun'),
    'status' => request('status')
]
                ) }}"
                class="member-export-btn"
            >

                <i class="fa-solid fa-file-excel"></i>

                Unduh Excel

            </a>

        </div>


        <div class="member-divider"></div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}

        <div class="member-table-wrapper">

            <table class="member-table">

                <thead>

                    <tr>

                        <th class="member-number">
                            No
                        </th>

                        <th>
                            Nama
                        </th>

                        <th>
                            NIS
                        </th>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Tanggal Daftar
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($anggota as $key => $user)

                    <tr>

                        {{-- NO --}}
                        <td class="member-number">
                            {{ $key + 1 }}
                        </td>


                        {{-- NAMA --}}
                        <td>

                            <span class="member-name">
                                {{ $user->name }}
                            </span>

                        </td>


                        {{-- NIS --}}
                        <td>

                            <span class="member-nis">
                                {{ $user->nis ?? '-' }}
                            </span>

                        </td>


                        {{-- KELAS --}}
                        <td>

                            <span class="member-class">
                                {{ $user->kelas ?? '-' }}
                            </span>

                        </td>


                        {{-- EMAIL --}}
                        <td>

                            <span class="member-email">
                                {{ $user->email ?? '-' }}
                            </span>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($user->status == 'aktif')

                                <span class="member-status active">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Terverifikasi

                                </span>

                            @elseif($user->status == 'pending')

                                <span class="member-status pending">

                                    <i class="fa-solid fa-clock"></i>

                                    Belum Terverifikasi

                                </span>

                            @elseif($user->status == 'ditolak')

                                <span class="member-status rejected">

                                    <i class="fa-solid fa-circle-xmark"></i>

                                    Ditolak

                                </span>

                            @else

                                <span class="member-status rejected">

                                    <i class="fa-solid fa-circle-question"></i>

                                    {{ $user->status ?? '-' }}

                                </span>

                            @endif

                        </td>


                        {{-- TANGGAL --}}
                        <td>

                            <span class="member-date">

                                <i class="fa-regular fa-calendar"></i>

                                {{ $user->created_at
                                    ? $user->created_at->format('d-m-Y')
                                    : '-'
                                }}

                            </span>

                        </td>


                        {{-- AKSI --}}
                        <td>

                            <form
                                action="{{ route(
                                    'petugas.archive.anggota.destroy',
                                    $user->id
                                ) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus anggota ini?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="member-delete-btn"
                                >

                                    <i class="fa-solid fa-trash"></i>

                                    Hapus

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="member-empty"
                        >

                            <div class="member-empty-icon">

                                <i class="fa-solid fa-users-slash"></i>

                            </div>

                            <strong>
                                Tidak ada data anggota
                            </strong>

                            <span>
                                Tidak ditemukan anggota berdasarkan
                                filter yang dipilih.
                            </span>

                            <a
                                href="{{ route('petugas.archive.anggota') }}"
                                class="member-reset-btn"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                Reset Filter
                            </a>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        {{-- FOOTER --}}

        <div class="member-footer">

            <span>
                Menampilkan
                <strong>{{ $anggota->count() }}</strong>
                anggota
            </span>

            <span>
                <i class="fa-solid fa-circle-info"></i>
                Data arsip anggota perpustakaan
            </span>

        </div>

    </div>

</div>

@endsection