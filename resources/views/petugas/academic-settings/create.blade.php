@extends('layouts.petugas')

@section('title', 'Tambah Pengaturan Akademik')

@section('styles')

<link rel="stylesheet" href="{{ asset('css/petugas/academic-settings.css') }}">
@endsection

@section('content')

<div class="academic-page">

```
{{-- =========================================================
     HEADER
========================================================== --}}
<div class="academic-header">

    <div class="academic-heading">

        <div class="academic-heading-icon">
            <i class="fa-solid fa-calendar-plus"></i>
        </div>

        <div>

            <span class="academic-eyebrow">
                PENGATURAN AKADEMIK
            </span>

            <h1>
                Tambah Pengaturan Semester
            </h1>

            <p>
                Tambahkan tahun ajaran, semester, periode akademik,
                dan batas peminjaman buku paket berdasarkan tingkat
                kelas siswa.
            </p>

        </div>

    </div>

    <div class="academic-header-action">

        <a
            href="{{ route('petugas.academic-settings.index') }}"
            class="btn-back-academic"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>

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
            <i class="fa-solid fa-calendar-days"></i>
        </div>

        <div>

            <h2>
                Data Tahun Ajaran & Semester
            </h2>

            <p>
                Isi informasi periode akademik dan batas
                peminjaman buku paket untuk masing-masing
                tingkat kelas.
            </p>

        </div>

    </div>


    {{-- =====================================================
         ERROR VALIDASI
    ====================================================== --}}
    @if($errors->any())

        <div class="form-alert form-alert-danger">

            <div class="alert-icon">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>

            <div>

                <strong>
                    Terdapat kesalahan pada formulir.
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
         PESAN SUCCESS
    ====================================================== --}}
    @if(session('success'))

        <div class="form-alert form-alert-success">

            <div class="alert-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- =====================================================
         PESAN ERROR
    ====================================================== --}}
    @if(session('error'))

        <div class="form-alert form-alert-danger">

            <div class="alert-icon">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>

            <div>
                {{ session('error') }}
            </div>

        </div>

    @endif


    {{-- =====================================================
         FORM
    ====================================================== --}}
    <form
        action="{{ route('petugas.academic-settings.store') }}"
        method="POST"
        class="academic-form"
    >

        @csrf


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
                    value="{{ old('tahun_ajaran') }}"
                    placeholder="Contoh: 2026/2027"
                    maxlength="20"
                    required
                >

            </div>

            <small>
                Contoh format tahun ajaran:
                <strong>2026/2027</strong>.
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
                        {{ old('semester') == '1' ? 'selected' : '' }}
                    >
                        Semester 1
                    </option>

                    <option
                        value="2"
                        {{ old('semester') == '2' ? 'selected' : '' }}
                    >
                        Semester 2
                    </option>

                </select>

            </div>

            <small>
                Pilih semester yang ingin ditambahkan.
                Semester baru tidak akan menjadi aktif
                kecuali pilihan aktif dicentang.
            </small>

        </div>


        {{-- =================================================
             BATAS BUKU PAKET
        ================================================== --}}
        <div class="form-group">

            <label>

                Batas Buku Paket Berdasarkan Kelas

                <span class="required">
                    *
                </span>

            </label>

            <small>
                Tentukan jumlah maksimal buku paket yang
                dapat dipinjam siswa berdasarkan tingkat
                kelas.
            </small>

        </div>


        {{-- =================================================
             BARIS BATAS KELAS
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
                        value="{{ old('batas_paket_kelas_10', 0) }}"
                        min="0"
                        max="100"
                        step="1"
                        placeholder="Contoh: 5"
                        required
                    >

                </div>

                <small>
                    Maksimal buku paket yang dapat dipinjam
                    oleh siswa kelas 10.
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
                        value="{{ old('batas_paket_kelas_11', 0) }}"
                        min="0"
                        max="100"
                        step="1"
                        placeholder="Contoh: 5"
                        required
                    >

                </div>

                <small>
                    Maksimal buku paket yang dapat dipinjam
                    oleh siswa kelas 11.
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
                        value="{{ old('batas_paket_kelas_12', 0) }}"
                        min="0"
                        max="100"
                        step="1"
                        placeholder="Contoh: 5"
                        required
                    >

                </div>

                <small>
                    Maksimal buku paket yang dapat dipinjam
                    oleh siswa kelas 12.
                </small>

            </div>

        </div>


        {{-- =================================================
             INFORMASI BATAS BUKU
        ================================================== --}}
        <div class="form-alert form-alert-info">

            <div class="alert-icon">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>

            <div>

                <strong>
                    Aturan Batas Buku Paket
                </strong>

                <p>
                    Sistem akan menggunakan batas sesuai
                    tingkat kelas siswa.
                </p>

                <p>

                    Contoh:

                    <br>

                    Kelas 10:
                    <strong>
                        5 buku
                    </strong>

                    <br>

                    Kelas 11:
                    <strong>
                        6 buku
                    </strong>

                    <br>

                    Kelas 12:
                    <strong>
                        4 buku
                    </strong>

                </p>

                <p>

                    Batas tersebut hanya digunakan untuk
                    buku dengan jenis:

                    <strong>
                        paket
                    </strong>.

                    Buku jenis:

                    <strong>
                        literasi
                    </strong>

                    menggunakan aturan peminjaman literasi
                    tersendiri.

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
                        value="{{ old('tanggal_mulai') }}"
                        required
                    >

                </div>

                <small>
                    Tanggal dimulainya periode semester.
                </small>

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
                        value="{{ old('tanggal_selesai') }}"
                        required
                    >

                </div>

                <small>
                    Tanggal selesai harus sama atau setelah
                    tanggal mulai.
                </small>

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

                    <input
                        type="checkbox"
                        name="aktif"
                        value="1"
                        {{ old('aktif') ? 'checked' : '' }}
                    >

                    <span class="switch"></span>

                    <span class="switch-text">

                        <strong>
                            Jadikan semester aktif
                        </strong>

                        <small>

                            Centang jika pengaturan ini ingin
                            langsung digunakan sebagai semester
                            aktif oleh sistem.

                            Jika hanya ingin membuat data semester
                            baru tanpa mengganti semester yang
                            sedang aktif, biarkan tidak dicentang.

                        </small>

                    </span>

                </label>

            </div>

        </div>


        {{-- =================================================
             INFORMASI STATUS
        ================================================== --}}
        <div class="form-alert form-alert-info">

            <div class="alert-icon">
                <i class="fa-solid fa-circle-info"></i>
            </div>

            <div>

                <strong>
                    Perhatian
                </strong>

                <p>

                    Kamu dapat menambahkan semester baru
                    tanpa menonaktifkan semester yang sedang
                    aktif.

                </p>

                <p>

                    Contohnya, jika saat ini:

                    <br>

                    Semester 1 =
                    <strong>
                        Aktif
                    </strong>

                </p>

                <p>

                    Kemudian kamu menambahkan Semester 2
                    dengan checkbox aktif tidak dicentang,
                    maka:

                    <br>

                    Semester 1 =
                    <strong>
                        Tetap Aktif
                    </strong>

                    <br>

                    Semester 2 =
                    <strong>
                        Tidak Aktif
                    </strong>

                </p>

                <p>

                    Jika checkbox
                    <strong>
                        Jadikan semester aktif
                    </strong>
                    dicentang, sistem akan menonaktifkan
                    pengaturan akademik lainnya.

                </p>

            </div>

        </div>


        {{-- =================================================
             CONTOH PENGATURAN
        ================================================== --}}
        <div class="form-alert form-alert-info">

            <div class="alert-icon">
                <i class="fa-solid fa-lightbulb"></i>
            </div>

            <div>

                <strong>
                    Contoh Pengaturan Semester 2
                </strong>

                <p>

                    Tahun Ajaran:
                    <strong>
                        2026/2027
                    </strong>

                    <br>

                    Semester:
                    <strong>
                        Semester 2
                    </strong>

                    <br>

                    Kelas 10:
                    <strong>
                        5 Buku
                    </strong>

                    <br>

                    Kelas 11:
                    <strong>
                        6 Buku
                    </strong>

                    <br>

                    Kelas 12:
                    <strong>
                        4 Buku
                    </strong>

                    <br>

                    Status:
                    <strong>
                        Tidak Aktif
                    </strong>

                </p>

                <p>

                    Dengan pengaturan tersebut, Semester 2
                    tersimpan di database tetapi sistem masih
                    menggunakan Semester 1 yang sedang aktif.

                </p>

            </div>

        </div>


        {{-- =================================================
             HUBUNGAN DENGAN SISTEM
        ================================================== --}}
        <div class="form-alert form-alert-info">

            <div class="alert-icon">
                <i class="fa-solid fa-gears"></i>
            </div>

            <div>

                <strong>
                    Hubungan dengan Sistem Peminjaman
                </strong>

                <p>

                    Saat siswa meminjam buku jenis
                    <strong>
                        paket
                    </strong>,
                    sistem akan menggunakan pengaturan
                    akademik yang berstatus aktif.

                </p>

                <ol>

                    <li>
                        Mengambil semester akademik yang aktif.
                    </li>

                    <li>
                        Memeriksa tingkat kelas siswa.
                    </li>

                    <li>
                        Mengambil batas buku paket sesuai kelas.
                    </li>

                    <li>
                        Menghitung jumlah buku paket aktif
                        yang sedang dipinjam siswa.
                    </li>

                    <li>
                        Menolak peminjaman jika batas telah tercapai.
                    </li>

                </ol>

                <p>

                    Buku jenis
                    <strong>
                        literasi
                    </strong>
                    tidak menggunakan batas buku paket ini.

                </p>

            </div>

        </div>


        {{-- =================================================
             FORM ACTION
        ================================================== --}}
        <div class="form-actions">

            <a
                href="{{ route('petugas.academic-settings.index') }}"
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

                Simpan Pengaturan

            </button>

        </div>

    </form>

</div>
```

</div>

{{-- =========================================================
VALIDASI TANGGAL DI BROWSER
========================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tanggalMulai =
        document.getElementById('tanggal_mulai');

    const tanggalSelesai =
        document.getElementById('tanggal_selesai');


    function updateTanggalSelesai() {

        if (!tanggalMulai || !tanggalSelesai) {
            return;
        }

        if (tanggalMulai.value) {

            tanggalSelesai.min =
                tanggalMulai.value;

            if (
                tanggalSelesai.value &&
                tanggalSelesai.value < tanggalMulai.value
            ) {

                tanggalSelesai.value =
                    tanggalMulai.value;

            }

        }

    }


    tanggalMulai.addEventListener(
        'change',
        updateTanggalSelesai
    );


    updateTanggalSelesai();

});
</script>

@endsection
