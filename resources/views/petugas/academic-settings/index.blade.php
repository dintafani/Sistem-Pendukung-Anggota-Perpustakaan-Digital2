@extends('layouts.petugas')

@section('title', 'Pengaturan Akademik')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/academic-settings.css') }}"
>

@endsection

@section('content')

<div class="academic-page">

{{-- =========================================================
     HEADER
========================================================== --}}

<div class="academic-header">

    <div class="academic-heading">

        <div class="academic-heading-icon">
            <i class="fa-solid fa-calendar-days"></i>
        </div>

        <div>

            <span class="academic-eyebrow">
                PENGATURAN PERPUSTAKAAN
            </span>

            <h1>
                Pengaturan Akademik
            </h1>

            <p>
                Atur tahun ajaran, semester, batas peminjaman
                buku paket, dan mode peminjaman saat ujian.
            </p>

        </div>

    </div>


    <div class="academic-header-action">

        <a
            href="{{ route('petugas.books.index') }}"
            class="btn-back-academic"
        >

            <i class="fa-solid fa-arrow-left"></i>

            Kembali

        </a>


        <a
            href="{{ route('petugas.academic-settings.create') }}"
            class="btn-add-academic"
        >

            <i class="fa-solid fa-plus"></i>

            Tambah Semester

        </a>

    </div>

</div>



{{-- =========================================================
     ALERT SUCCESS
========================================================== --}}

@if(session('success'))

    <div class="academic-alert academic-alert-success">

        <div class="academic-alert-icon">

            <i class="fa-solid fa-circle-check"></i>

        </div>

        <div class="academic-alert-content">

            <strong>
                Berhasil
            </strong>

            <span>
                {{ session('success') }}
            </span>

        </div>

        <button
            type="button"
            class="academic-alert-close"
            onclick="this.parentElement.remove()"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>

    </div>

@endif



{{-- =========================================================
     ALERT ERROR
========================================================== --}}

@if(session('error'))

    <div class="academic-alert academic-alert-error">

        <div class="academic-alert-icon">

            <i class="fa-solid fa-circle-exclamation"></i>

        </div>

        <div class="academic-alert-content">

            <strong>
                Gagal
            </strong>

            <span>
                {{ session('error') }}
            </span>

        </div>

        <button
            type="button"
            class="academic-alert-close"
            onclick="this.parentElement.remove()"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>

    </div>

@endif



{{-- =========================================================
     VALIDATION ERROR
========================================================== --}}

@if($errors->any())

    <div class="academic-alert academic-alert-error">

        <div class="academic-alert-icon">

            <i class="fa-solid fa-triangle-exclamation"></i>

        </div>

        <div class="academic-alert-content">

            <strong>
                Periksa kembali data
            </strong>

            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

        <button
            type="button"
            class="academic-alert-close"
            onclick="this.parentElement.remove()"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>

    </div>

@endif



{{-- =========================================================
     DATA AKADEMIK
========================================================== --}}

@php

    $academicSettings = $academicSettings ?? collect();

    /*
     * Controller menggunakan paginate().
     * Jadi data utama berupa LengthAwarePaginator.
     */

    if (is_array($academicSettings)) {
        $academicSettings = collect($academicSettings);
    }


    /*
     * Cari semester aktif.
     */

    if (method_exists($academicSettings, 'getCollection')) {

        $activeSetting = $academicSettings
            ->getCollection()
            ->where('aktif', 1)
            ->sortByDesc('id')
            ->first();

    } else {

        $activeSetting = $academicSettings
            ->where('aktif', 1)
            ->sortByDesc('id')
            ->first();

    }

@endphp



{{-- =========================================================
     SEMESTER AKTIF
========================================================== --}}

