@extends('layouts.petugas')

@section('title', 'Edit Inventaris')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/coba.css') }}"
>

<style>
    /* =====================================================
       EDIT INVENTARIS
    ====================================================== */

    .inventory-edit-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 10px 0 40px;
        color: #263238;
        font-family: "Poppins", sans-serif;
    }

    .inventory-edit-page *,
    .inventory-edit-page *::before,
    .inventory-edit-page *::after {
        box-sizing: border-box;
    }

    /* =====================================================
       HEADER
    ====================================================== */

    .inventory-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;

        padding: 30px 34px;
        margin-bottom: 25px;

        border-radius: 20px;

        background: linear-gradient(
            135deg,
            #fff7ed 0%,
            #fffaf5 55%,
            #f0fdf4 100%
        );

        border: 1px solid #f3e8dc;

        box-shadow: 0 8px 25px rgba(91, 72, 54, 0.06);
    }

    .inventory-edit-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        margin-bottom: 8px;

        color: #e66a16;

        font-size: 13px;
        font-weight: 700;
    }

    .inventory-edit-header h1 {
        margin: 0 0 7px;

        color: #263238;

        font-size: 30px;
        line-height: 1.25;
        font-weight: 800;
    }

    .inventory-edit-header p {
        margin: 0;

        color: #78828a;

        font-size: 14px;
        line-height: 1.7;
    }

    .inventory-edit-icon {
        width: 72px;
        height: 72px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 20px;

        background: #ffffff;
        color: #e66a16;

        border: 1px solid #f1dfcf;

        box-shadow: 0 8px 20px rgba(230, 106, 22, 0.10);

        font-size: 28px;
    }

    /* =====================================================
       CARD
    ====================================================== */

    .inventory-edit-card {
        width: 100%;

        overflow: hidden;

        background: #ffffff;

        border: 1px solid #ece8e3;
        border-radius: 20px;

        box-shadow: 0 10px 30px rgba(41, 37, 36, 0.07);
    }

    /* =====================================================
       CARD HEADER
    ====================================================== */

    .inventory-edit-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 25px;

        padding: 27px 32px;

        background: #fffdfb;

        border-bottom: 1px solid #eee9e4;
    }

    .inventory-edit-section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        margin-bottom: 7px;

        color: #e66a16;

        font-size: 12px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .7px;
    }

    .inventory-edit-card-header h2 {
        margin: 0 0 5px;

        color: #263238;

        font-size: 21px;
        font-weight: 750;
    }

    .inventory-edit-card-header p {
        margin: 0;

        color: #8a9298;

        font-size: 13px;
    }

    /* =====================================================
       BUTTON KEMBALI
    ====================================================== */

    .inventory-edit-back {
        flex-shrink: 0;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        min-height: 42px;

        padding: 0 17px;

        border-radius: 10px;

        background: #ffffff;

        border: 1px solid #ddd8d2;

        color: #68737a;

        text-decoration: none;

        font-size: 13px;
        font-weight: 600;

        transition: .2s ease;
    }

    .inventory-edit-back:hover {
        color: #e66a16;

        background: #fff7ed;

        border-color: #f0c49f;
    }

    /* =====================================================
       ALERT
    ====================================================== */

    .inventory-edit-alert {
        display: flex;
        align-items: flex-start;

        gap: 14px;

        margin: 25px 32px 0;

        padding: 16px 18px;

        border-radius: 12px;

        background: #fff7ed;

        border: 1px solid #fed7aa;

        color: #9a3412;
    }

    .inventory-edit-alert-icon {
        width: 35px;
        height: 35px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #ffedd5;

        color: #ea580c;
    }

    .inventory-edit-alert strong {
        display: block;

        margin-bottom: 5px;

        font-size: 13px;
    }

    .inventory-edit-alert ul {
        margin: 0;
        padding-left: 18px;
    }

    .inventory-edit-alert li {
        font-size: 12px;
        line-height: 1.5;
    }

    /* =====================================================
       FORM
    ====================================================== */

    .inventory-edit-form {
        display: grid;

        grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 22px 24px;

        padding: 30px 32px 32px;
    }

    .inventory-edit-group {
        width: 100%;
    }

    .inventory-edit-group.full {
        grid-column: 1 / -1;
    }

    .inventory-edit-group label {
        display: block;

        margin-bottom: 8px;

        color: #3f474c;

        font-size: 13px;
        font-weight: 650;
    }

    .inventory-edit-group label span {
        color: #ef4444;
    }

    /* =====================================================
       INPUT
    ====================================================== */

    .inventory-edit-input {
        position: relative;

        display: flex;
        align-items: center;

        width: 100%;
        min-height: 49px;

        border: 1px solid #ddd9d4;

        border-radius: 11px;

        background: #ffffff;

        transition: .2s ease;
    }

    .inventory-edit-input:focus-within {
        border-color: #e66a16;

        box-shadow: 0 0 0 3px rgba(230, 106, 22, .10);
    }

    .inventory-edit-input i {
        width: 46px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #a4a09b;

        font-size: 14px;
    }

    .inventory-edit-input:focus-within i {
        color: #e66a16;
    }

    .inventory-edit-input input,
    .inventory-edit-input select {
        width: 100%;
        height: 47px;

        min-width: 0;

        padding: 0 14px 0 0;

        border: 0;
        outline: none;

        background: transparent;

        color: #333b40;

        font-family: "Poppins", sans-serif;

        font-size: 13px;
    }

    .inventory-edit-input select {
        cursor: pointer;
    }

    /* =====================================================
       DISABLED KODE
    ====================================================== */

    .inventory-edit-input input[readonly] {
        cursor: not-allowed;

        color: #737373;

        background: #f8f7f5;
    }

    /* =====================================================
       TEXTAREA
    ====================================================== */

    .inventory-edit-textarea {
        position: relative;

        display: flex;
        align-items: flex-start;

        width: 100%;

        border: 1px solid #ddd9d4;

        border-radius: 11px;

        background: #ffffff;
    }

    .inventory-edit-textarea:focus-within {
        border-color: #e66a16;

        box-shadow: 0 0 0 3px rgba(230, 106, 22, .10);
    }

    .inventory-edit-textarea i {
        width: 46px;

        padding-top: 16px;

        flex-shrink: 0;

        display: flex;
        justify-content: center;

        color: #a4a09b;
    }

    .inventory-edit-textarea textarea {
        width: 100%;

        min-height: 135px;

        padding: 14px 16px 14px 0;

        resize: vertical;

        border: 0;
        outline: none;

        background: transparent;

        color: #333b40;

        font-family: "Poppins", sans-serif;

        font-size: 13px;

        line-height: 1.65;
    }

    /* =====================================================
       ACTION
    ====================================================== */

    .inventory-edit-actions {
        grid-column: 1 / -1;

        display: flex;

        justify-content: flex-end;

        gap: 12px;

        padding-top: 20px;

        border-top: 1px solid #eee9e4;
    }

    .inventory-edit-btn {
        min-height: 46px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        padding: 0 20px;

        border-radius: 10px;

        font-family: "Poppins", sans-serif;

        font-size: 13px;
        font-weight: 650;

        text-decoration: none;

        cursor: pointer;

        transition: .2s ease;
    }

    .inventory-edit-cancel {
        background: #ffffff;

        color: #68737a;

        border: 1px solid #ddd8d2;
    }

    .inventory-edit-cancel:hover {
        background: #f8f7f5;
    }

    .inventory-edit-submit {
        border: 1px solid #e66a16;

        background: #e66a16;

        color: #ffffff;

        box-shadow: 0 6px 15px rgba(230, 106, 22, .18);
    }

    .inventory-edit-submit:hover {
        background: #d85f10;

        border-color: #d85f10;

        transform: translateY(-1px);
    }

    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 700px) {

        .inventory-edit-page {
            padding: 0 0 25px;
        }

        .inventory-edit-header {
            padding: 22px 20px;
        }

        .inventory-edit-header h1 {
            font-size: 23px;
        }

        .inventory-edit-icon {
            width: 55px;
            height: 55px;

            font-size: 21px;
        }

        .inventory-edit-card-header {
            align-items: flex-start;

            flex-direction: column;

            padding: 21px 20px;
        }

        .inventory-edit-back {
            width: 100%;
        }

        .inventory-edit-form {
            grid-template-columns: 1fr;

            padding: 22px 20px 25px;
        }

        .inventory-edit-group.full {
            grid-column: auto;
        }

        .inventory-edit-actions {
            grid-column: auto;

            flex-direction: column-reverse;
        }

        .inventory-edit-btn {
            width: 100%;
        }

        .inventory-edit-alert {
            margin-left: 20px;
            margin-right: 20px;
        }
    }

    @media (max-width: 430px) {

        .inventory-edit-header {
            padding: 19px 16px;
        }

        .inventory-edit-header h1 {
            font-size: 21px;
        }

        .inventory-edit-card-header {
            padding: 19px 16px;
        }

        .inventory-edit-form {
            padding: 20px 16px 22px;
        }

        .inventory-edit-alert {
            margin-left: 16px;
            margin-right: 16px;
        }
    }
