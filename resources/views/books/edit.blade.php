
@extends('layouts.petugas')

@section('title', 'Edit Buku')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/petugas/edit-buku.css') }}">
@endpush

@section('content')

@php
    $oldKategori = old('kategori');

    if ($oldKategori !== null) {
        $oldKategori = is_array($oldKategori) ? $oldKategori : [$oldKategori];
    } else {
        $oldKategori = !empty($book->kategori)
            ? array_map('trim', explode(',', $book->kategori))
            : [''];
    }

    if (empty($oldKategori)) {
        $oldKategori = [''];
    }

    $copies = $book->copies ?? collect();

    $statusLabels = [
        'tersedia' => 'Tersedia',
        'dipinjam' => 'Dipinjam',
        'rusak' => 'Rusak',
        'hilang' => 'Hilang',
    ];
@endphp

<div class="edit-book-page">

    {{-- HEADER --}}
    <div class="edit-book-header">
        <div>
            <span class="edit-book-eyebrow">KOLEKSI BUKU</span>
            <h2>Edit Buku</h2>
            <p>Perbarui informasi buku dan kelola eksemplarnya.</p>
        </div>

        <a href="{{ route('petugas.books.index') }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali
        </a>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            Periksa kembali data yang kamu masukkan.
        </div>
    @endif

    <div class="edit-book-card">

        {{-- FORM UPDATE INFORMASI BUKU --}}
        <form
            action="{{ route('petugas.books.update', $book->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            {{-- INFORMASI UTAMA --}}
            <div class="form-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div>
                        <h3>Informasi Buku</h3>
                        <p>Masukkan informasi dasar mengenai buku.</p>
                    </div>
                </div>

                <div class="form-grid">

                    {{-- JUDUL --}}
                    <div class="form-group full-width">
                        <label for="judul">Judul Buku <span>*</span></label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-book-open"></i>
                            <input
                                type="text"
                                id="judul"
                                name="judul"
                                value="{{ old('judul', $book->judul) }}"
                                placeholder="Masukkan judul buku"
                                required
                            >
                        </div>

                        @error('judul')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- PENULIS --}}
                    <div class="form-group">
                        <label for="penulis">Penulis <span>*</span></label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-user-pen"></i>
                            <input
                                type="text"
                                id="penulis"
                                name="penulis"
                                value="{{ old('penulis', $book->penulis) }}"
                                placeholder="Nama penulis"
                                required
                            >
                        </div>

                        @error('penulis')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- PENERBIT --}}
                    <div class="form-group">
                        <label for="penerbit">Penerbit <span>*</span></label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-building"></i>
                            <input
                                type="text"
                                id="penerbit"
                                name="penerbit"
                                value="{{ old('penerbit', $book->penerbit) }}"
                                placeholder="Nama penerbit"
                                required
                            >
                        </div>

                        @error('penerbit')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- TAHUN --}}
                    <div class="form-group">
                        <label for="tahun">Tahun Terbit <span>*</span></label>

                        <div class="input-wrapper">
                            <i class="fa-regular fa-calendar"></i>
                            <input
                                type="number"
                                id="tahun"
                                name="tahun"
                                value="{{ old('tahun', $book->tahun) }}"
                                placeholder="Contoh: 2025"
                                min="1900"
                                max="{{ date('Y') }}"
                                required
                            >
                        </div>

                        @error('tahun')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- KATEGORI --}}
                    <div class="form-group kategori-field">
                        <label>Kategori Buku <span>*</span></label>

                        <div id="kategori-container">
                            @foreach($oldKategori as $kategori)
                                <div class="kategori-row">
                                    <div class="input-wrapper">
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
                            <small class="error-message">{{ $message }}</small>
                        @enderror

                        @error('kategori.*')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- JENIS BUKU --}}
                    <div class="form-group">
                        <label for="jenis_buku">Jenis Buku <span>*</span></label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-book-open-reader"></i>
                            <select id="jenis_buku" name="jenis_buku" required>
                                <option value="">-- Pilih Jenis Buku --</option>

                                <option
                                    value="literasi"
                                    {{ old('jenis_buku', strtolower($book->jenis_buku)) == 'literasi' ? 'selected' : '' }}
                                >
                                    Literasi
                                </option>

                                <option
                                    value="paket"
                                    {{ old('jenis_buku', strtolower($book->jenis_buku)) == 'paket' ? 'selected' : '' }}
                                >
                                    Paket
                                </option>
                            </select>
                        </div>

                        @error('jenis_buku')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- SEMESTER --}}
                    <div class="form-group">
                        <label for="semester">Semester</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <select id="semester" name="semester">
                                <option value="">-- Tidak Ada Semester --</option>

                                <option
                                    value="1"
                                    {{ old('semester', $book->semester) == 1 ? 'selected' : '' }}
                                >
                                    Semester 1
                                </option>

                                <option
                                    value="2"
                                    {{ old('semester', $book->semester) == 2 ? 'selected' : '' }}
                                >
                                    Semester 2
                                </option>
                            </select>
                        </div>

                        <small class="field-hint">
                            Buku paket wajib memiliki semester. Buku literasi tidak menggunakan semester.
                        </small>

                        @error('semester')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- RAK --}}
                    <div class="form-group">
                        <label for="rak">Rak Buku</label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-box-archive"></i>
                            <input
                                type="text"
                                id="rak"
                                name="rak"
                                value="{{ old('rak', $book->rak) }}"
                                placeholder="Contoh: A-01"
                            >
                        </div>

                        <small class="field-hint">
                            Rak digunakan sebagai lokasi penyimpanan dan acuan penambahan eksemplar.
                        </small>

                        @error('rak')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- STOK --}}
                    <div class="form-group">
                        <label for="stok">Jumlah Eksemplar Tersedia <span>*</span></label>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <input
                                type="number"
                                id="stok"
                                name="stok"
                                value="{{ old('stok', $book->stok) }}"
                                placeholder="Jumlah eksemplar tersedia"
                                min="1"
                                required
                            >
                        </div>

                        <small class="field-hint">
                            Perubahan jumlah mengikuti aturan pengelolaan eksemplar pada sistem.
                        </small>

                        @error('stok')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- DAFTAR EKSEMPLAR --}}
            <div class="form-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div>
                        <h3>Daftar Eksemplar Buku</h3>
                        <p>Cari eksemplar dan kelola status fisik buku.</p>
                    </div>
                </div>

                {{-- RINGKASAN --}}
                <div class="copy-summary">

                    <div class="copy-summary-item">
                        <span>Total Eksemplar</span>
                        <strong>{{ $copies->count() }}</strong>
                    </div>

                    <div class="copy-summary-item">
                        <span>Tersedia</span>
                        <strong>{{ $copies->where('status', 'tersedia')->count() }}</strong>
                    </div>

                    <div class="copy-summary-item">
                        <span>Dipinjam</span>
                        <strong>{{ $copies->where('status', 'dipinjam')->count() }}</strong>
                    </div>

                    <div class="copy-summary-item">
                        <span>Rusak</span>
                        <strong>{{ $copies->where('status', 'rusak')->count() }}</strong>
                    </div>

                    <div class="copy-summary-item">
                        <span>Hilang</span>
                        <strong>{{ $copies->where('status', 'hilang')->count() }}</strong>
                    </div>

                </div>

                {{-- PENCARIAN --}}
                <div class="copy-search-area">

                    <label for="copy-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Cari Eksemplar
                    </label>

                    <input
                        type="search"
                        id="copy-search"
                        placeholder="Masukkan kode atau nomor eksemplar..."
                        autocomplete="off"
                    >

                    <div class="copy-filter">
                        <label for="copy-status-filter">Filter Status</label>

                        <select id="copy-status-filter">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="dipinjam">Dipinjam</option>
                            <option value="rusak">Rusak</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>

                    <small class="field-hint">
                        Contoh pencarian: BK-1-3 atau nomor eksemplar 3.
                    </small>
                </div>

                {{-- TABEL --}}
                @if($copies->isNotEmpty())

                    <div class="copy-table-wrapper">

                        <table class="copy-table" id="copy-table">

                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Eksemplar</th>
                                    <th>Nomor</th>
                                    <th>Rak</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($copies->sortBy('nomor_eksemplar') as $copy)

                                    <tr
                                        class="copy-row"
                                        data-code="{{ strtolower($copy->kode_eksemplar ?? '') }}"
                                        data-number="{{ strtolower((string) $copy->nomor_eksemplar) }}"
                                        data-status="{{ $copy->status }}"
                                    >

                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $copy->kode_eksemplar ?? '-' }}</strong>
                                        </td>

                                        <td>{{ $copy->nomor_eksemplar }}</td>

                                        <td>{{ $copy->rak ?? '-' }}</td>

                                        <td>
                                            <span class="copy-status status-{{ $copy->status }}">
                                                {{ $statusLabels[$copy->status] ?? ucfirst($copy->status) }}
                                            </span>
                                        </td>

                                        <td class="copy-action-cell">

                                            @if($copy->status === 'tersedia')

                                                <button
                                                    type="submit"
                                                    form="damage-copy-{{ $copy->id }}"
                                                    class="damage-copy-button"
                                                >
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    Tandai Rusak
                                                </button>

                                            @elseif($copy->status === 'rusak')

                                                <span class="copy-action-disabled">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    Sudah Rusak
                                                </span>

                                            @elseif($copy->status === 'dipinjam')

                                                <span class="copy-action-disabled">
                                                    <i class="fa-solid fa-lock"></i>
                                                    Sedang Dipinjam
                                                </span>

                                            @else

                                                <span class="copy-action-disabled">
                                                    Tidak tersedia
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                                <tr id="copy-empty-result" style="display: none;">
                                    <td colspan="6" class="copy-empty-result">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        Eksemplar tidak ditemukan.
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                @else

                    <p class="field-hint">
                        Belum ada data eksemplar untuk buku ini.
                    </p>

                @endif

                <div class="copy-information">
                    <i class="fa-solid fa-circle-info"></i>
                    <p>
                        Status rusak hanya dapat diberikan pada eksemplar yang tersedia.
                        Eksemplar yang sedang dipinjam harus melalui proses pengembalian terlebih dahulu.
                    </p>
                </div>

            </div>

            {{-- DESKRIPSI --}}
            <div class="form-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fa-solid fa-align-left"></i>
                    </div>
                    <div>
                        <h3>Deskripsi Buku</h3>
                        <p>Tambahkan penjelasan singkat mengenai buku.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="6"
                        placeholder="Tuliskan deskripsi buku..."
                    >{{ old('deskripsi', $book->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            {{-- COVER --}}
            <div class="form-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fa-solid fa-image"></i>
                    </div>
                    <div>
                        <h3>Cover Buku</h3>
                        <p>Ganti cover buku jika diperlukan.</p>
                    </div>
                </div>

                <div class="cover-upload">

                    @if($book->cover)
                        <div class="current-cover">
                            <img
                                src="{{ asset('storage/' . $book->cover) }}"
                                alt="Cover {{ $book->judul }}"
                            >

                            <div>
                                <span>Cover Saat Ini</span>
                                <strong>{{ $book->judul }}</strong>
                            </div>
                        </div>
                    @endif

                    <label for="cover" class="upload-box">
                        <div class="upload-icon">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>

                        <div class="upload-content">
                            <strong>Pilih Cover Baru</strong>
                            <span>Klik untuk memilih file gambar</span>
                            <small>JPG, JPEG atau PNG</small>
                        </div>

                        <input
                            type="file"
                            id="cover"
                            name="cover"
                            accept="image/jpeg,image/png,image/jpg"
                        >
                    </label>

                    @error('cover')
                        <small class="error-message">{{ $message }}</small>
                    @enderror

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="form-footer">

                <a href="{{ route('petugas.books.index') }}" class="cancel-button">
                    Batal
                </a>

                <button type="submit" class="save-button">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Update Buku
                </button>

            </div>

        </form>

        {{-- FORM TERPISAH UNTUK MENANDAI EKSEMPLAR RUSAK --}}
        {{-- Form ini sengaja diletakkan di luar form Update Buku --}}
        @foreach($copies as $copy)

            @if($copy->status === 'tersedia')

                <form
                    id="damage-copy-{{ $copy->id }}"
                    action="{{ route('petugas.books.copies.damage', [
                        'book' => $book->id,
                        'copy' => $copy->id
                    ]) }}"
                    method="POST"
                    onsubmit="return confirm('Apakah kamu yakin ingin mengubah eksemplar {{ $copy->kode_eksemplar }} menjadi rusak?');"
                >
                    @csrf
                    @method('PUT')
                </form>

            @endif

        @endforeach

    </div>
</div>

{{-- LOGIKA SEMESTER --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const jenisBuku = document.getElementById('jenis_buku');
    const semester = document.getElementById('semester');

    if (!jenisBuku || !semester) return;

    function updateSemester() {

        if (jenisBuku.value === 'paket') {
            semester.disabled = false;
            semester.required = true;
        } else {
            semester.value = '';
            semester.disabled = true;
            semester.required = false;
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
            <div class="input-wrapper">
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

{{-- PENCARIAN DAN FILTER EKSEMPLAR --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('copy-search');
    const statusFilter = document.getElementById('copy-status-filter');
    const rows = document.querySelectorAll('.copy-row');
    const emptyResult = document.getElementById('copy-empty-result');

    if (!searchInput || !statusFilter) return;

    function filterCopies() {

        const keyword = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;

        let visibleCount = 0;

        rows.forEach(function (row) {

            const code = row.dataset.code || '';
            const number = row.dataset.number || '';
            const status = row.dataset.status || '';

            const matchesSearch =
                code.includes(keyword) ||
                number.includes(keyword);

            const matchesStatus =
                selectedStatus === '' ||
                status === selectedStatus;

            const isVisible = matchesSearch && matchesStatus;

            row.style.display = isVisible ? '' : 'none';

            if (isVisible) {
                visibleCount++;
            }
        });

        if (emptyResult) {
            emptyResult.style.display =
                visibleCount === 0 ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', filterCopies);
    statusFilter.addEventListener('change', filterCopies);

});
</script>

@endsection