<div class="active-academic-card">

    <div class="active-academic-left">

        <div class="active-academic-icon">

            <i class="fa-solid fa-graduation-cap"></i>

        </div>


        <div>

            <span class="active-academic-label">
                SEMESTER YANG SEDANG AKTIF
            </span>


            @if($activeSetting)

                <h2>
                    Semester {{ $activeSetting->semester }}
                </h2>


                <p>

                    Tahun Ajaran

                    <strong>
                        {{ $activeSetting->tahun_ajaran }}
                    </strong>


                    @if(
                        $activeSetting->tanggal_mulai &&
                        $activeSetting->tanggal_selesai
                    )

                        <span class="active-date-separator">
                            •
                        </span>

                        {{ \Carbon\Carbon::parse($activeSetting->tanggal_mulai)->format('d M Y') }}

                        sampai

                        {{ \Carbon\Carbon::parse($activeSetting->tanggal_selesai)->format('d M Y') }}

                    @endif

                </p>


                {{-- =================================================
                     STATUS MODE UJIAN
                ================================================== --}}

                @if((int) ($activeSetting->mode_ujian ?? 0) === 1)

                    <div
                        style="
                            display:inline-flex;
                            align-items:center;
                            gap:8px;
                            margin-top:12px;
                            padding:8px 14px;
                            border-radius:999px;
                            background:#fff7ed;
                            color:#c2410c;
                            font-size:13px;
                            font-weight:700;
                            border:1px solid #fed7aa;
                        "
                    >

                        <span
                            style="
                                width:8px;
                                height:8px;
                                border-radius:50%;
                                background:#f97316;
                                box-shadow:0 0 0 4px rgba(249,115,22,.12);
                            "
                        ></span>

                        MODE UJIAN AKTIF

                    </div>

                @else

                    <div
                        style="
                            display:inline-flex;
                            align-items:center;
                            gap:8px;
                            margin-top:12px;
                            padding:8px 14px;
                            border-radius:999px;
                            background:#f8fafc;
                            color:#64748b;
                            font-size:13px;
                            font-weight:700;
                            border:1px solid #e2e8f0;
                        "
                    >

                        <span
                            style="
                                width:8px;
                                height:8px;
                                border-radius:50%;
                                background:#94a3b8;
                            "
                        ></span>

                        MODE NORMAL

                    </div>

                @endif

            @else

                <h2>
                    Belum Ada Semester Aktif
                </h2>

                <p>
                    Silakan buat dan aktifkan salah satu semester.
                </p>

            @endif

        </div>

    </div>



    {{-- =====================================================
         BATAS BUKU PAKET
    ====================================================== --}}

    @if($activeSetting)

        <div class="active-academic-right">

            <div class="active-package-limit">

                <div class="active-package-icon">

                    <i class="fa-solid fa-book-open"></i>

                </div>


                <div class="active-package-content">

                    <span>
                        BATAS BUKU PAKET
                    </span>


                    <div class="active-package-class-list">


                        {{-- KELAS 10 --}}

                        <div>

                            <small>
                                Kelas 10
                            </small>

                            <strong>
                                {{ $activeSetting->batas_paket_kelas_10 ?? 0 }}
                                Buku
                            </strong>

                        </div>


                        {{-- KELAS 11 --}}

                        <div>

                            <small>
                                Kelas 11
                            </small>

                            <strong>
                                {{ $activeSetting->batas_paket_kelas_11 ?? 0 }}
                                Buku
                            </strong>

                        </div>


                        {{-- KELAS 12 --}}

                        <div>

                            <small>
                                Kelas 12
                            </small>

                            <strong>
                                {{ $activeSetting->batas_paket_kelas_12 ?? 0 }}
                                Buku
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            <div class="active-academic-status">

                <span class="active-status-dot"></span>

                AKTIF

            </div>

        </div>

    @endif

</div>



{{-- =========================================================
     PANEL MODE UJIAN
========================================================== --}}