</style>

@endsection


@section('content')

<div class="inventory-edit-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}

    <div class="inventory-edit-header">

        <div>

            <div class="inventory-edit-label">
                <i class="fa-solid fa-boxes-stacked"></i>
                Arsip Inventaris
            </div>

            <h1>
                Edit Inventaris
            </h1>

            <p>
                Perbarui data aset atau fasilitas
                yang tersimpan dalam inventaris perpustakaan.
            </p>

        </div>

        <div class="inventory-edit-icon">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>

    </div>


    {{-- =====================================================
        CARD
    ====================================================== --}}

    <div class="inventory-edit-card">

        <div class="inventory-edit-card-header">

            <div>

                <span class="inventory-edit-section-label">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Data Inventaris
                </span>

                <h2>
                    Form Edit Inventaris
                </h2>

                <p>
                    Periksa dan ubah data inventaris sesuai kondisi terbaru.
                </p>

            </div>

            <a
                href="{{ route('petugas.archive.inventaris') }}"
                class="inventory-edit-back"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>

        </div>


        {{-- =====================================================
            ERROR
        ====================================================== --}}

        @if ($errors->any())

            <div class="inventory-edit-alert">

                <div class="inventory-edit-alert-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <div>

                    <strong>
                        Periksa kembali data!
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
            FORM
        ====================================================== --}}

        <form
            action="{{ route('petugas.archive.inventaris.update', $inventaris->id) }}"
            method="POST"
            class="inventory-edit-form"
        >

            @csrf
            @method('PUT')


            {{-- KODE --}}

            <div class="inventory-edit-group">

                <label for="kode_inventaris">
                    Kode Inventaris
                    <span>*</span>
                </label>

                <div class="inventory-edit-input">

                    <i class="fa-solid fa-barcode"></i>

                    <input
                        type="text"
                        name="kode_inventaris"
                        id="kode_inventaris"
                        value="{{ old('kode_inventaris', $inventaris->kode_inventaris) }}"
                        readonly
                    >

                </div>

            </div>


            {{-- NAMA --}}

            <div class="inventory-edit-group">

                <label for="nama_barang">
                    Nama Barang
                    <span>*</span>
                </label>

                <div class="inventory-edit-input">

                    <i class="fa-solid fa-box"></i>

                    <input
                        type="text"
                        name="nama_barang"
                        id="nama_barang"
                        value="{{ old('nama_barang', $inventaris->nama_barang) }}"
                        placeholder="Contoh: Komputer Desktop"
                        required
                    >

                </div>

            </div>


            {{-- KATEGORI --}}

            <div class="inventory-edit-group">

                <label for="kategori">
                    Kategori
                    <span>*</span>
                </label>

                <div class="inventory-edit-input">

                    <i class="fa-solid fa-layer-group"></i>

                    <select
                        name="kategori"
                        id="kategori"
                        required
                    >

                        <option value="">
                            Pilih Kategori
                        </option>

                        <option
                            value="elektronik"
                            {{ old('kategori', $inventaris->kategori) == 'elektronik' ? 'selected' : '' }}
                        >
                            Elektronik
                        </option>

                        <option
                            value="furniture"
                            {{ old('kategori', $inventaris->kategori) == 'furniture' ? 'selected' : '' }}
                        >
                            Furniture
                        </option>

                        <option
                            value="peralatan"
                            {{ old('kategori', $inventaris->kategori) == 'peralatan' ? 'selected' : '' }}
                        >
                            Peralatan
                        </option>

                        <option
                            value="lainnya"
                            {{ old('kategori', $inventaris->kategori) == 'lainnya' ? 'selected' : '' }}
                        >
                            Lainnya
                        </option>

                    </select>

                </div>

            </div>


            {{-- JUMLAH --}}

            <div class="inventory-edit-group">

                <label for="jumlah">
                    Jumlah
                    <span>*</span>
                </label>

                <div class="inventory-edit-input">

                    <i class="fa-solid fa-hashtag"></i>

                    <input
                        type="number"
                        name="jumlah"
                        id="jumlah"
                        value="{{ old('jumlah', $inventaris->jumlah) }}"
                        min="1"
                        required
                    >

                </div>

            </div>


            {{-- KONDISI --}}

            <div class="inventory-edit-group">

                <label for="kondisi">
                    Kondisi
                    <span>*</span>
                </label>

                <div class="inventory-edit-input">

                    <i class="fa-solid fa-circle-check"></i>

                    <select
                        name="kondisi"
                        id="kondisi"
                        required
                    >

                        <option value="">
                            Pilih Kondisi
                        </option>

                        <option
                            value="baik"
                            {{ old('kondisi', $inventaris->kondisi) == 'baik' ? 'selected' : '' }}
                        >
                            Baik
                        </option>

                        <option
                            value="rusak_ringan"
                            {{ old('kondisi', $inventaris->kondisi) == 'rusak_ringan' ? 'selected' : '' }}
                        >
                            Rusak Ringan
                        </option>

                        <option
                            value="rusak_berat"
                            {{ old('kondisi', $inventaris->kondisi) == 'rusak_berat' ? 'selected' : '' }}
                        >
                            Rusak Berat
                        </option>

                    </select>

                </div>

            </div>


            {{-- LOKASI --}}

            <div class="inventory-edit-group">

                <label for="lokasi">
                    Lokasi
                </label>

                <div class="inventory-edit-input">

                    <i class="fa-solid fa-location-dot"></i>

                    <input
                        type="text"
                        name="lokasi"
                        id="lokasi"
                        value="{{ old('lokasi', $inventaris->lokasi) }}"
                        placeholder="Contoh: Ruang Perpustakaan"
                    >

                </div>

            </div>


            {{-- TANGGAL --}}

            <div class="inventory-edit-group">

                <label for="tanggal_pengadaan">
                    Tanggal Pengadaan
                </label>

                <div class="inventory-edit-input">

                    <i class="fa-solid fa-calendar-days"></i>

                    <input
                        type="date"
                        name="tanggal_pengadaan"
                        id="tanggal_pengadaan"
                        value="{{ old('tanggal_pengadaan', $inventaris->tanggal_pengadaan) }}"
                    >

                </div>

            </div>


            {{-- KETERANGAN --}}

            <div class="inventory-edit-group full">

                <label for="keterangan">
                    Keterangan
                </label>

                <div class="inventory-edit-textarea">

                    <i class="fa-solid fa-align-left"></i>

                    <textarea
                        name="keterangan"
                        id="keterangan"
                        rows="5"
                        placeholder="Tambahkan keterangan..."
                    >{{ old('keterangan', $inventaris->keterangan) }}</textarea>

                </div>

            </div>


            {{-- =================================================
                ACTION
            ================================================== --}}

            <div class="inventory-edit-actions">

                <a
                    href="{{ route('petugas.archive.inventaris') }}"
                    class="inventory-edit-btn inventory-edit-cancel"
                >
                    <i class="fa-solid fa-xmark"></i>
                    Batal
                </a>

                <button
                    type="submit"
                    class="inventory-edit-btn inventory-edit-submit"
                >
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection