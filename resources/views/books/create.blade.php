
@extends('layouts.petugas')

@section('title', 'Tambah Buku')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/tambah-buku.css') }}">
@endpush

@section('content')

<div class="add-book-page">

    {{-- HEADER --}}
    <div class="add-book-header">

        <div class="add-book-heading">
            <div class="add-book-icon">
                <i class="fa-solid fa-book-medical"></i>
            </div>

            <div>
                <span class="add-book-eyebrow">
                    KOLEKSI PERPUSTAKAAN
                </span>

                <h1>Tambah Buku</h1>

                <p>
                    Tambahkan buku baru beserta jumlah eksemplarnya ke dalam koleksi perpustakaan.
                </p>
            </div>
        </div>

        <a
            href="{{ route('petugas.books.index') }}"
            class="back-book-button"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>

    </div>


    {{-- FORM CARD --}}
    <div class="add-book-card">

        <form
            action="{{ route('petugas.books.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            {{-- INFORMASI BUKU --}}
            <div class="add-form-section">

                <div class="add-section-heading">

                    <div class="add-section-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>

                    <div>
                        <h2>Informasi Buku</h2>

                        <p>
                            Lengkapi informasi utama buku yang akan ditambahkan.
                        </p>
                    </div>

                </div>


                <div class="add-form-grid">

                    {{-- COVER --}}
                    <div class="add-form-group cover-field">

                        <label>Cover Buku</label>

                        <label
                            for="cover"
                            class="cover-upload-box"
                        >

                            <div class="cover-upload-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>

                            <div class="cover-upload-text">

                                <strong>
                                    Upload Cover Buku
                                </strong>

                                <span>
                                    Klik untuk memilih gambar
                                </span>

                                <small>
                                    JPG, JPEG atau PNG
                                </small>

                            </div>

                            <input
                                type="file"
                                id="cover"
                                name="cover"
                                accept="image/jpeg,image/png,image/jpg"
                            >

                        </label>

                        <div id="cover-preview" class="cover-preview" style="display:none;">
                            <img id="cover-preview-image" src="" alt="Preview cover">
                            <span id="cover-file-name"></span>
                        </div>

                        @error('cover')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- JUDUL --}}
                    <div class="add-form-group">

                        <label for="judul">
                            Judul Buku
                            <span>*</span>
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-solid fa-book"></i>

                            <input
                                type="text"
                                id="judul"
                                name="judul"
                                value="{{ old('judul') }}"
                                placeholder="Masukkan judul buku"
                                required
                            >

                        </div>

                        @error('judul')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- PENULIS --}}
                    <div class="add-form-group">

                        <label for="penulis">
                            Penulis
                            <span>*</span>
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-solid fa-user-pen"></i>

                            <input
                                type="text"
                                id="penulis"
                                name="penulis"
                                value="{{ old('penulis') }}"
                                placeholder="Nama penulis"
                                required
                            >

                        </div>

                        @error('penulis')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- PENERBIT --}}
                    <div class="add-form-group">

                        <label for="penerbit">
                            Penerbit
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-solid fa-building"></i>

                            <input
                                type="text"
                                id="penerbit"
                                name="penerbit"
                                value="{{ old('penerbit') }}"
                                placeholder="Nama penerbit"
                            >

                        </div>

                        @error('penerbit')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- TAHUN --}}
                    <div class="add-form-group">

                        <label for="tahun">
                            Tahun Terbit
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-regular fa-calendar"></i>

                            <input
                                type="number"
                                id="tahun"
                                name="tahun"
                                value="{{ old('tahun') }}"
                                min="1900"
                                max="{{ date('Y') }}"
                                placeholder="Contoh: 2025"
                            >

                        </div>

                        @error('tahun')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- KATEGORI --}}
                    <div class="add-form-group kategori-field">

                        <label>
                            Kategori Buku
                        </label>

                        <div id="kategori-container">

                            @php
                                $oldKategori = old('kategori', ['']);

                                if (!is_array($oldKategori)) {
                                    $oldKategori = [$oldKategori];
                                }

                                if (empty($oldKategori)) {
                                    $oldKategori = [''];
                                }
                            @endphp

                            @foreach($oldKategori as $kategori)

                                <div class="kategori-row">

                                    <div class="add-input-wrapper">

                                        <i class="fa-solid fa-layer-group"></i>

                                        <input
                                            type="text"
                                            name="kategori[]"
                                            value="{{ $kategori }}"
                                            placeholder="Contoh: Matematika"
                                        >

                                    </div>

                                    <button
                                        type="button"
                                        class="remove-kategori"
                                        title="Hapus kategori"
                                    >
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>

                                </div>

                            @endforeach

                        </div>


                        <button
                            type="button"
                            id="add-kategori-button"
                            class="add-kategori-button"
                        >
                            <i class="fa-solid fa-plus"></i>
                            Tambah Kategori
                        </button>


                        @error('kategori')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                        @error('kategori.*')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- JENIS BUKU --}}
                    <div class="add-form-group">

                        <label for="jenis_buku">
                            Jenis Buku
                            <span>*</span>
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-solid fa-book-open-reader"></i>

                            <select
                                id="jenis_buku"
                                name="jenis_buku"
                                required
                            >

                                <option value="">
                                    -- Pilih Jenis Buku --
                                </option>

                                <option
                                    value="literasi"
                                    {{ old('jenis_buku') == 'literasi' ? 'selected' : '' }}
                                >
                                    Literasi
                                </option>

                                <option
                                    value="paket"
                                    {{ old('jenis_buku') == 'paket' ? 'selected' : '' }}
                                >
                                    Paket
                                </option>

                            </select>

                        </div>

                        @error('jenis_buku')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- SEMESTER --}}
                    <div
                        class="add-form-group"
                        id="semester-group"
                        style="display: none;"
                    >

                        <label for="semester">
                            Semester
                            <span>*</span>
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-solid fa-graduation-cap"></i>

                            <select
                                id="semester"
                                name="semester"
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

                        @error('semester')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- RAK --}}
                    <div class="add-form-group">

                        <label for="rak">
                            Rak Buku
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-solid fa-box-archive"></i>

                            <input
                                type="text"
                                id="rak"
                                name="rak"
                                value="{{ old('rak') }}"
                                placeholder="Contoh: Rak A1"
                            >

                        </div>

                        <small class="field-hint">
                            Rak awal untuk eksemplar buku. Rak setiap eksemplar dapat dikelola secara terpisah.
                        </small>

                        @error('rak')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>


                    {{-- JUMLAH EKSEMPLAR --}}
                    <div class="add-form-group">

                        <label for="stok">
                            Jumlah Eksemplar
                            <span>*</span>
                        </label>

                        <div class="add-input-wrapper">

                            <i class="fa-solid fa-boxes-stacked"></i>

                            <input
                                type="number"
                                id="stok"
                                name="stok"
                                value="{{ old('stok', 1) }}"
                                min="1"
                                placeholder="Jumlah eksemplar"
                                required
                            >

                        </div>

                        <small class="field-hint">
                            Setiap eksemplar akan dibuatkan nomor dan kode identitas secara otomatis oleh sistem.
                        </small>

                        @error('stok')
                            <small class="form-error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- INFORMASI EKSEMPLAR --}}
            <div class="add-form-section">

                <div class="add-section-heading">

                    <div class="add-section-icon">
                        <i class="fa-solid fa-barcode"></i>
                    </div>

                    <div>
                        <h2>Pengelolaan Eksemplar</h2>

                        <p>
                            Sistem akan membuat identitas setiap eksemplar berdasarkan jumlah yang dimasukkan.
                        </p>
                    </div>

                </div>

                <div class="eksemplar-info">

                    <div class="eksemplar-info-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <div>
                        <strong>Nomor dan kode eksemplar otomatis</strong>

                        <p>
                            Setelah buku disimpan, sistem akan membuat nomor eksemplar dan kode unik
                            untuk setiap salinan fisik. Status awal setiap eksemplar adalah tersedia.
                        </p>
                    </div>

                </div>

                <div class="eksemplar-preview">

                    <div class="eksemplar-preview-heading">
                        <span>Perkiraan jumlah eksemplar</span>
                        <strong id="jumlah-eksemplar-preview">1 eksemplar</strong>
                    </div>

                    <div class="eksemplar-preview-list" id="eksemplar-preview-list">
                        <div class="eksemplar-preview-item">
                            <span>Eksemplar 1</span>
                            <span class="preview-status">Tersedia</span>
                        </div>
                    </div>

                    <small>
                        Pratinjau ini hanya gambaran jumlah eksemplar. Kode sebenarnya dibuat setelah buku disimpan.
                    </small>

                </div>

            </div>


            {{-- DESKRIPSI --}}
            <div class="add-form-section">

                <div class="add-section-heading">

                    <div class="add-section-icon">
                        <i class="fa-solid fa-align-left"></i>
                    </div>

                    <div>
                        <h2>Deskripsi Buku</h2>

                        <p>
                            Tambahkan sinopsis atau informasi singkat mengenai buku.
                        </p>
                    </div>

                </div>


                <div class="add-form-group">

                    <label for="deskripsi">
                        Deskripsi Buku
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="6"
                        placeholder="Masukkan deskripsi atau sinopsis buku..."
                    >{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <small class="form-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

            </div>


            {{-- ACTION --}}
            <div class="add-form-footer">

                <button
                    type="reset"
                    class="reset-book-button"
                >
                    <i class="fa-solid fa-rotate-left"></i>
                    Reset
                </button>

                <button
                    type="submit"
                    class="save-book-button"
                >
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Buku
                </button>

            </div>

        </form>

    </div>

</div>


{{-- LOGIKA JENIS BUKU DAN SEMESTER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const jenisBuku = document.getElementById('jenis_buku');
    const semesterGroup = document.getElementById('semester-group');
    const semester = document.getElementById('semester');

    if (!jenisBuku || !semesterGroup || !semester) return;

    function updateSemester() {

        if (jenisBuku.value === 'paket') {

            semesterGroup.style.display = 'block';
            semester.disabled = false;
            semester.required = true;

        } else {

            semesterGroup.style.display = 'none';
            semester.disabled = true;
            semester.required = false;
            semester.value = '';

        }
    }

    jenisBuku.addEventListener('change', updateSemester);
    updateSemester();

});
</script>


{{-- LOGIKA KATEGORI DINAMIS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('kategori-container');
    const tombolTambah = document.getElementById('add-kategori-button');

    if (!container || !tombolTambah) return;

    tombolTambah.addEventListener('click', function () {

        const row = document.createElement('div');

        row.className = 'kategori-row';

        row.innerHTML = `
            <div class="add-input-wrapper">
                <i class="fa-solid fa-layer-group"></i>

                <input
                    type="text"
                    name="kategori[]"
                    placeholder="Contoh: Sains"
                >
            </div>

            <button
                type="button"
                class="remove-kategori"
                title="Hapus kategori"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        `;

        container.appendChild(row);

        const inputBaru = row.querySelector('input');

        if (inputBaru) {
            inputBaru.focus();
        }

    });


    container.addEventListener('click', function (event) {

        const tombolHapus = event.target.closest('.remove-kategori');

        if (!tombolHapus) return;

        const rows = container.querySelectorAll('.kategori-row');

        if (rows.length > 1) {

            tombolHapus.closest('.kategori-row').remove();

        } else {

            const input = tombolHapus
                .closest('.kategori-row')
                .querySelector('input');

            if (input) {
                input.value = '';
                input.focus();
            }

        }

    });

});
</script>


{{-- PREVIEW COVER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const coverInput = document.getElementById('cover');
    const preview = document.getElementById('cover-preview');
    const previewImage = document.getElementById('cover-preview-image');
    const fileName = document.getElementById('cover-file-name');

    if (!coverInput || !preview || !previewImage || !fileName) return;

    coverInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            preview.style.display = 'none';
            previewImage.src = '';
            fileName.textContent = '';
            return;
        }

        if (!file.type.startsWith('image/')) {
            alert('File yang dipilih harus berupa gambar.');
            this.value = '';
            preview.style.display = 'none';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            previewImage.src = event.target.result;
            fileName.textContent = file.name;
            preview.style.display = 'flex';
        };

        reader.readAsDataURL(file);

    });

});
</script>


{{-- PREVIEW JUMLAH EKSEMPLAR --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const stokInput = document.getElementById('stok');
    const jumlahPreview = document.getElementById('jumlah-eksemplar-preview');
    const daftarPreview = document.getElementById('eksemplar-preview-list');

    if (!stokInput || !jumlahPreview || !daftarPreview) return;

    function updateEksemplarPreview() {

        let jumlah = parseInt(stokInput.value, 10);

        if (isNaN(jumlah) || jumlah < 1) {
            jumlah = 1;
        }

        jumlahPreview.textContent = jumlah + ' eksemplar';

        daftarPreview.innerHTML = '';

        const batasPreview = Math.min(jumlah, 10);

        for (let i = 1; i <= batasPreview; i++) {

            const item = document.createElement('div');

            item.className = 'eksemplar-preview-item';

            const nomor = document.createElement('span');
            nomor.textContent = 'Eksemplar ' + i;

            const status = document.createElement('span');
            status.className = 'preview-status';
            status.textContent = 'Tersedia';

            item.appendChild(nomor);
            item.appendChild(status);

            daftarPreview.appendChild(item);

        }

        if (jumlah > 10) {

            const sisa = document.createElement('div');

            sisa.className = 'eksemplar-preview-more';
            sisa.textContent = '+ ' + (jumlah - 10) + ' eksemplar lainnya';

            daftarPreview.appendChild(sisa);

        }

    }

    stokInput.addEventListener('input', updateEksemplarPreview);

    updateEksemplarPreview();

});
</script>

@endsection
