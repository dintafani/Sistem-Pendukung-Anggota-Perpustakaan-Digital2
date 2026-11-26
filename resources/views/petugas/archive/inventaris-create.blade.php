@extends('layouts.petugas')

@section('title', 'Tambah Inventaris')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/coba.css') }}"
>

@endsection

@section('content')

<div class="inventory-create-page">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="inventory-create-header">

        <div class="inventory-create-header-content">

            <div class="inventory-create-label">
                <i class="fa-solid fa-boxes-stacked"></i>
                Arsip Inventaris
            </div>

            <h1>
                Tambah Inventaris
            </h1>

            <p>
                Tambahkan data aset atau fasilitas baru
                ke dalam inventaris perpustakaan.
            </p>

        </div>

        <div class="inventory-create-header-icon">

            <div class="inventory-create-icon-circle">
                <i class="fa-solid fa-box"></i>
            </div>

        </div>

    </div>


    {{-- =====================================================
        FORM CARD
    ====================================================== --}}
    <div class="inventory-create-card">

        {{-- HEADER CARD --}}
        <div class="inventory-create-card-header">

            <div>

                <span class="inventory-create-section-label">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Data Inventaris
                </span>

                <h2>
                    Form Tambah Inventaris
                </h2>

                <p>
                    Isi data inventaris dengan lengkap dan benar.
                </p>

            </div>

            <a
                href="{{ route('petugas.archive.inventaris') }}"
                class="inventory-create-back"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>

        </div>


        {{-- =====================================================
            ERROR VALIDASI
        ====================================================== --}}
        @if ($errors->any())

            <div class="inventory-create-alert">

                <div class="inventory-create-alert-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <div class="inventory-create-alert-content">

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
            action="{{ route('petugas.archive.inventaris.store') }}"
            method="POST"
            class="inventory-create-form"
        >

            @csrf


            {{-- =================================================
                KODE INVENTARIS
            ================================================== --}}
            <div class="inventory-form-group">

                <label for="kode_inventaris">
                    Kode Inventaris
                    <span>*</span>
                </label>

                <div class="inventory-input">

                    <i class="fa-solid fa-barcode"></i>

                    <input
                        type="text"
                        name="kode_inventaris"
                        id="kode_inventaris"
                        value="{{ old('kode_inventaris') }}"
                        placeholder="Contoh: INV-001"
                        autocomplete="off"
                        required
                    >

                </div>

            </div>


            {{-- =================================================
                NAMA BARANG
            ================================================== --}}
            <div class="inventory-form-group">

                <label for="nama_barang">
                    Nama Barang
                    <span>*</span>
                </label>

                <div class="inventory-input">

                    <i class="fa-solid fa-box"></i>

                    <input
                        type="text"
                        name="nama_barang"
                        id="nama_barang"
                        value="{{ old('nama_barang') }}"
                        placeholder="Contoh: Komputer Desktop"
                        autocomplete="off"
                        required
                    >

                </div>

            </div>


            {{-- =================================================
                KATEGORI
            ================================================== --}}
            <div class="inventory-form-group">

                <label for="kategori">
                    Kategori
                    <span>*</span>
                </label>

                <div class="inventory-input">

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
                            {{ old('kategori') == 'elektronik' ? 'selected' : '' }}
                        >
                            Elektronik
                        </option>

                        <option
                            value="furniture"
                            {{ old('kategori') == 'furniture' ? 'selected' : '' }}
                        >
                            Furniture
                        </option>

                        <option
                            value="peralatan"
                            {{ old('kategori') == 'peralatan' ? 'selected' : '' }}
                        >
                            Peralatan
                        </option>

                        <option
                            value="lainnya"
                            {{ old('kategori') == 'lainnya' ? 'selected' : '' }}
                        >
                            Lainnya
                        </option>

                    </select>

                </div>

            </div>


            {{-- =================================================
                JUMLAH
            ================================================== --}}
            <div class="inventory-form-group">

                <label for="jumlah">
                    Jumlah
                    <span>*</span>
                </label>

                <div class="inventory-input">

                    <i class="fa-solid fa-hashtag"></i>

                    <input
                        type="number"
                        name="jumlah"
                        id="jumlah"
                        value="{{ old('jumlah', 1) }}"
                        min="1"
                        placeholder="Jumlah barang"
                        required
                    >

                </div>

            </div>


            {{-- =================================================
                KONDISI
            ================================================== --}}
            <div class="inventory-form-group">

                <label for="kondisi">
                    Kondisi
                    <span>*</span>
                </label>

                <div class="inventory-input">

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
                            {{ old('kondisi') == 'baik' ? 'selected' : '' }}
                        >
                            Baik
                        </option>

                        <option
                            value="rusak_ringan"
                            {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}
                        >
                            Rusak Ringan
                        </option>

                        <option
                            value="rusak_berat"
                            {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}
                        >
                            Rusak Berat
                        </option>

                    </select>

                </div>

            </div>


            {{-- =================================================
                LOKASI
            ================================================== --}}
            <div class="inventory-form-group">

                <label for="lokasi">
                    Lokasi
                </label>

                <div class="inventory-input">

                    <i class="fa-solid fa-location-dot"></i>

                    <input
                        type="text"
                        name="lokasi"
                        id="lokasi"
                        value="{{ old('lokasi') }}"
                        placeholder="Contoh: Ruang Perpustakaan"
                    >

                </div>

            </div>


            {{-- =================================================
                TANGGAL
            ================================================== --}}
            <div class="inventory-form-group">

                <label for="tanggal_pengadaan">
                    Tanggal Pengadaan
                </label>

                <div class="inventory-input">

                    <i class="fa-solid fa-calendar-days"></i>

                    <input
                        type="date"
                        name="tanggal_pengadaan"
                        id="tanggal_pengadaan"
                        value="{{ old('tanggal_pengadaan') }}"
                    >

                </div>

            </div>


            {{-- =================================================
                KETERANGAN
            ================================================== --}}
            <div class="inventory-form-group inventory-full">

                <label for="keterangan">
                    Keterangan
                </label>

                <div class="inventory-textarea">

                    <i class="fa-solid fa-align-left"></i>

                    <textarea
                        name="keterangan"
                        id="keterangan"
                        rows="5"
                        placeholder="Tambahkan keterangan jika diperlukan..."
                    >{{ old('keterangan') }}</textarea>

                </div>

            </div>


            {{-- =================================================
                ACTION
            ================================================== --}}
            <div class="inventory-create-actions">

                <a
                    href="{{ route('petugas.archive.inventaris') }}"
                    class="inventory-btn inventory-btn-cancel"
                >
                    <i class="fa-solid fa-xmark"></i>
                    Batal
                </a>

                <button
                    type="submit"
                    class="inventory-btn inventory-btn-submit"
                >
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Inventaris
                </button>

            </div>

        </form>

    </div>

</div>

@endsection