@if($activeSetting)

    <div
        style="
            margin-top:20px;
            padding:22px 24px;
            border-radius:18px;
            background:linear-gradient(135deg,#fff7ed,#fffbeb);
            border:1px solid #fed7aa;
            box-shadow:0 8px 25px rgba(15,23,42,.05);
        "
    >

        <div
            style="
                display:flex;
                align-items:center;
                justify-content:space-between;
                gap:20px;
                flex-wrap:wrap;
            "
        >

            <div
                style="
                    display:flex;
                    align-items:flex-start;
                    gap:15px;
                "
            >

                <div
                    style="
                        width:46px;
                        height:46px;
                        min-width:46px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        border-radius:14px;
                        background:#f97316;
                        color:#fff;
                        font-size:19px;
                    "
                >

                    <i class="fa-solid fa-graduation-cap"></i>

                </div>


                <div>

                    <h3
                        style="
                            margin:0 0 5px;
                            font-size:17px;
                            color:#1e293b;
                        "
                    >

                        Mode Peminjaman Ujian

                    </h3>


                    @if((int) ($activeSetting->mode_ujian ?? 0) === 1)

                        <p
                            style="
                                margin:0;
                                color:#c2410c;
                                font-size:14px;
                                line-height:1.6;
                            "
                        >

                            <strong>Mode ujian sedang aktif.</strong>

                            Siswa dapat meminjam seluruh buku paket
                            pada semester {{ $activeSetting->semester }}
                            sesuai aturan sistem ujian.

                        </p>

                    @else

                        <p
                            style="
                                margin:0;
                                color:#64748b;
                                font-size:14px;
                                line-height:1.6;
                            "
                        >

                            Mode ujian belum aktif.

                            Aktifkan mode ini saat memasuki
                            minggu ujian akhir semester agar
                            siswa dapat meminjam buku paket
                            semester ini sesuai aturan ujian.

                        </p>

                    @endif

                </div>

            </div>



            {{-- =================================================
                 TOMBOL MODE UJIAN
            ================================================== --}}

            <div>

                @if((int) ($activeSetting->mode_ujian ?? 0) === 1)

                    {{-- NONAKTIFKAN MODE UJIAN --}}

                    <form
                        action="{{ route(
                            'petugas.academic-settings.nonaktifkan-ujian',
                            ['academic_setting' => $activeSetting->id]
                        ) }}"
                        method="POST"
                        onsubmit="return confirmNonaktifkanUjian()"
                    >

                        @csrf

                        @method('PATCH')

                        <button
                            type="submit"
                            style="
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                gap:9px;
                                padding:11px 18px;
                                border:0;
                                border-radius:11px;
                                background:#64748b;
                                color:#fff;
                                font-size:14px;
                                font-weight:700;
                                cursor:pointer;
                                box-shadow:0 5px 14px rgba(100,116,139,.20);
                            "
                        >

                            <i class="fa-solid fa-power-off"></i>

                            Nonaktifkan Mode Ujian

                        </button>

                    </form>

                @else

                    {{-- AKTIFKAN MODE UJIAN --}}

                    <form
                        action="{{ route(
                            'petugas.academic-settings.aktifkan-ujian',
                            ['academic_setting' => $activeSetting->id]
                        ) }}"
                        method="POST"
                        onsubmit="return confirmAktifkanUjian()"
                    >

                        @csrf

                        @method('PATCH')

                        <button
                            type="submit"
                            style="
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                gap:9px;
                                padding:11px 18px;
                                border:0;
                                border-radius:11px;
                                background:#f97316;
                                color:#fff;
                                font-size:14px;
                                font-weight:700;
                                cursor:pointer;
                                box-shadow:0 5px 14px rgba(249,115,22,.25);
                            "
                        >

                            <i class="fa-solid fa-graduation-cap"></i>

                            Aktifkan Mode Ujian

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

@endif



{{-- =========================================================
     INFORMASI SISTEM
========================================================== --}}

