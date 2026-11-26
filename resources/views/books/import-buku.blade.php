@extends('layouts.petugas')

@section('title', 'Import Buku')

@push('styles')
<link
    rel="stylesheet"
    href="{{ asset('css/petugas/import-buku.css') }}"
>
@endpush

@section('content')

<div class="import-book-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="import-book-header">

        <div class="import-book-heading">

            <div class="import-book-icon">
                <i class="fa-solid fa-file-excel"></i>
            </div>

            <div class="import-book-heading-text">

                <span class="import-book-eyebrow">
                    KOLEKSI PERPUSTAKAAN
                </span>

                <h1>Import Buku</h1>

                <p>
                    Tambahkan banyak buku sekaligus menggunakan file Excel.
                </p>

            </div>

        </div>

        <a
            href="{{ route('petugas.books.create') }}"
            class="back-book-button"
        >
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

    </div>


    {{-- =====================================================
         INFO UTAMA
    ====================================================== --}}

    <div class="import-info-card">

        <div class="import-info-icon">
            <i class="fa-solid fa-circle-info"></i>
        </div>

        <div class="import-info-content">

            <h3>Import Excel Lebih Fleksibel</h3>

            <p>
                Sistem membaca data berdasarkan nama kolom Excel.
                Kolom opsional yang tidak tersedia akan dikosongkan
                secara otomatis.
            </p>

        </div>

    </div>


    {{-- =====================================================
         INFORMASI JENIS BUKU
    ====================================================== --}}

    <div class="import-info-card book-type-info-card">

        <div class="import-info-icon">
            <i class="fa-solid fa-book-open"></i>
        </div>

        <div class="import-info-content">

            <h3>Jenis Buku & Semester</h3>

            <p>
                Gunakan <strong>literasi</strong> untuk buku bacaan
                umum dan <strong>paket</strong> untuk buku paket
                pembelajaran.
            </p>

            <p>
                Untuk buku <strong>paket</strong>, isi kolom
                <strong>semester</strong> dengan angka
                <strong>1</strong> atau <strong>2</strong>.
                Buku literasi tidak wajib memiliki semester.
            </p>

        </div>

    </div>


    {{-- =====================================================
         FORMAT DATA
    ====================================================== --}}

    <div class="import-book-card">

        <div class="import-section-heading">

            <div class="import-section-icon">
                <i class="fa-solid fa-table"></i>
            </div>

            <div>

                <h2>Format Data Excel</h2>

                <p>
                    Gunakan nama kolom berikut agar data dapat
                    dibaca oleh sistem.
                </p>

            </div>

        </div>


        {{-- =================================================
             KOLOM WAJIB
        ================================================== --}}

        <div class="column-group">

            <div class="column-group-title required-title">

                <i class="fa-solid fa-circle-check"></i>

                <span>Kolom Wajib</span>

            </div>

            <div class="column-grid">

                <div class="column-card required">

                    <div class="column-card-number">
                        01
                    </div>

                    <div class="column-card-content">

                        <strong>judul</strong>

                        <span>
                            Judul buku
                        </span>

                    </div>

                    <span class="required-badge">
                        Wajib
                    </span>

                </div>


                <div class="column-card required">

                    <div class="column-card-number">
                        02
                    </div>

                    <div class="column-card-content">

                        <strong>penulis</strong>

                        <span>
                            Nama penulis
                        </span>

                    </div>

                    <span class="required-badge">
                        Wajib
                    </span>

                </div>


                <div class="column-card required">

                    <div class="column-card-number">
                        03
                    </div>

                    <div class="column-card-content">

                        <strong>stok</strong>

                        <span>
                            Jumlah buku
                        </span>

                    </div>

                    <span class="required-badge">
                        Wajib
                    </span>

                </div>


                <div class="column-card required">

                    <div class="column-card-number">
                        04
                    </div>

                    <div class="column-card-content">

                        <strong>jenis_buku</strong>

                        <span>
                            literasi atau paket
                        </span>

                    </div>

                    <span class="required-badge">
                        Wajib
                    </span>

                </div>

            </div>

        </div>


        {{-- =================================================
             KOLOM SEMESTER
        ================================================== --}}

        <div class="column-group semester-group">

            <div class="column-group-title semester-title">

                <i class="fa-solid fa-graduation-cap"></i>

                <span>Kolom Semester</span>

            </div>

            <div class="column-grid">

                <div class="column-card semester-card">

                    <div class="column-card-number">
                        05
                    </div>

                    <div class="column-card-content">

                        <strong>semester</strong>

                        <span>
                            Semester buku paket
                        </span>

                    </div>

                    <span class="optional-badge">
                        Opsional
                    </span>

                </div>

            </div>

            <div class="semester-note">

                <i class="fa-solid fa-circle-info"></i>

                <span>
                    Isi <strong>1</strong> atau <strong>2</strong>
                    untuk buku paket. Buku literasi boleh dikosongkan.
                </span>

            </div>

        </div>


        {{-- =================================================
             KOLOM OPSIONAL
        ================================================== --}}

        <div class="column-group optional-group">

            <div class="column-group-title optional-title">

                <i class="fa-solid fa-circle-info"></i>

                <span>Kolom Opsional</span>

            </div>

            <div class="column-grid">

                <div class="column-card optional">

                    <div class="column-card-number">
                        06
                    </div>

                    <div class="column-card-content">

                        <strong>penerbit</strong>

                        <span>
                            Nama penerbit
                        </span>

                    </div>

                    <span class="optional-badge">
                        Opsional
                    </span>

                </div>


                <div class="column-card optional">

                    <div class="column-card-number">
                        07
                    </div>

                    <div class="column-card-content">

                        <strong>tahun</strong>

                        <span>
                            Tahun terbit
                        </span>

                    </div>

                    <span class="optional-badge">
                        Opsional
                    </span>

                </div>


                <div class="column-card optional">

                    <div class="column-card-number">
                        08
                    </div>

                    <div class="column-card-content">

                        <strong>kategori</strong>

                        <span>
                            Kategori buku
                        </span>

                    </div>

                    <span class="optional-badge">
                        Opsional
                    </span>

                </div>


                <div class="column-card optional">

                    <div class="column-card-number">
                        09
                    </div>

                    <div class="column-card-content">

                        <strong>rak</strong>

                        <span>
                            Lokasi rak buku
                        </span>

                    </div>

                    <span class="optional-badge">
                        Opsional
                    </span>

                </div>


                <div class="column-card optional">

                    <div class="column-card-number">
                        10
                    </div>

                    <div class="column-card-content">

                        <strong>deskripsi</strong>

                        <span>
                            Deskripsi buku
                        </span>

                    </div>

                    <span class="optional-badge">
                        Opsional
                    </span>

                </div>

            </div>

        </div>


    

            <div class="example-note">

                <i class="fa-solid fa-check"></i>

                <span>
                    Buku <strong>literasi</strong> tidak membutuhkan
                    semester. Buku <strong>paket</strong> harus
                    memiliki semester agar dapat digunakan sesuai
                    aturan semester aktif.
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TEMPLATE EXCEL
    ====================================================== --}}

    <div class="import-template-card">

        <div class="template-icon">

            <i class="fa-solid fa-file-arrow-down"></i>

        </div>

        <div class="template-content">

            <h2>Belum Punya File Excel?</h2>

            <p>
                Download template Excel yang sudah disiapkan
                dengan kolom jenis buku dan semester.
            </p>

        </div>

        <a
            href="{{ route('petugas.books.template') }}"
            class="download-template-button"
        >

            <i class="fa-solid fa-download"></i>

            <span>Download Template</span>

        </a>

    </div>


    {{-- =====================================================
         UPLOAD EXCEL
    ====================================================== --}}

    <div class="import-book-card upload-card">

        <div class="import-section-heading">

            <div class="import-section-icon">

                <i class="fa-solid fa-cloud-arrow-up"></i>

            </div>

            <div>

                <h2>Upload File Excel</h2>

                <p>
                    Pilih file Excel yang berisi data buku.
                </p>

            </div>

        </div>


        <form
            action="{{ route('petugas.books.import') }}"
            method="POST"
            enctype="multipart/form-data"
            id="importBookForm"
        >

            @csrf


            {{-- =================================================
                 UPLOAD BOX
            ================================================== --}}

            <div class="excel-upload-area">

                <label
                    for="file_excel"
                    class="excel-upload-box"
                    id="excelUploadBox"
                >

                    <div class="excel-upload-icon">

                        <i class="fa-solid fa-file-excel"></i>

                    </div>

                    <div class="excel-upload-content">

                        <strong id="uploadTitle">
                            Pilih File Excel
                        </strong>

                        <span id="uploadDescription">
                            Klik untuk memilih file
                        </span>

                        <small>
                            XLSX atau XLS
                        </small>

                    </div>

                    <input
                        type="file"
                        id="file_excel"
                        name="file_excel"
                        accept=".xlsx,.xls"
                        hidden
                        required
                    >

                </label>

            </div>


            {{-- =================================================
                 ERROR FILE
            ================================================== --}}

            @error('file_excel')

                <div class="form-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <span>
                        {{ $message }}
                    </span>

                </div>

            @enderror


            {{-- =================================================
                 ERROR UMUM
            ================================================== --}}

            @if(session('error'))

                <div class="form-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <span>
                        {{ session('error') }}
                    </span>

                </div>

            @endif


            {{-- =================================================
                 SUCCESS
            ================================================== --}}

            @if(session('success'))

                <div class="import-success">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('success') }}
                    </span>

                </div>

            @endif


            {{-- =================================================
                 ATURAN IMPORT
            ================================================== --}}

            <div class="import-rules">

                <div class="rule-item">

                    <div class="rule-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Kolom opsional tidak harus lengkap
                        </strong>

                        <span>
                            Penerbit, tahun, kategori, rak dan
                            deskripsi boleh dikosongkan.
                        </span>

                    </div>

                </div>


                <div class="rule-item">

                    <div class="rule-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Urutan kolom bebas
                        </strong>

                        <span>
                            Sistem membaca data berdasarkan nama
                            header Excel.
                        </span>

                    </div>

                </div>


                <div class="rule-item">

                    <div class="rule-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Jenis buku harus benar
                        </strong>

                        <span>
                            Isi <strong>literasi</strong> atau
                            <strong>paket</strong>.
                        </span>

                    </div>

                </div>


                <div class="rule-item">

                    <div class="rule-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Buku paket menggunakan semester
                        </strong>

                        <span>
                            Isi semester <strong>1</strong> atau
                            <strong>2</strong> untuk buku paket.
                        </span>

                    </div>

                </div>


                <div class="rule-item">

                    <div class="rule-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Buku literasi tidak wajib semester
                        </strong>

                        <span>
                            Kolom semester dapat dikosongkan untuk
                            buku literasi.
                        </span>

                    </div>

                </div>

            </div>




            {{-- =================================================
                 FOOTER BUTTON
            ================================================== --}}

            <div class="import-form-footer">

                <a
                    href="{{ route('petugas.books.create') }}"
                    class="cancel-import-button"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    <span>Kembali ke Tambah Manual</span>

                </a>


                <button
                    type="submit"
                    class="import-submit-button"
                    id="importSubmitButton"
                >

                    <i class="fa-solid fa-file-import"></i>

                    <span>Import Buku</span>

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('file_excel');
    const uploadBox = document.getElementById('excelUploadBox');
    const uploadTitle = document.getElementById('uploadTitle');
    const uploadDescription = document.getElementById('uploadDescription');
    const form = document.getElementById('importBookForm');
    const submitButton = document.getElementById('importSubmitButton');


    if (!fileInput || !uploadBox || !form || !submitButton) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TAMPILAN FILE
    |--------------------------------------------------------------------------
    */

    function updateSelectedFile(file) {

        if (!file) {
            uploadBox.classList.remove('has-file');

            uploadTitle.textContent = 'Pilih File Excel';

            uploadDescription.textContent =
                'Klik untuk memilih file';

            return;
        }

        const extension = file.name
            .split('.')
            .pop()
            .toLowerCase();

        if (extension !== 'xlsx' && extension !== 'xls') {

            alert('File harus berformat XLSX atau XLS.');

            fileInput.value = '';

            updateSelectedFile(null);

            return;
        }

        uploadBox.classList.add('has-file');

        uploadTitle.textContent = file.name;

        uploadDescription.textContent =
            'File siap untuk diimport';
    }


    /*
    |--------------------------------------------------------------------------
    | FILE INPUT
    |--------------------------------------------------------------------------
    */

    fileInput.addEventListener('change', function () {

        if (!this.files || !this.files.length) {
            updateSelectedFile(null);
            return;
        }

        updateSelectedFile(this.files[0]);

    });


    /*
    |--------------------------------------------------------------------------
    | DRAG OVER
    |--------------------------------------------------------------------------
    */

    uploadBox.addEventListener('dragover', function (event) {

        event.preventDefault();

        uploadBox.classList.add('dragging');

    });


    /*
    |--------------------------------------------------------------------------
    | DRAG LEAVE
    |--------------------------------------------------------------------------
    */

    uploadBox.addEventListener('dragleave', function () {

        uploadBox.classList.remove('dragging');

    });


    /*
    |--------------------------------------------------------------------------
    | DROP FILE
    |--------------------------------------------------------------------------
    */

    uploadBox.addEventListener('drop', function (event) {

        event.preventDefault();

        uploadBox.classList.remove('dragging');

        const files = event.dataTransfer.files;

        if (!files || !files.length) {
            return;
        }

        const file = files[0];

        const extension = file.name
            .split('.')
            .pop()
            .toLowerCase();

        if (extension !== 'xlsx' && extension !== 'xls') {

            alert('File harus berformat XLSX atau XLS.');

            return;
        }

        try {

            fileInput.files = files;

        } catch (error) {

            console.error(
                'Tidak dapat memasukkan file hasil drag:',
                error
            );

            return;
        }

        updateSelectedFile(file);

    });


    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', function () {

        if (
            !fileInput.files ||
            !fileInput.files.length
        ) {
            return;
        }

        submitButton.disabled = true;

        submitButton.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            <span>Memproses Import...</span>
        `;

    });

});
</script>

@endsection