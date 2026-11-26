@extends('layouts.petugas')

@section('title', 'Edit Pengaturan Akademik')

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

        <div class="academic-header-left">

            <a
                href="{{ route('petugas.academic-settings.index') }}"
                class="back-button"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Kembali

            </a>


            <div class="academic-title">

                <div class="academic-title-icon">

                    <i class="fa-solid fa-calendar-pen"></i>

                </div>


                <div>

                    <span class="academic-eyebrow">
                        PENGATURAN AKADEMIK
                    </span>

                    <h1>
                        Edit Pengaturan Semester
                    </h1>

                    <p>
                        Perbarui tahun ajaran, semester, batas
                        peminjaman buku paket berdasarkan tingkat
                        kelas, dan periode akademik.
                    </p>

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         FORM CARD
    ========================================================== --}}

    <div class="academic-form-card">


        {{-- =====================================================
             FORM HEADER
        ====================================================== --}}

        <div class="academic-form-header">

            <div class="form-header-icon">

                <i class="fa-solid fa-pen-to-square"></i>

            </div>


            <div>

                <h2>
                    Edit Data Akademik
                </h2>

                <p>
                    Perbarui informasi semester dan aturan
                    peminjaman buku paket berdasarkan tingkat kelas.
                </p>

            </div>

        </div>



        {{-- =====================================================
             ERROR VALIDASI
        ====================================================== --}}

        @if ($errors->any())

            <div class="form-alert form-alert-danger">

                <div class="alert-icon">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>


                <div>

                    <strong>
                        Terdapat kesalahan pada formulir.
                    </strong>


                    <ul>

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif



        {{-- =====================================================
             SUCCESS
        ====================================================== --}}

        @if(session('success'))

            <div class="form-alert form-alert-success">

                <div class="alert-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>


                <div>

                    <strong>
                        Berhasil
                    </strong>

                    <p>
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        @endif



        {{-- =====================================================
             ERROR SESSION
        ====================================================== --}}

        @if(session('error'))

            <div class="form-alert form-alert-danger">

                <div class="alert-icon">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>


                <div>

                    <strong>
                        Gagal
                    </strong>

                    <p>
                        {{ session('error') }}
                    </p>

                </div>

            </div>

        @endif



        {{-- =====================================================
             FORM
        ====================================================== --}}

        <form
            action="{{ route(
                'petugas.academic-settings.update',
                $academicSetting->id
            ) }}"
            method="POST"
            class="academic-form"
        >

            @csrf

            @method('PUT')



            {{-- =================================================
                 TAHUN AJARAN
            ================================================== --}}

            <div class="form-group">

                <label for="tahun_ajaran">

                    Tahun Ajaran

                    <span class="required">
                        *
                    </span>

                </label>


                <div class="input-wrapper">

                    <i class="fa-solid fa-graduation-cap"></i>


                    <input
                        type="text"
                        id="tahun_ajaran"
                        name="tahun_ajaran"
                        value="{{ old(
                            'tahun_ajaran',
                            $academicSetting->tahun_ajaran
                        ) }}"
                        placeholder="Contoh: 2026/2027"
                        maxlength="20"
                        required
                    >

                </div>


                <small>
                    Contoh format tahun ajaran: 2026/2027.
                </small>

            </div>



            {{-- =================================================
                 SEMESTER
            ================================================== --}}

            <div class="form-group">

                <label for="semester">

                    Semester

                    <span class="required">
                        *
                    </span>

                </label>


                <div class="input-wrapper">

                    <i class="fa-solid fa-layer-group"></i>


                    <select
                        id="semester"
                        name="semester"
                        required
                    >

                        <option value="">
                            -- Pilih Semester --
                        </option>


                        <option
                            value="1"
                            {{ old(
                                'semester',
                                $academicSetting->semester
                            ) == 1 ? 'selected' : '' }}
                        >
                            Semester 1
                        </option>


                        <option
                            value="2"
                            {{ old(
                                'semester',
                                $academicSetting->semester
                            ) == 2 ? 'selected' : '' }}
                        >
                            Semester 2
                        </option>

                    </select>

                </div>


                <small>
                    Semester digunakan sebagai acuan
                    peminjaman buku paket.
                </small>

            </div>



            {{-- =================================================
                 BATAS BUKU PAKET
            ================================================== --}}

            <div class="form-group">

                <label>

                    Batas Peminjaman Buku Paket

                    <span class="required">
                        *
                    </span>

                </label>


                <small>
                    Batas buku paket disimpan pada tabel
                    <strong>academic_settings</strong> berdasarkan
                    tingkat kelas siswa.
                </small>

            </div>



            {{-- =================================================
                 BATAS KELAS
            ================================================== --}}

            <div class="form-row">


                {{-- =================================================
                     KELAS 10
                ================================================== --}}

                <div class="form-group">

                    <label for="batas_paket_kelas_10">

                        Kelas 10

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-book-open"></i>


                        <input
                            type="number"
                            id="batas_paket_kelas_10"
                            name="batas_paket_kelas_10"
                            value="{{ old(
                                'batas_paket_kelas_10',
                                $academicSetting->batas_paket_kelas_10 ?? 0
                            ) }}"
                            min="0"
                            max="100"
                            required
                        >

                    </div>


                    <small>
                        Maksimal buku paket untuk siswa kelas 10.
                    </small>

                </div>



                {{-- =================================================
                     KELAS 11
                ================================================== --}}

                <div class="form-group">

                    <label for="batas_paket_kelas_11">

                        Kelas 11

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-book-open"></i>


                        <input
                            type="number"
                            id="batas_paket_kelas_11"
                            name="batas_paket_kelas_11"
                            value="{{ old(
                                'batas_paket_kelas_11',
                                $academicSetting->batas_paket_kelas_11 ?? 0
                            ) }}"
                            min="0"
                            max="100"
                            required
                        >

                    </div>


                    <small>
                        Maksimal buku paket untuk siswa kelas 11.
                    </small>

                </div>



                {{-- =================================================
                     KELAS 12
                ================================================== --}}

                <div class="form-group">

                    <label for="batas_paket_kelas_12">

                        Kelas 12

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-book-open"></i>


                        <input
                            type="number"
                            id="batas_paket_kelas_12"
                            name="batas_paket_kelas_12"
                            value="{{ old(
                                'batas_paket_kelas_12',
                                $academicSetting->batas_paket_kelas_12 ?? 0
                            ) }}"
                            min="0"
                            max="100"
                            required
                        >

                    </div>


                    <small>
                        Maksimal buku paket untuk siswa kelas 12.
                    </small>

                </div>

            </div>



            {{-- =================================================
                 INFORMASI BATAS PAKET
            ================================================== --}}

            <div class="form-alert form-alert-info">

                <div class="alert-icon">

                    <i class="fa-solid fa-circle-info"></i>

                </div>


                <div>

                    <strong>
                        Aturan Batas Buku Paket
                    </strong>


                    <p>

                        Sistem mengambil batas buku paket
                        langsung dari pengaturan akademik
                        berdasarkan tingkat kelas siswa.

                        <br><br>

                        Kelas 10:

                        <strong>
                            {{ $academicSetting->batas_paket_kelas_10 ?? 0 }}
                            buku
                        </strong>

                        <br>

                        Kelas 11:

                        <strong>
                            {{ $academicSetting->batas_paket_kelas_11 ?? 0 }}
                            buku
                        </strong>

                        <br>

                        Kelas 12:

                        <strong>
                            {{ $academicSetting->batas_paket_kelas_12 ?? 0 }}
                            buku
                        </strong>

                    </p>

                </div>

            </div>



            {{-- =================================================
                 TANGGAL
            ================================================== --}}

            <div class="form-row">


                {{-- =================================================
                     TANGGAL MULAI
                ================================================== --}}

                <div class="form-group">

                    <label for="tanggal_mulai">

                        Tanggal Mulai

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-calendar-check"></i>


                        <input
                            type="date"
                            id="tanggal_mulai"
                            name="tanggal_mulai"
                            value="{{ old(
                                'tanggal_mulai',
                                $academicSetting->tanggal_mulai
                                    ? \Carbon\Carbon::parse(
                                        $academicSetting->tanggal_mulai
                                    )->format('Y-m-d')
                                    : ''
                            ) }}"
                            required
                        >

                    </div>

                </div>



                {{-- =================================================
                     TANGGAL SELESAI
                ================================================== --}}

                <div class="form-group">

                    <label for="tanggal_selesai">

                        Tanggal Selesai

                        <span class="required">
                            *
                        </span>

                    </label>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-calendar-xmark"></i>


                        <input
                            type="date"
                            id="tanggal_selesai"
                            name="tanggal_selesai"
                            value="{{ old(
                                'tanggal_selesai',
                                $academicSetting->tanggal_selesai
                                    ? \Carbon\Carbon::parse(
                                        $academicSetting->tanggal_selesai
                                    )->format('Y-m-d')
                                    : ''
                            ) }}"
                            required
                        >

                    </div>

                </div>

            </div>



            {{-- =================================================
                 STATUS AKTIF
            ================================================== --}}

            <div class="form-group">

                <label>
                    Status Pengaturan
                </label>


                <div class="active-setting-box">

                    <label class="switch-wrapper">


                        {{-- PENTING:
                             Jika checkbox tidak dicentang,
                             tetap kirim aktif = 0
                        --}}

                        <input
                            type="hidden"
                            name="aktif"
                            value="0"
                        >


                        <input
                            type="checkbox"
                            name="aktif"
                            value="1"
                            {{ old(
                                'aktif',
                                $academicSetting->aktif
                            ) ? 'checked' : '' }}
                        >


                        <span class="switch"></span>


                        <span class="switch-text">

                            <strong>
                                Jadikan semester aktif
                            </strong>


                            <small>
                                Jika aktif, pengaturan semester ini
                                akan digunakan sebagai acuan
                                peminjaman buku paket.
                            </small>

                        </span>

                    </label>

                </div>

            </div>



            {{-- =================================================
                 STATUS SEKARANG
            ================================================== --}}

            <div class="current-setting">


                <div class="current-setting-icon">

                    @if((int) $academicSetting->aktif === 1)

                        <i class="fa-solid fa-circle-check"></i>

                    @else

                        <i class="fa-solid fa-circle-xmark"></i>

                    @endif

                </div>


                <div>

                    <span>
                        Status Saat Ini
                    </span>


                    <strong>

                        @if((int) $academicSetting->aktif === 1)

                            Semester Aktif

                        @else

                            Tidak Aktif

                        @endif

                    </strong>

                </div>

            </div>



            {{-- =================================================
                 RINGKASAN DATA
            ================================================== --}}

            <div class="form-alert form-alert-info">

                <div class="alert-icon">

                    <i class="fa-solid fa-book-open-reader"></i>

                </div>


                <div>

                    <strong>
                        Pengaturan Peminjaman Buku Paket
                    </strong>


                    <p>

                        Tahun Ajaran:

                        <strong>
                            {{ $academicSetting->tahun_ajaran }}
                        </strong>

                        <br>

                        Semester:

                        <strong>
                            {{ $academicSetting->semester }}
                        </strong>

                        <br><br>

                        Batas Kelas 10:

                        <strong>
                            {{ $academicSetting->batas_paket_kelas_10 ?? 0 }}
                            buku
                        </strong>

                        <br>

                        Batas Kelas 11:

                        <strong>
                            {{ $academicSetting->batas_paket_kelas_11 ?? 0 }}
                            buku
                        </strong>

                        <br>

                        Batas Kelas 12:

                        <strong>
                            {{ $academicSetting->batas_paket_kelas_12 ?? 0 }}
                            buku
                        </strong>

                    </p>

                </div>

            </div>



            {{-- =================================================
                 INFO SISTEM
            ================================================== --}}

            <div class="form-alert form-alert-info">

                <div class="alert-icon">

                    <i class="fa-solid fa-circle-info"></i>

                </div>


                <div>

                    <strong>
                        Cara Kerja Sistem
                    </strong>


                    <p>

                        Jika semester ini aktif, sistem peminjaman
                        akan menggunakan pengaturan ini.

                        <br><br>

                        Untuk buku dengan jenis
                        <strong>paket</strong>,
                        batas peminjaman akan disesuaikan dengan
                        tingkat kelas siswa.

                        <br><br>

                        Kelas 10 menggunakan batas
                        <strong>
                            {{ $academicSetting->batas_paket_kelas_10 ?? 0 }}
                        </strong>
                        buku.

                        <br>

                        Kelas 11 menggunakan batas
                        <strong>
                            {{ $academicSetting->batas_paket_kelas_11 ?? 0 }}
                        </strong>
                        buku.

                        <br>

                        Kelas 12 menggunakan batas
                        <strong>
                            {{ $academicSetting->batas_paket_kelas_12 ?? 0 }}
                        </strong>
                        buku.

                        <br><br>

                        Buku dengan jenis
                        <strong>literasi</strong>
                        tidak menggunakan batas buku paket.

                    </p>

                </div>

            </div>



            {{-- =================================================
                 ACTION
            ================================================== --}}

            <div class="form-actions">


                <a
                    href="{{ route(
                        'petugas.academic-settings.index'
                    ) }}"
                    class="button button-secondary"
                >

                    <i class="fa-solid fa-xmark"></i>

                    Batal

                </a>


                <button
                    type="submit"
                    class="button button-primary"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Simpan Perubahan

                </button>

            </div>


        </form>

    </div>

</div>

@endsection