<div class="academic-info">

    <div class="academic-info-icon">

        <i class="fa-solid fa-circle-info"></i>

    </div>


    <div class="academic-info-content">

        <strong>
            Aturan Peminjaman Buku Paket
        </strong>


        <p>

            Buku <strong>literasi</strong> mengikuti aturan
            peminjaman literasi.

            Buku <strong>paket</strong> menggunakan batas
            peminjaman berdasarkan kelas siswa dan semester
            akademik yang sedang aktif.

            Batas kelas 10, 11, dan 12 diambil langsung dari
            <strong>pengaturan semester aktif</strong>.

            Saat <strong>Mode Ujian</strong> diaktifkan oleh
            petugas, sistem akan menggunakan aturan peminjaman
            khusus ujian untuk buku paket semester tersebut.

            Data batas buku paket
            <strong>tidak disimpan di tabel users</strong>.

        </p>

    </div>

</div>



{{-- =========================================================
     DAFTAR AKADEMIK
========================================================== --}}

<div class="academic-card">


    <div class="academic-card-header">

        <div>

            <span class="academic-card-eyebrow">
                DATA AKADEMIK
            </span>

            <h2>
                Daftar Tahun Ajaran & Semester
            </h2>

            <p>
                Kelola semester, batas peminjaman buku paket,
                dan status mode ujian.
            </p>

        </div>


        <div class="academic-card-header-icon">

            <i class="fa-solid fa-calendar-check"></i>

        </div>

    </div>



    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="academic-table-wrapper">

        <table class="academic-table">

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Tahun Ajaran
                    </th>

                    <th>
                        Semester
                    </th>

                    <th>
                        Kelas 10
                    </th>

                    <th>
                        Kelas 11
                    </th>

                    <th>
                        Kelas 12
                    </th>

                    <th>
                        Tanggal Mulai
                    </th>

                    <th>
                        Tanggal Selesai
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Mode Ujian
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($academicSettings as $index => $setting)

                    <tr>


                        {{-- NOMOR --}}

                        <td>

                            <span class="table-number">
                                {{ $academicSettings instanceof \Illuminate\Pagination\LengthAwarePaginator
                                    ? $academicSettings->firstItem() + $index
                                    : $index + 1
                                }}
                            </span>

                        </td>



                        {{-- TAHUN AJARAN --}}

                        <td>

                            <div class="academic-year">

                                <div class="academic-year-icon">

                                    <i class="fa-solid fa-calendar"></i>

                                </div>

                                <strong>
                                    {{ $setting->tahun_ajaran }}
                                </strong>

                            </div>

                        </td>



                        {{-- SEMESTER --}}

                        <td>

                            <span
                                class="
                                    semester-badge
                                    semester-{{ $setting->semester }}
                                "
                            >

                                <i class="fa-solid fa-book-open"></i>

                                Semester {{ $setting->semester }}

                            </span>

                        </td>



                        {{-- KELAS 10 --}}

                        <td>

                            <div class="package-limit-cell">

                                <div class="package-limit-icon">

                                    <i class="fa-solid fa-book"></i>

                                </div>

                                <div class="package-limit-text">

                                    <strong>
                                        {{ $setting->batas_paket_kelas_10 ?? 0 }}
                                    </strong>

                                    <span>
                                        buku
                                    </span>

                                </div>

                            </div>

                        </td>



                        {{-- KELAS 11 --}}

                        <td>

                            <div class="package-limit-cell">

                                <div class="package-limit-icon">

                                    <i class="fa-solid fa-book"></i>

                                </div>

                                <div class="package-limit-text">

                                    <strong>
                                        {{ $setting->batas_paket_kelas_11 ?? 0 }}
                                    </strong>

                                    <span>
                                        buku
                                    </span>

                                </div>

                            </div>

                        </td>



                        {{-- KELAS 12 --}}

                        <td>

                            <div class="package-limit-cell">

                                <div class="package-limit-icon">

                                    <i class="fa-solid fa-book"></i>

                                </div>

                                <div class="package-limit-text">

                                    <strong>
                                        {{ $setting->batas_paket_kelas_12 ?? 0 }}
                                    </strong>

                                    <span>
                                        buku
                                    </span>

                                </div>

                            </div>

                        </td>



                        {{-- TANGGAL MULAI --}}

                        <td>

                            <div class="academic-date">

                                <i class="fa-regular fa-calendar"></i>

                                <span>

                                    @if($setting->tanggal_mulai)

                                        {{ \Carbon\Carbon::parse($setting->tanggal_mulai)->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </span>

                            </div>

                        </td>



                        {{-- TANGGAL SELESAI --}}

                        <td>

                            <div class="academic-date">

                                <i class="fa-regular fa-calendar-check"></i>

                                <span>

                                    @if($setting->tanggal_selesai)

                                        {{ \Carbon\Carbon::parse($setting->tanggal_selesai)->format('d M Y') }}

                                    @else

                                        -

                                    @endif

                                </span>

                            </div>

                        </td>



                        {{-- STATUS SEMESTER --}}

                        <td>

                            @if((int) $setting->aktif === 1)

                                <span
                                    class="
                                        status-badge
                                        status-active
                                    "
                                >

                                    <span class="status-dot"></span>

                                    Aktif

                                </span>

                            @else

                                <span
                                    class="
                                        status-badge
                                        status-inactive
                                    "
                                >

                                    <span class="status-dot"></span>

                                    Tidak Aktif

                                </span>

                            @endif

                        </td>



                        {{-- =================================================
                             MODE UJIAN
                        ================================================== --}}

                        <td>

                            @if((int) ($setting->mode_ujian ?? 0) === 1)

                                <span
                                    style="
                                        display:inline-flex;
                                        align-items:center;
                                        gap:7px;
                                        padding:7px 11px;
                                        border-radius:999px;
                                        background:#fff7ed;
                                        color:#c2410c;
                                        border:1px solid #fed7aa;
                                        font-size:12px;
                                        font-weight:700;
                                        white-space:nowrap;
                                    "
                                >

                                    <span
                                        style="
                                            width:7px;
                                            height:7px;
                                            border-radius:50%;
                                            background:#f97316;
                                        "
                                    ></span>

                                    Ujian Aktif

                                </span>

                            @else

                                <span
                                    style="
                                        display:inline-flex;
                                        align-items:center;
                                        gap:7px;
                                        padding:7px 11px;
                                        border-radius:999px;
                                        background:#f8fafc;
                                        color:#64748b;
                                        border:1px solid #e2e8f0;
                                        font-size:12px;
                                        font-weight:700;
                                        white-space:nowrap;
                                    "
                                >

                                    <span
                                        style="
                                            width:7px;
                                            height:7px;
                                            border-radius:50%;
                                            background:#94a3b8;
                                        "
                                    ></span>

                                    Normal

                                </span>

                            @endif

                        </td>



                        {{-- =================================================
                             AKSI
                        ================================================== --}}

                        <td>

                            <div class="academic-actions">


                                {{-- EDIT --}}

                                <a
                                    href="{{ route(
                                        'petugas.academic-settings.edit',
                                        ['academic_setting' => $setting->id]
                                    ) }}"
                                    class="
                                        action-button
                                        action-edit
                                    "
                                    title="Edit semester"
                                >

                                    <i class="fa-solid fa-pen"></i>

                                    Edit

                                </a>



                                {{-- =================================================
                                     MODE UJIAN
                                     HANYA UNTUK SEMESTER AKTIF
                                ================================================== --}}

                                @if((int) $setting->aktif === 1)

                                    @if((int) ($setting->mode_ujian ?? 0) === 1)

                                        {{-- NONAKTIFKAN --}}

                                        <form
                                            action="{{ route(
                                                'petugas.academic-settings.nonaktifkan-ujian',
                                                ['academic_setting' => $setting->id]
                                            ) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirmNonaktifkanUjian()"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="action-button"
                                                style="
                                                    background:#64748b;
                                                    color:#fff;
                                                    border:1px solid #64748b;
                                                "
                                                title="Nonaktifkan mode ujian"
                                            >

                                                <i class="fa-solid fa-power-off"></i>

                                                Matikan Ujian

                                            </button>

                                        </form>

                                    @else

                                        {{-- AKTIFKAN --}}

                                        <form
                                            action="{{ route(
                                                'petugas.academic-settings.aktifkan-ujian',
                                                ['academic_setting' => $setting->id]
                                            ) }}"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirmAktifkanUjian()"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="action-button"
                                                style="
                                                    background:#f97316;
                                                    color:#fff;
                                                    border:1px solid #f97316;
                                                "
                                                title="Aktifkan mode ujian"
                                            >

                                                <i class="fa-solid fa-graduation-cap"></i>

                                                Mode Ujian

                                            </button>

                                        </form>

                                    @endif

                                @endif



                                {{-- =================================================
                                     HAPUS
                                ================================================== --}}

                                @if((int) $setting->aktif !== 1)

                                    <form
                                        action="{{ route(
                                            'petugas.academic-settings.destroy',
                                            ['academic_setting' => $setting->id]
                                        ) }}"
                                        method="POST"
                                        class="delete-form"
                                        onsubmit="return confirmHapusSemester()"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="
                                                action-button
                                                action-delete
                                            "
                                            title="Hapus semester"
                                        >

                                            <i class="fa-solid fa-trash"></i>

                                            Hapus

                                        </button>

                                    </form>

                                @else

                                    <button
                                        type="button"
                                        class="
                                            action-button
                                            action-delete
                                            action-disabled
                                        "
                                        title="Semester aktif tidak dapat dihapus"
                                        onclick="alert('Semester yang sedang aktif tidak dapat dihapus. Aktifkan semester lain terlebih dahulu.')"
                                    >

                                        <i class="fa-solid fa-trash"></i>

                                        Hapus

                                    </button>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="11"
                            class="academic-empty-cell"
                        >

                            <div class="academic-empty">

                                <div class="academic-empty-icon">

                                    <i class="fa-solid fa-calendar-xmark"></i>

                                </div>

                                <h3>
                                    Belum Ada Pengaturan Akademik
                                </h3>

                                <p>
                                    Belum ada tahun ajaran atau semester
                                    yang ditambahkan.
                                </p>

                                <a
                                    href="{{ route(
                                        'petugas.academic-settings.create'
                                    ) }}"
                                    class="btn-add-academic-empty"
                                >

                                    <i class="fa-solid fa-plus"></i>

                                    Tambah Semester

                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =========================================================
         PAGINATION
    ========================================================== --}}

    @if(
        $academicSettings instanceof
        \Illuminate\Pagination\LengthAwarePaginator
    )

        <div
            style="
                padding:20px 24px;
                border-top:1px solid #e2e8f0;
            "
        >

            {{ $academicSettings->links() }}

        </div>

    @endif

</div>



{{-- =========================================================
     CARA KERJA
========================================================== --}}

<div class="academic-guide">

    <div class="academic-guide-header">

        <div class="academic-guide-icon">

            <i class="fa-solid fa-lightbulb"></i>

        </div>

        <div>

            <span>
                CARA KERJA
            </span>

            <h2>
                Pengaturan Semester Perpustakaan
            </h2>

        </div>

    </div>


    <div class="academic-guide-grid">


        {{-- 1 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                1
            </div>

            <div>

                <h3>
                    Buat Tahun Ajaran
                </h3>

                <p>
                    Tambahkan tahun ajaran dan periode akademik.
                </p>

            </div>

        </div>



        {{-- 2 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                2
            </div>

            <div>

                <h3>
                    Tentukan Semester
                </h3>

                <p>
                    Pilih semester 1 atau semester 2.
                </p>

            </div>

        </div>



        {{-- 3 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                3
            </div>

            <div>

                <h3>
                    Atur Batas Kelas 10
                </h3>

                <p>
                    Batas buku paket kelas 10 disimpan
                    pada pengaturan akademik.
                </p>

            </div>

        </div>



        {{-- 4 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                4
            </div>

            <div>

                <h3>
                    Atur Batas Kelas 11
                </h3>

                <p>
                    Batas buku paket kelas 11 disimpan
                    pada pengaturan akademik.
                </p>

            </div>

        </div>



        {{-- 5 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                5
            </div>

            <div>

                <h3>
                    Atur Batas Kelas 12
                </h3>

                <p>
                    Batas buku paket kelas 12 disimpan
                    pada pengaturan akademik.
                </p>

            </div>

        </div>



        {{-- 6 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                6
            </div>

            <div>

                <h3>
                    Aktifkan Semester
                </h3>

                <p>
                    Semester aktif menjadi acuan
                    peminjaman buku paket.
                </p>

            </div>

        </div>



        {{-- 7 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                7
            </div>

            <div>

                <h3>
                    Sistem Membaca Kelas Siswa
                </h3>

                <p>
                    Sistem membaca kelas dari data siswa,
                    kemudian mengambil batas dari semester aktif.
                </p>

            </div>

        </div>



        {{-- 8 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                8
            </div>

            <div>

                <h3>
                    Buku Literasi Terpisah
                </h3>

                <p>
                    Buku literasi tidak menggunakan
                    batas buku paket.
                </p>

            </div>

        </div>



        {{-- 9 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                9
            </div>

            <div>

                <h3>
                    Aktifkan Mode Ujian
                </h3>

                <p>
                    Saat minggu ujian akhir semester,
                    petugas dapat mengaktifkan mode ujian
                    pada semester yang sedang aktif.
                </p>

            </div>

        </div>



        {{-- 10 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                10
            </div>

            <div>

                <h3>
                    Peminjaman Saat Ujian
                </h3>

                <p>
                    Saat mode ujian aktif, sistem akan
                    menggunakan aturan khusus peminjaman
                    buku paket untuk kebutuhan ujian.
                </p>

            </div>

        </div>



        {{-- 11 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                11
            </div>

            <div>

                <h3>
                    Nonaktifkan Setelah Ujian
                </h3>

                <p>
                    Setelah minggu ujian selesai,
                    petugas dapat menonaktifkan kembali
                    mode ujian.
                </p>

            </div>

        </div>



        {{-- 12 --}}

        <div class="academic-guide-item">

            <div class="guide-number">
                12
            </div>

            <div>

                <h3>
                    Batas Normal Kembali
                </h3>

                <p>
                    Setelah mode ujian dinonaktifkan,
                    peminjaman kembali menggunakan
                    batas normal berdasarkan kelas.
                </p>

            </div>

        </div>


    </div>

</div>
```

</div>

{{-- =========================================================
JAVASCRIPT
========================================================= --}}

<script>

function confirmHapusSemester()
{
    return confirm(
        'Apakah kamu yakin ingin menghapus pengaturan semester ini?\n\n' +
        'Data yang sudah dihapus tidak dapat dikembalikan.'
    );
}


function confirmAktifkanUjian()
{
    return confirm(
        'Aktifkan Mode Ujian?\n\n' +
        'Setelah mode ujian aktif, sistem akan menggunakan aturan ' +
        'peminjaman khusus ujian untuk buku paket semester aktif.\n\n' +
        'Lanjutkan?'
    );
}


function confirmNonaktifkanUjian()
{
    return confirm(
        'Nonaktifkan Mode Ujian?\n\n' +
        'Setelah dinonaktifkan, sistem akan kembali menggunakan ' +
        'batas peminjaman buku paket normal berdasarkan kelas siswa.\n\n' +
        'Lanjutkan?'
    );
}


document.addEventListener(
    'DOMContentLoaded',
    function ()
    {

        const alerts =
            document.querySelectorAll('.academic-alert');


        alerts.forEach(
            function (alert)
            {

                setTimeout(
                    function ()
                    {

                        if (!alert) {
                            return;
                        }


                        alert.style.opacity = '0';

                        alert.style.transform =
                            'translateY(-8px)';

                        alert.style.transition =
                            'all .3s ease';


                        setTimeout(
                            function ()
                            {

                                if (alert) {
                                    alert.remove();
                                }

                            },
                            300
                        );

                    },
                    5000
                );

            }
        );

    }
);

</script>

@endsection
