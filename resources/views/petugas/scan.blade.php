@extends('layouts.petugas')

@section('title', 'Pindai Barcode')

@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/petugas/scan-barcode.css') }}"
>

<style>
    .journal-view-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 10px;
        background: #f59e0b;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .journal-view-button:hover {
        background: #d97706;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(245, 158, 11, .25);
    }

    .journal-not-available {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 10px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
    }

    .journal-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 6px 10px;
        border-radius: 8px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 12px;
        font-weight: 700;
    }

    /* =====================================================
       MODAL JURNAL SISWA
    ===================================================== */

    .journal-modal {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, .72);
        backdrop-filter: blur(4px);
    }

    .journal-modal.show {
        display: flex;
    }

    .journal-modal-box {
        width: min(900px, 100%);
        max-height: 90vh;
        overflow: hidden;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 25px 70px rgba(0, 0, 0, .25);
        animation: journalModalIn .2s ease;
    }

    @keyframes journalModalIn {
        from {
            opacity: 0;
            transform: translateY(15px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .journal-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }

    .journal-modal-title {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .journal-modal-icon {
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff7ed;
        color: #f59e0b;
        font-size: 20px;
    }

    .journal-modal-title span {
        display: block;
        margin-bottom: 3px;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
    }

    .journal-modal-title h2 {
        margin: 0;
        color: #111827;
        font-size: 20px;
        font-weight: 800;
    }

    .journal-modal-close {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 10px;
        background: #f3f4f6;
        color: #6b7280;
        cursor: pointer;
        font-size: 18px;
        transition: all .2s ease;
    }

    .journal-modal-close:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .journal-modal-body {
        max-height: calc(90vh - 87px);
        overflow-y: auto;
        padding: 24px;
    }

    .journal-student-card {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #f9fafb;
    }

    .journal-student-avatar {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f59e0b;
        color: #fff;
        font-size: 19px;
    }

    .journal-student-info {
        min-width: 0;
    }

    .journal-student-info span {
        display: block;
        color: #9ca3af;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .journal-student-info strong {
        display: block;
        margin-top: 3px;
        color: #111827;
        font-size: 16px;
    }

    .journal-book-card {
        margin-bottom: 20px;
        padding: 16px;
        border: 1px solid #fed7aa;
        border-radius: 14px;
        background: #fff7ed;
    }

    .journal-book-label {
        margin-bottom: 5px;
        color: #c2410c;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .journal-book-title {
        margin: 0;
        color: #7c2d12;
        font-size: 18px;
        font-weight: 800;
    }

    .journal-section {
        margin-bottom: 18px;
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
    }

    .journal-section:last-child {
        margin-bottom: 0;
    }

    .journal-section-header {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 10px;
    }

    .journal-section-header i {
        color: #f59e0b;
        font-size: 16px;
    }

    .journal-section-header h3 {
        margin: 0;
        color: #111827;
        font-size: 15px;
        font-weight: 800;
    }

    .journal-text {
        margin: 0;
        color: #4b5563;
        font-size: 14px;
        line-height: 1.75;
        white-space: pre-line;
        word-break: break-word;
    }

    .journal-readonly-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 20px;
        padding: 13px 15px;
        border-radius: 10px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        line-height: 1.5;
    }

    .journal-readonly-note i {
        margin-top: 2px;
    }

    .journal-empty {
        padding: 45px 20px;
        text-align: center;
    }

    .journal-empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f3f4f6;
        color: #9ca3af;
        font-size: 25px;
    }

    .journal-empty h3 {
        margin: 0 0 7px;
        color: #374151;
        font-size: 17px;
    }

    .journal-empty p {
        margin: 0;
        color: #9ca3af;
        font-size: 13px;
    }

    @media (max-width: 640px) {
        .journal-modal {
            padding: 10px;
        }

        .journal-modal-box {
            max-height: 94vh;
            border-radius: 14px;
        }

        .journal-modal-header {
            padding: 16px;
        }

        .journal-modal-body {
            padding: 16px;
            max-height: calc(94vh - 78px);
        }

        .journal-modal-title h2 {
            font-size: 17px;
        }
    }
</style>

@endpush


@section('content')

<div class="scan-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="scan-header">

        <div class="scan-heading">

            <div class="scan-heading-icon">
                <i class="fa-solid fa-qrcode"></i>
            </div>

            <div>

                <span class="scan-eyebrow">
                    TRANSAKSI PERPUSTAKAAN
                </span>

                <h1>
                    Pindai QR Code Siswa
                </h1>

                <p>
                    Scan QR code siswa satu kali untuk menampilkan
                    seluruh peminjaman literasi dan paket.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MAIN GRID
    ====================================================== --}}

    <div class="scan-layout">

        {{-- =================================================
             SCANNER
        ================================================== --}}

        <div class="scanner-card">

            <div class="scanner-card-header">

                <div class="scanner-title-icon">
                    <i class="fa-solid fa-camera"></i>
                </div>

                <div>

                    <h2>
                        Kamera Scanner
                    </h2>

                    <p>
                        Arahkan kamera ke QR Code siswa
                    </p>

                </div>

            </div>


            <div class="scanner-wrapper">

                <div id="reader"></div>

                <div class="scanner-corner corner-tl"></div>
                <div class="scanner-corner corner-tr"></div>
                <div class="scanner-corner corner-bl"></div>
                <div class="scanner-corner corner-br"></div>

            </div>


            <div class="scanner-status">

                <span class="status-dot"></span>

                <span id="statusScan">
                    Menunggu Scan...
                </span>

            </div>


            <button
                type="button"
                onclick="scanLagi()"
                class="scan-again-button"
            >
                <i class="fa-solid fa-rotate-right"></i>
                Scan Lagi
            </button>


            <div class="scanner-divider">
                <span>ATAU</span>
            </div>


            {{-- =================================================
                 INPUT MANUAL
            ================================================== --}}

            <div class="upload-section">

                <div class="upload-heading">

                    <div class="upload-icon">
                        <i class="fa-solid fa-keyboard"></i>
                    </div>

                    <div>

                        <h3>
                            Input Kode Anggota
                        </h3>

                        <p>
                            Masukkan kode QR Code anggota jika kamera
                            tidak dapat digunakan.
                        </p>

                    </div>

                </div>


                <div class="manual-barcode-form">

                    <label for="manualBarcode">
                        Kode QR Code Anggota
                    </label>


                    <div class="manual-barcode-input">

                        <i class="fa-solid fa-qrcode"></i>

                        <input
                            type="text"
                            id="manualBarcode"
                            placeholder="Contoh: QR17840887277"
                            autocomplete="off"
                            maxlength="50"
                        >

                    </div>


                    <small class="manual-barcode-help">
                        Masukkan kode yang tertera pada kartu anggota siswa.
                    </small>


                    <button
                        type="button"
                        id="manualBarcodeBtn"
                        class="scan-image-button"
                        onclick="prosesBarcodeManual()"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Cari Anggota
                    </button>

                </div>

            </div>

        </div>


        {{-- =================================================
             HASIL SCAN
        ================================================== --}}

        <div class="result-card">

            <div class="result-header">

                <div>

                    <span class="result-eyebrow">
                        HASIL PEMINDAIAN
                    </span>

                    <h2>
                        Hasil Scan QR Code
                    </h2>

                    <p>
                        Semua transaksi siswa ditampilkan setelah
                        QR code berhasil dipindai.
                    </p>

                </div>


                <div class="result-header-icon">
                    <i class="fa-solid fa-id-card"></i>
                </div>

            </div>


            {{-- INFO QR CODE--}}

            <div class="barcode-info">

                <div class="barcode-info-item">

                    <span class="barcode-label">
                        Status Scan
                    </span>

                    <strong
                        id="statusScanResult"
                        class="barcode-value"
                    >
                        Menunggu Scan...
                    </strong>

                </div>


                <div class="barcode-info-item">

                    <span class="barcode-label">
                        QR Code
                    </span>

                    <strong
                        id="barcodeResult"
                        class="barcode-value barcode-code"
                    >
                        -
                    </strong>

                </div>

            </div>


            {{-- HASIL --}}

            <div
                id="scanResult"
                class="scan-result"
            >

                <div class="empty-scan">

                    <div class="empty-scan-icon">

                        <i class="fa-solid fa-qrcode"></i>

                    </div>

                    <h3>
                        Belum Ada QR Code
                    </h3>

                    <p>
                        Silakan scan QR Code siswa.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL PILIHAN BUKU
========================================================= --}}

<div
    id="borrowingPopup"
    class="borrowing-popup"
>

    <div class="borrowing-popup-box">

        <div class="borrowing-popup-header">

            <div class="borrowing-popup-title">

                <div
                    id="borrowingPopupIcon"
                    class="borrowing-popup-icon"
                >
                    <i class="fa-solid fa-book"></i>
                </div>

                <div>

                    <h2 id="borrowingPopupTitle">
                        Peminjaman Buku
                    </h2>

                    <p id="borrowingPopupSubtitle">
                        Daftar transaksi peminjaman siswa
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="tutupBorrowingPopup()"
                class="borrowing-popup-close"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <div
            id="borrowingPopupBody"
            class="borrowing-popup-body"
        ></div>

    </div>

</div>


{{-- =========================================================
     MODAL JURNAL SISWA
========================================================= --}}

<div
    id="journalModal"
    class="journal-modal"
>

    <div class="journal-modal-box">

        <div class="journal-modal-header">

            <div class="journal-modal-title">

                <div class="journal-modal-icon">

                    <i class="fa-solid fa-book-open-reader"></i>

                </div>

                <div>

                    <span>
                        PEMERIKSAAN JURNAL
                    </span>

                    <h2>
                        Jurnal Bacaan Siswa
                    </h2>

                </div>

            </div>


            <button
                type="button"
                class="journal-modal-close"
                onclick="tutupJournalModal()"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <div
            id="journalModalBody"
            class="journal-modal-body"
        ></div>

    </div>

</div>


{{-- =========================================================
     MODAL RIWAYAT
========================================================= --}}

<div
    id="historyModal"
    class="scan-modal"
>

    <div class="history-modal-box">

        <div class="modal-header">

            <div>

                <span class="modal-eyebrow">
                    DATA TRANSAKSI
                </span>

                <h2>

                    <i class="fa-solid fa-clock-rotate-left"></i>

                    Riwayat Peminjaman

                </h2>

            </div>


            <button
                type="button"
                onclick="tutupHistory()"
                class="modal-close"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <div
            id="historyContent"
            class="history-content"
        >

            <div class="modal-empty">

                <i class="fa-solid fa-folder-open"></i>

                <h3>
                    Belum ada data
                </h3>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL REWARD
========================================================= --}}

<div
    id="rewardModal"
    class="scan-modal"
>

    <div class="reward-modal-box">

        <div class="modal-header">

            <div>

                <span class="modal-eyebrow">
                    GAMIFIKASI LITERASI
                </span>

                <h2>

                    <i class="fa-solid fa-gift"></i>

                    Tukar Poin

                </h2>

            </div>


            <button
                type="button"
                onclick="tutupReward()"
                class="modal-close"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>


        <form
            id="rewardForm"
            method="POST"
            class="reward-form"
        >

            @csrf

            <input
                type="hidden"
                id="rewardUserId"
                name="user_id"
            >


            <div class="current-point">

                <span>
                    Poin Saat Ini
                </span>

                <strong id="currentPoint">
                    ⭐ 0
                </strong>

            </div>


            <div class="reward-input-group">

                <label for="rewardPoint">
                    Jumlah Poin Yang Ditukar
                </label>

                <input
                    type="number"
                    id="rewardPoint"
                    name="poin"
                    min="1"
                    required
                    placeholder="Masukkan jumlah poin"
                >

            </div>


            <button
                type="submit"
                class="reward-submit"
            >
                <i class="fa-solid fa-gift"></i>
                Tukar Poin
            </button>

        </form>

    </div>

</div>


{{-- =========================================================
     HTML5 QR CODE
========================================================= --}}

<script src="https://unpkg.com/html5-qrcode"></script>


<script>

let scanner = new Html5Qrcode("reader");

let sudahScan = false;

let scannerRunning = false;

let dataLiterasi = [];

let dataPaket = [];

let dataSiswa = null;

let dataAcademicSetting = null;


const statusScan =
    document.getElementById("statusScan");


const statusScanResult =
    document.getElementById("statusScanResult");


const barcodeResult =
    document.getElementById("barcodeResult");


const scanResult =
    document.getElementById("scanResult");


/* =========================================================
   MULAI SCAN
========================================================= */

function mulaiScan() {

    sudahScan = false;

    statusScan.innerHTML =
        "Mencari kamera...";

    statusScanResult.innerHTML =
        "Mencari kamera...";

    statusScanResult.classList.remove("success");


    Html5Qrcode.getCameras()

        .then(cameras => {

            if (!cameras || cameras.length === 0) {

                statusScan.innerHTML =
                    "❌ Kamera tidak ditemukan";

                statusScanResult.innerHTML =
                    "Kamera tidak ditemukan";

                return;

            }


            const camera =
                cameras.find(camera =>
                    /back|rear|environment/i.test(
                        camera.label || ''
                    )
                ) || cameras[0];


            return scanner.start(

                camera.id,

                {
                    fps: 10,

                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },

             QRCodeBerhasil,

                () => {}

            );

        })

        .then(() => {

            if (!scannerRunning) {

                scannerRunning = true;

                statusScan.innerHTML =
                    "Kamera aktif — siap memindai";

                statusScanResult.innerHTML =
                    "Kamera aktif";

            }

        })

        .catch(error => {

            console.error(error);

            scannerRunning = false;

            statusScan.innerHTML =
                "❌ Kamera tidak dapat digunakan";

            statusScanResult.innerHTML =
                "Kamera gagal digunakan";

        });

}


/* =========================================================
   QR CODE BERHASIL
========================================================= */

function QRCodeBerhasil(text) {

    if (sudahScan) {
        return;
    }

    sudahScan = true;


    const barcode =
        String(text)
            .trim()
            .toUpperCase();


    barcodeResult.innerHTML =
        escapeHtml(barcode);


    statusScan.innerHTML =
        "✅ QR Code berhasil dibaca";

    statusScanResult.innerHTML =
        "QR Code berhasil dibaca";

    statusScanResult.classList.add("success");


    if (scannerRunning) {

        scanner.stop()

            .then(() => {

                scannerRunning = false;

            })

            .catch(() => {

                scannerRunning = false;

            });

    }


    prosesBarcode(barcode);

}


/* =========================================================
   INPUT MANUAL
========================================================= */

function prosesBarcodeManual() {

    const input =
        document.getElementById("manualBarcode");


    if (!input) {
        return;
    }


    const barcode =
        input.value.trim();


    if (barcode === "") {

        statusScan.innerHTML =
            "❌ QR Codebelum dimasukkan";

        statusScanResult.innerHTML =
            "QR Code belum dimasukkan";

        input.focus();

        return;

    }


    const barcodeNormal =
        barcode.toUpperCase();


    if (!/^QR\d+$/.test(barcodeNormal)) {

        statusScan.innerHTML =
            "❌ Format QR Code tidak valid";

        statusScanResult.innerHTML =
            "Format QR Code tidak valid";

        statusScanResult.classList.remove(
            "success"
        );


        scanResult.innerHTML = `

            <div class="scan-error">

                <div class="scan-error-icon">

                    <i class="fa-solid fa-circle-xmark"></i>

                </div>

                <div>

                    <h3>
                        QR Code Tidak Valid
                    </h3>

                    <p>
                        Format QR Code harus seperti
                        <strong>QR17840887277</strong>.
                    </p>

                </div>

            </div>

        `;


        input.focus();

        return;

    }


    barcodeResult.innerHTML =
        escapeHtml(barcodeNormal);


    statusScan.innerHTML =
        "🔎 Mencari data anggota...";


    statusScanResult.innerHTML =
        "Mencari data anggota...";


    statusScanResult.classList.remove(
        "success"
    );


    if (scannerRunning) {

        scanner.stop()

            .then(() => {

                scannerRunning = false;

                prosesBarcode(barcodeNormal);

            })

            .catch(() => {

                scannerRunning = false;

                prosesBarcode(barcodeNormal);

            });

    } else {

        prosesBarcode(barcodeNormal);

    }

}


/* =========================================================
   FORMAT STATUS
========================================================= */

function formatStatus(status) {

    switch (status) {

        case "menunggu":
            return "Menunggu";

        case "dipinjam":
            return "Dipinjam";

        case "menunggu_pengembalian":
            return "Menunggu Pengembalian";

        case "dikembalikan":
            return "Dikembalikan";

        case "ditolak":
            return "Ditolak";

        default:
            return status ?? "-";

    }

}


/* =========================================================
   FORMAT JENIS
========================================================= */

function formatJenisBuku(jenis) {

    switch (jenis) {

        case "literasi":
            return "Literasi";

        case "paket":
            return "Paket";

        default:
            return jenis || "-";

    }

}


/* =========================================================
   FORMAT SEMESTER
========================================================= */

function formatSemester(jenis, semester) {

    if (jenis !== "paket") {
        return "Tidak menggunakan semester";
    }


    if (
        semester === null ||
        semester === undefined ||
        semester === ""
    ) {

        return "Belum ditentukan";

    }


    return "Semester " + semester;

}


/* =========================================================
   DUE STATUS
========================================================= */

function buatDueStatus(item) {

    if (
        !item.due_status ||
        item.due_status === "none"
    ) {

        if (item.status === "menunggu") {

            return `

                <div class="due-status due-waiting">

                    <div class="due-status-icon">

                        <i class="fa-solid fa-hourglass-half"></i>

                    </div>

                    <div class="due-status-content">

                        <strong>
                            Menunggu persetujuan
                        </strong>

                        <span>
                            Tanggal jatuh tempo akan dimulai
                            setelah peminjaman disetujui.
                        </span>

                    </div>

                </div>

            `;

        }

        return "";

    }


    if (item.due_status === "late") {

        const daysLate =
            Number(item.days_late ?? 0);


        return `

            <div class="due-status due-late">

                <div class="due-status-icon">

                    <i class="fa-solid fa-triangle-exclamation"></i>

                </div>

                <div class="due-status-content">

                    <strong>
                        Terlambat ${daysLate} hari
                    </strong>

                    <span>
                        Buku sudah melewati tanggal jatuh tempo.
                    </span>

                </div>

            </div>

        `;

    }


    if (item.due_status === "today") {

        return `

            <div class="due-status due-today">

                <div class="due-status-icon">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <div class="due-status-content">

                    <strong>
                        Jatuh tempo hari ini
                    </strong>

                    <span>
                        Buku harus dikembalikan hari ini.
                    </span>

                </div>

            </div>

        `;

    }


    if (item.due_status === "safe") {

        const daysLeft =
            Number(item.days_left ?? 0);


        const teksHari =
            daysLeft === 1
                ? "Tersisa 1 hari peminjaman"
                : `Tersisa ${daysLeft} hari peminjaman`;


        return `

            <div class="due-status due-safe">

                <div class="due-status-icon">

                    <i class="fa-solid fa-circle-check"></i>

                </div>

                <div class="due-status-content">

                    <strong>
                        ${teksHari}
                    </strong>

                    <span>
                        Buku masih dalam batas waktu peminjaman.
                    </span>

                </div>

            </div>

        `;

    }


    return "";

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(value) {

    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

}


/* =========================================================
   LIHAT JURNAL
   PETUGAS HANYA MELIHAT ISI JURNAL
========================================================= */

function bukaJournalModal(journal, user, book) {

    const modal =
        document.getElementById("journalModal");

    const body =
        document.getElementById("journalModalBody");


    if (!modal || !body) {
        return;
    }


    journal = journal || {};
    user = user || {};
    book = book || {};


    const ringkasan =
        journal.ringkasan || "";

    const yangDipelajari =
        journal.yang_dipelajari || "";

    const bagianMenarik =
        journal.bagian_menarik || "";


    body.innerHTML = `

        <div class="journal-student-card">

            <div class="journal-student-avatar">

                <i class="fa-solid fa-user"></i>

            </div>

            <div class="journal-student-info">

                <span>
                    Siswa
                </span>

                <strong>
                    ${escapeHtml(user.name || '-')}
                </strong>

            </div>

        </div>


        <div class="journal-book-card">

            <div class="journal-book-label">
                Buku Literasi
            </div>

            <h3 class="journal-book-title">
                ${escapeHtml(book.judul || '-')}
            </h3>

        </div>


        <div class="journal-section">

            <div class="journal-section-header">

                <i class="fa-solid fa-align-left"></i>

                <h3>
                    Ringkasan Buku
                </h3>

            </div>

            <p class="journal-text">
                ${escapeHtml(
                    ringkasan || 'Siswa belum mengisi ringkasan.'
                )}
            </p>

        </div>


        <div class="journal-section">

            <div class="journal-section-header">

                <i class="fa-solid fa-lightbulb"></i>

                <h3>
                    Yang Dipelajari
                </h3>

            </div>

            <p class="journal-text">
                ${escapeHtml(
                    yangDipelajari ||
                    'Siswa belum mengisi bagian yang dipelajari.'
                )}
            </p>

        </div>


        <div class="journal-section">

            <div class="journal-section-header">

                <i class="fa-solid fa-star"></i>

                <h3>
                    Bagian Menarik
                </h3>

            </div>

            <p class="journal-text">
                ${escapeHtml(
                    bagianMenarik ||
                    'Siswa belum mengisi bagian menarik.'
                )}
            </p>

        </div>


        <div class="journal-readonly-note">

            <i class="fa-solid fa-eye"></i>

            <span>
                Jurnal ini hanya dapat dilihat oleh petugas
                untuk memastikan siswa benar-benar membaca
                buku. Petugas tidak dapat mengubah isi jurnal
                dari halaman ini.
            </span>

        </div>

    `;


    modal.classList.add("show");

    document.body.style.overflow = "hidden";

}


/* =========================================================
   TUTUP JURNAL
========================================================= */

function tutupJournalModal() {

    const modal =
        document.getElementById("journalModal");


    if (modal) {

        modal.classList.remove("show");

    }


    document.body.style.overflow = "";

}


/* =========================================================
   BUAT ITEM PEMINJAMAN
========================================================= */

function buatItemPeminjaman(item, nomor) {

    const book =
        item.book || {};


    const jenis =
        book.jenis_buku || "";


    const jenisText =
        formatJenisBuku(jenis);


    const semester =
        formatSemester(
            jenis,
            book.semester
        );


    const statusText =
        formatStatus(item.status);


    const dueStatusHTML =
        buatDueStatus(item);


    let tombol = "";


    /* =====================================================
   AKSI TRANSAKSI TANPA SCAN ULANG
===================================================== */

if (item.aksi === "approve") {

    tombol += `
     <div class="transaction-action-buttons">

        <button
            type="button"
            class="approve-button approve-loan"
            onclick="verifikasiPeminjaman(${Number(item.id)}, this)"
        >

            <i class="fa-solid fa-circle-check"></i>

            Verifikasi Peminjaman

        </button>
 <button
                type="button"
                class="reject-button"
                onclick="tolakPeminjaman(${Number(item.id)}, this)"
            >
                <i class="fa-solid fa-xmark"></i>
                Tolak Peminjaman
            </button>

        </div>
    `;

}


/* =====================================================
   PENGEMBALIAN
===================================================== */

if (item.aksi === "return") {

    tombol += `
      <div class="transaction-action-buttons">

        <button
            type="button"
            class="approve-button approve-return"
            onclick="terimaPengembalian(${Number(item.id)}, this)"
        >

            <i class="fa-solid fa-book-open"></i>

            Terima Pengembalian

        </button>
  
     <button 
        type="button" 
        class="reject-button reject-return" 
        onclick="tolakPengembalian(${Number(item.id)}, this)" 
        > 
     <i class="fa-solid fa-xmark"></i> 
     Tolak Pengembalian 
     </button> 
     </div>


    `;

}


/* =====================================================
   STATUS DIPINJAM
===================================================== */

if (item.status === "dipinjam") {

    tombol += `

        <div class="transaction-success">

            <i class="fa-solid fa-circle-check"></i>

            Sudah Dipinjam

        </div>

    `;

}


/* =====================================================
   STATUS DIKEMBALIKAN
===================================================== */

if (item.status === "dikembalikan") {

    tombol += `

        <div class="transaction-returned">

            <i class="fa-solid fa-book"></i>

            Sudah Dikembalikan

        </div>

    `;

}

    /* =====================================================
       JURNAL SISWA
    ===================================================== */

    if (
        jenis === "literasi" &&
        item.reading_journal &&
        item.reading_journal.id
    ) {

        const journalJSON =
            JSON.stringify(
                item.reading_journal
            )
            .replace(/</g, '\\u003c')
            .replace(/>/g, '\\u003e')
            .replace(/&/g, '\\u0026')
            .replace(/'/g, '\\u0027')
            .replace(/"/g, '&quot;');


        const userJSON =
            JSON.stringify(
                dataSiswa || {}
            )
            .replace(/</g, '\\u003c')
            .replace(/>/g, '\\u003e')
            .replace(/&/g, '\\u0026')
            .replace(/'/g, '\\u0027')
            .replace(/"/g, '&quot;');


        const bookJSON =
            JSON.stringify(
                book
            )
            .replace(/</g, '\\u003c')
            .replace(/>/g, '\\u003e')
            .replace(/&/g, '\\u0026')
            .replace(/'/g, '\\u0027')
            .replace(/"/g, '&quot;');


        tombol += `

            <button
                type="button"
                class="journal-view-button"
                title="Lihat isi jurnal siswa"
                onclick="bukaJournalModal(
                    JSON.parse(this.dataset.journal),
                    JSON.parse(this.dataset.user),
                    JSON.parse(this.dataset.book)
                )"
                data-journal="${journalJSON}"
                data-user="${userJSON}"
                data-book="${bookJSON}"
            >

                <i class="fa-solid fa-book-open-reader"></i>

                Lihat Jurnal

            </button>

        `;

    }


    /* =====================================================
       JURNAL BELUM ADA
    ===================================================== */

    if (
        jenis === "literasi" &&
        !item.reading_journal
    ) {

        tombol += `

            <div class="journal-not-available">

                <i class="fa-solid fa-file-circle-xmark"></i>

                Jurnal belum diisi siswa

            </div>

        `;

    }


    /* =====================================================
   ITEM TRANSAKSI
===================================================== */

const kodeEksemplar =
    item.book_copy?.kode_eksemplar ??
    item.bookCopy?.kode_eksemplar ??
    '-';

return `

    <div class="borrowing-item-popup">

        <div class="borrowing-main">

            <div class="borrowing-book-number">

                ${Number(nomor)}

            </div>


            <div class="borrowing-book-icon">

                <i class="fa-solid fa-book"></i>

            </div>


            <div class="borrowing-content">

                <h4>

                    ${escapeHtml(
                        book.judul ?? '-'
                    )}

                </h4>


                <span class="transaction-id">

                    ID Transaksi #${Number(item.id)}

                </span>


                <div class="borrowing-info">

                    <div>

                        <span>
                            Jenis Buku
                        </span>

                        <strong>

                            ${escapeHtml(
                                jenisText
                            )}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Semester
                        </span>

                        <strong>

                            ${escapeHtml(
                                semester
                            )}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Status
                        </span>

                        <strong>

                            ${escapeHtml(
                                statusText
                            )}

                        </strong>

                    </div>

                </div>


                <div class="borrowing-info">

                    <div>

                        <span>
                            Kode Eksemplar
                        </span>

                        <strong>

                            ${escapeHtml(
                                kodeEksemplar
                            )}

                        </strong>

                    </div>

                </div>


                <div class="borrowing-info">

                    <div>

                        <span>
                            Tanggal Pinjam
                        </span>

                        <strong>

                            ${escapeHtml(
                                item.tanggal_pinjam ?? '-'
                            )}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Jatuh Tempo
                        </span>

                        <strong>

                            ${escapeHtml(
                                item.tanggal_jatuh_tempo ?? '-'
                            )}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Tanggal Kembali
                        </span>

                        <strong>

                            ${escapeHtml(
                                item.tanggal_kembali ?? '-'
                            )}

                        </strong>

                    </div>

                </div>


                ${dueStatusHTML}


                ${
                    item.reading_journal &&
                    item.reading_journal.status
                        ? `

                            <div class="journal-status">

                                <i class="fa-solid fa-file-lines"></i>

                                Status Jurnal:

                                ${escapeHtml(
                                    item.reading_journal.status
                                )}

                            </div>

                          `
                        : ''
                }


                <div class="borrowing-action">

                    ${tombol}

                </div>

            </div>

        </div>

    </div>

`;

}
/* =========================================================
   VERIFIKASI PEMINJAMAN TANPA RELOAD
========================================================= */

function verifikasiPeminjaman(id, button) {

    if (!id || !button) {
        return;
    }

    const tombolLama = button.innerHTML;

    button.disabled = true;

    button.innerHTML = `
        <i class="fa-solid fa-spinner fa-spin"></i>
        Memverifikasi...
    `;


    fetch(`/petugas/borrowings/${Number(id)}/approve`, {

        method: "POST",

        headers: {

            "Content-Type": "application/json",

            "X-CSRF-TOKEN": "{{ csrf_token() }}",

            "Accept": "application/json"

        },

        body: JSON.stringify({})

    })

    .then(async response => {

        const data = await response.json()
            .catch(() => ({}));


        if (!response.ok) {

            throw new Error(
                data.message ||
                "Verifikasi peminjaman gagal."
            );

        }


        if (
            data.success === false
        ) {

            throw new Error(
                data.message ||
                "Verifikasi peminjaman gagal."
            );

        }


        return data;

    })

    .then(data => {

        /* =========================================
           CARI TRANSAKSI DI DATA JAVASCRIPT
        ========================================= */

        const semuaData = [
            ...dataLiterasi,
            ...dataPaket
        ];


        const item = semuaData.find(
            transaksi =>
                Number(transaksi.id) === Number(id)
        );


        if (item) {

            item.status = "dipinjam";

            item.aksi = null;

        }


        /* =========================================
           GANTI TOMBOL
        ========================================= */

        button.outerHTML = `

            <div class="transaction-success">

                <i class="fa-solid fa-circle-check"></i>

                Sudah Dipinjam

            </div>

        `;


        /* =========================================
           UPDATE JUMLAH PAKET
        ========================================= */

        if (
            item &&
            item.book &&
            item.book.jenis_buku === "paket"
        ) {

            const jumlahAktif =
                Number(dataSiswa?.jumlah_paket ?? 0);

            if (dataSiswa) {

                dataSiswa.jumlah_paket =
                    jumlahAktif + 1;


                dataSiswa.sisa_paket =
                    Math.max(
                        Number(dataSiswa.batas_paket ?? 0) -
                        dataSiswa.jumlah_paket,
                        0
                    );

            }

        }


        alert(
            data.message ||
            "Peminjaman berhasil diverifikasi."
        );

    })

    .catch(error => {

        console.error(error);

        button.disabled = false;

        button.innerHTML = tombolLama;

        alert(
            error.message ||
            "Terjadi kesalahan saat memverifikasi peminjaman."
        );

    });

}


/* =========================================================
   TERIMA PENGEMBALIAN TANPA RELOAD
========================================================= */

function terimaPengembalian(id, button) {

    if (!id || !button) {
        return;
    }


    const tombolLama = button.innerHTML;

    button.disabled = true;

    button.innerHTML = `
        <i class="fa-solid fa-spinner fa-spin"></i>
        Memproses...
    `;


    fetch(
        `/petugas/borrowings/${Number(id)}/approve-return`,
        {

            method: "POST",

            headers: {

                "Content-Type": "application/json",

                "X-CSRF-TOKEN": "{{ csrf_token() }}",

                "Accept": "application/json"

            },

            body: JSON.stringify({})

        }
    )

    .then(async response => {

        const data = await response.json()
            .catch(() => ({}));


        if (!response.ok) {

            throw new Error(
                data.message ||
                "Pengembalian gagal diproses."
            );

        }


        if (data.success === false) {

            throw new Error(
                data.message ||
                "Pengembalian gagal diproses."
            );

        }


        return data;

    })

    .then(data => {

        const semuaData = [
            ...dataLiterasi,
            ...dataPaket
        ];


        const item = semuaData.find(
            transaksi =>
                Number(transaksi.id) === Number(id)
        );


        if (item) {

            item.status = "dikembalikan";

            item.aksi = null;

            item.tanggal_kembali =
                data.tanggal_kembali ??
                item.tanggal_kembali ??
                "-";

        }


        button.outerHTML = `

            <div class="transaction-returned">

                <i class="fa-solid fa-book"></i>

                Sudah Dikembalikan

            </div>

        `;


        alert(
            data.message ||
            "Pengembalian berhasil diterima."
        );

    })

    .catch(error => {

        console.error(error);

        button.disabled = false;

        button.innerHTML = tombolLama;

        alert(
            error.message ||
            "Terjadi kesalahan saat memproses pengembalian."
        );

    });

}
/* =========================================================
   TOLAK PEMINJAMAN
   STATUS TETAP MENUNGGU
   STOK TIDAK BERUBAH
========================================================= */

function tolakPeminjaman(id, button) {

    if (!id || !button) {
        return;
    }

    if (!confirm("Tolak peminjaman buku ini?")) {
        return;
    }

    const tombolLama = button.innerHTML;

    button.disabled = true;

    button.innerHTML = `
        <i class="fa-solid fa-spinner fa-spin"></i>
        Memproses...
    `;

    fetch(`/petugas/borrowings/${Number(id)}/reject`, {

        method: "POST",

        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },

        body: JSON.stringify({})
    })

    .then(async response => {

        const data = await response.json()
            .catch(() => ({}));

        if (!response.ok) {
            throw new Error(
                data.message ||
                "Penolakan peminjaman gagal."
            );
        }

        if (data.success === false) {
            throw new Error(
                data.message ||
                "Penolakan peminjaman gagal."
            );
        }

        return data;
    })

    .then(data => {

        const semuaData = [
            ...dataLiterasi,
            ...dataPaket
        ];

        const item = semuaData.find(
            transaksi =>
                Number(transaksi.id) === Number(id)
        );

        /*
         * PENTING:
         * Status TIDAK diubah menjadi ditolak.
         * Stok juga tidak disentuh.
         */
        if (item) {
            item.aksi = null;
        }

        button.outerHTML = `
            <div class="transaction-rejected">
                <i class="fa-solid fa-circle-xmark"></i>
                Peminjaman Ditolak
            </div>
        `;

        alert(
            data.message ||
            "Peminjaman berhasil ditolak."
        );

    })

    .catch(error => {

        console.error(error);

        button.disabled = false;

        button.innerHTML = tombolLama;

        alert(
            error.message ||
            "Terjadi kesalahan saat menolak peminjaman."
        );
    });
}


/* =========================================================
   TOLAK PENGEMBALIAN
   STATUS TETAP DIPINJAM
   STOK TIDAK BERTAMBAH
========================================================= */

function tolakPengembalian(id, button) {

    if (!id || !button) {
        return;
    }

    if (!confirm(
        "Tolak pengembalian buku ini? Buku tetap dianggap dipinjam."
    )) {
        return;
    }

    const tombolLama = button.innerHTML;

    button.disabled = true;

    button.innerHTML = `
        <i class="fa-solid fa-spinner fa-spin"></i>
        Memproses...
    `;

    fetch(`/petugas/borrowings/${Number(id)}/reject-return`, {

        method: "POST",

        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },

        body: JSON.stringify({})
    })

    .then(async response => {

        const data = await response.json()
            .catch(() => ({}));

        if (!response.ok) {
            throw new Error(
                data.message ||
                "Penolakan pengembalian gagal."
            );
        }

        if (data.success === false) {
            throw new Error(
                data.message ||
                "Penolakan pengembalian gagal."
            );
        }

        return data;
    })

    .then(data => {

        const semuaData = [
            ...dataLiterasi,
            ...dataPaket
        ];

        const item = semuaData.find(
            transaksi =>
                Number(transaksi.id) === Number(id)
        );

        /*
         * PENTING:
         * Buku tetap DIPINJAM.
         * Stok TIDAK bertambah.
         */
        if (item) {
            item.status = "dipinjam";
            item.aksi = null;
        }

        button.outerHTML = `
            <div class="transaction-success">
                <i class="fa-solid fa-book"></i>
                Pengembalian Ditolak — Buku Masih Dipinjam
            </div>
        `;

        alert(
            data.message ||
            "Pengembalian ditolak. Buku masih tercatat sebagai dipinjam."
        );

    })

    .catch(error => {

        console.error(error);

        button.disabled = false;

        button.innerHTML = tombolLama;

        alert(
            error.message ||
            "Terjadi kesalahan saat menolak pengembalian."
        );
    });
}

/* =========================================================
   POPUP PEMINJAMAN
========================================================= */

function bukaBorrowingPopup(jenis) {

    const popup =
        document.getElementById(
            "borrowingPopup"
        );


    const body =
        document.getElementById(
            "borrowingPopupBody"
        );


    const title =
        document.getElementById(
            "borrowingPopupTitle"
        );


    const subtitle =
        document.getElementById(
            "borrowingPopupSubtitle"
        );


    const icon =
        document.getElementById(
            "borrowingPopupIcon"
        );


    if (!popup || !body) {
        return;
    }


    let data = [];


    if (jenis === "literasi") {

        data = dataLiterasi;

        title.innerHTML =
            "Buku Literasi";

        subtitle.innerHTML =
            "Daftar seluruh transaksi buku literasi siswa";

        icon.innerHTML =
            '<i class="fa-solid fa-book-open"></i>';

    } else {

        data = dataPaket;

        title.innerHTML =
            "Buku Paket";

        subtitle.innerHTML =
            "Daftar seluruh transaksi buku paket siswa";

        icon.innerHTML =
            '<i class="fa-solid fa-book"></i>';

    }


    if (!data || data.length === 0) {

        body.innerHTML = `

            <div class="column-empty-popup">

                <i class="fa-solid fa-folder-open"></i>

                <strong>

                    Tidak ada peminjaman
                    ${
                        jenis === "literasi"
                            ? "literasi"
                            : "paket"
                    }.

                </strong>

            </div>

        `;

    } else {

        body.innerHTML = `

            <div class="borrowing-popup-summary">

                <strong>

                    Total transaksi
                    ${
                        jenis === "literasi"
                            ? "literasi"
                            : "paket"
                    }

                </strong>

                <span>
                    ${data.length}
                </span>

            </div>


            ${data.map((item, index) =>
                buatItemPeminjaman(
                    item,
                    index + 1
                )
            ).join("")}

        `;

    }


    popup.classList.add("show");

    document.body.style.overflow = "hidden";

}


function tutupBorrowingPopup() {

    const popup =
        document.getElementById(
            "borrowingPopup"
        );


    if (popup) {

        popup.classList.remove("show");

    }


    document.body.style.overflow = "";

}


/* =========================================================
   PROSES BARCODE
========================================================= */

function prosesBarcode(text) {

    scanResult.innerHTML = `

        <div class="loading-result">

            <div class="loading-spinner"></div>

            <h3>
                Memproses barcode...
            </h3>

            <p>
                Mengambil data siswa dan seluruh transaksi.
            </p>

        </div>

    `;


    fetch(
        "{{ route('petugas.scan.process') }}",
        {

            method: "POST",

            headers: {

                "Content-Type":
                    "application/json",

                "X-CSRF-TOKEN":
                    "{{ csrf_token() }}",

                "Accept":
                    "application/json"

            },

            body: JSON.stringify({
                barcode: text
            })

        }
    )

    .then(res => {

        if (!res.ok) {

            throw new Error(
                "Server error"
            );

        }

        return res.json();

    })

    .then(data => {

        if (!data.success) {

            statusScan.innerHTML =
                "❌ QR Code tidak ditemukan";

            statusScanResult.innerHTML =
                "QR Code tidak ditemukan";

            statusScanResult.classList.remove(
                "success"
            );


            scanResult.innerHTML = `

                <div class="scan-error">

                    <div class="scan-error-icon">

                        <i class="fa-solid fa-circle-xmark"></i>

                    </div>

                    <div>

                        <h3>
                            QR Code Tidak Ditemukan
                        </h3>

                        <p>
                            ${escapeHtml(data.message)}
                        </p>

                    </div>

                </div>

            `;

            return;

        }


        statusScan.innerHTML =
            "✅ Data anggota ditemukan";

        statusScanResult.innerHTML =
            "Data anggota ditemukan";

        statusScanResult.classList.add(
            "success"
        );


        const user =
            data.user || {};


        dataSiswa =
            user;


        const batasPaket =
            Number(
                user.batas_paket ?? 0
            );


        const borrowings =
            Array.isArray(
                data.borrowings
            )
                ? data.borrowings
                : [];


        /* =================================================
           DATA LITERASI
        ================================================== */

        dataLiterasi =
            borrowings.filter(item =>
                item.book &&
                item.book.jenis_buku ===
                "literasi"
            );


        /* =================================================
           DATA PAKET
        ================================================== */

        dataPaket =
            borrowings.filter(item =>
                item.book &&
                item.book.jenis_buku ===
                "paket"
            );


        const jumlahPaketAktif =
            Number(
                user.jumlah_paket ?? 0
            );


        const sisaPaket =
            Number(
                user.sisa_paket ??
                Math.max(
                    batasPaket -
                    jumlahPaketAktif,
                    0
                )
            );


        const semesterAktif =
            user.semester_aktif ??
            null;


        /* =====================================================
           REWARD
        ===================================================== */

        const rewardForm =
            document.getElementById(
                "rewardForm"
            );


        const rewardUserId =
            document.getElementById(
                "rewardUserId"
            );


        const currentPoint =
            document.getElementById(
                "currentPoint"
            );


        if (rewardForm) {

            rewardForm.action =
                "/petugas/users/" +
                Number(user.id) +
                "/reward";

        }


        if (rewardUserId) {

            rewardUserId.value =
                user.id;

        }


        if (currentPoint) {

            currentPoint.innerHTML =
                "⭐ " +
                Number(user.poin ?? 0);

        }


        /* =====================================================
           STATUS BATAS PAKET
        ===================================================== */

        let statusBatasPaketHTML = "";


        if (batasPaket <= 0) {

            statusBatasPaketHTML = `

                <div class="student-info-warning">

                    <i class="fa-solid fa-circle-info"></i>

                    <span>

                        Batas buku paket untuk kelas siswa
                        saat ini adalah 0 atau belum ditentukan.

                    </span>

                </div>

            `;

        } else if (
            jumlahPaketAktif >=
            batasPaket
        ) {

            statusBatasPaketHTML = `

                <div class="student-info-warning">

                    <i class="fa-solid fa-triangle-exclamation"></i>

                    <span>

                        Batas peminjaman buku paket telah tercapai.

                    </span>

                </div>

            `;

        } else {

            statusBatasPaketHTML = `

                <div class="student-info-success">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>

                        Sisa batas buku paket:

                        <strong>
                            ${sisaPaket}
                        </strong>

                        buku.

                    </span>

                </div>

            `;

        }


        /* =====================================================
           HEADER SISWA
        ===================================================== */

        let html = `

            <div class="student-profile">

                <div class="student-profile-header">

                    <div class="student-avatar">

                        <i class="fa-solid fa-user"></i>

                    </div>

                    <div>

                        <span>
                            DATA SISWA
                        </span>

                        <h3>

                            ${escapeHtml(
                                user.name ?? '-'
                            )}

                        </h3>

                    </div>

                </div>


                <div class="student-info-grid">

                    <div class="student-info">

                        <span>
                            NIS
                        </span>

                        <strong>

                            ${escapeHtml(
                                user.nis ?? '-'
                            )}

                        </strong>

                    </div>


                    <div class="student-info">

                        <span>
                            Kelas
                        </span>

                        <strong>

                            ${escapeHtml(
                                user.kelas ?? '-'
                            )}

                        </strong>

                    </div>


                    <div class="student-info">

                        <span>
                            Poin
                        </span>

                        <strong class="point-value">

                            ⭐

                            ${Number(
                                user.poin ?? 0
                            )}

                        </strong>

                    </div>


                    <div class="student-info">

                        <span>
                            Batas Buku Paket
                        </span>

                        <strong>

                            ${batasPaket} buku

                        </strong>

                    </div>


                    <div class="student-info">

                        <span>
                            Paket Sedang Dipinjam
                        </span>

                        <strong>

                            ${jumlahPaketAktif}
                            /
                            ${batasPaket}

                        </strong>

                    </div>


                    <div class="student-info">

                        <span>
                            Sisa Batas Paket
                        </span>

                        <strong>

                            ${sisaPaket} buku

                        </strong>

                    </div>


                    <div class="student-info">

                        <span>
                            Semester Aktif
                        </span>

                        <strong>

                            ${
                                semesterAktif
                                    ? "Semester " +
                                      semesterAktif
                                    : "-"
                            }

                        </strong>

                    </div>

                </div>


                ${statusBatasPaketHTML}


                <div class="student-actions">

                    <button
                        type="button"
                        onclick="bukaHistory()"
                        class="history-button"
                    >

                        <i class="fa-solid fa-clock-rotate-left"></i>

                        Lihat Riwayat

                    </button>


                    <button
                        type="button"
                        onclick="bukaReward()"
                        class="reward-button"
                    >

                        <i class="fa-solid fa-gift"></i>

                        Tukar Poin

                    </button>

                </div>

            </div>


            <div class="transaction-heading">

                <div class="transaction-icon">

                    <i class="fa-solid fa-book"></i>

                </div>

                <div>

                    <h3>
                        Verifikasi Peminjaman
                    </h3>

                    <p>

                        Pilih jenis buku untuk melihat transaksi
                        dan melakukan verifikasi.

                    </p>

                </div>

            </div>


            <div class="borrowing-type-selector">


                <button
                    type="button"
                    class="borrowing-type-card literasi"
                    onclick="bukaBorrowingPopup('literasi')"
                >

                    <div class="borrowing-type-card-top">

                        <div class="borrowing-type-card-icon">

                            <i class="fa-solid fa-book-open"></i>

                        </div>

                        <span class="borrowing-type-count">

                            ${dataLiterasi.length}

                        </span>

                    </div>


                    <h3>
                        Buku Literasi
                    </h3>


                    <p>

                        Lihat dan verifikasi seluruh transaksi
                        peminjaman buku literasi.

                    </p>


                    <span class="click-hint">

                        Lihat transaksi

                        <i class="fa-solid fa-arrow-right"></i>

                    </span>

                </button>


                <button
                    type="button"
                    class="borrowing-type-card paket"
                    onclick="bukaBorrowingPopup('paket')"
                >

                    <div class="borrowing-type-card-top">

                        <div class="borrowing-type-card-icon">

                            <i class="fa-solid fa-book"></i>

                        </div>

                        <span class="borrowing-type-count">

                            ${dataPaket.length}

                        </span>

                    </div>


                    <h3>
                        Buku Paket
                    </h3>


                    <p>

                        Lihat dan verifikasi transaksi buku paket
                        berdasarkan semester aktif.

                    </p>


                    <span class="click-hint">

                        Lihat transaksi

                        <i class="fa-solid fa-arrow-right"></i>

                    </span>

                </button>

            </div>

        `;


        scanResult.innerHTML =
            html;


        /* =====================================================
           RIWAYAT
        ===================================================== */

        const historyContent =
            document.getElementById(
                "historyContent"
            );


        if (historyContent) {

            historyContent.innerHTML =
                buatRiwayat(
                    data.history
                );

        }

    })

    .catch(error => {

        console.error(error);


        statusScan.innerHTML =
            "❌ Terjadi kesalahan";

        statusScanResult.innerHTML =
            "Gagal mengambil data";


        scanResult.innerHTML = `

            <div class="scan-error">

                <div class="scan-error-icon">

                    <i class="fa-solid fa-triangle-exclamation"></i>

                </div>

                <div>

                    <h3>
                        Terjadi Kesalahan
                    </h3>

                    <p>

                        Tidak dapat mengambil data siswa.
                        Silakan coba scan kembali.

                    </p>

                </div>

            </div>

        `;

    });

}


/* =========================================================
   RIWAYAT
========================================================= */

function buatRiwayat(history) {

    if (!history || history.length === 0) {

        return `

            <div class="modal-empty">

                <i class="fa-solid fa-book-open"></i>

                <h3>
                    Belum Ada Riwayat
                </h3>

                <p>
                    Siswa belum memiliki riwayat peminjaman.
                </p>

            </div>

        `;

    }


    let html = `

        <div class="history-list">

    `;


    history.forEach(item => {

        const book =
            item.book || {};


        let statusClass =
            "status-default";


        if (item.status === "dipinjam") {

            statusClass =
                "status-dipinjam";

        }


        if (item.status === "dikembalikan") {

            statusClass =
                "status-dikembalikan";

        }


        if (item.status === "ditolak") {

            statusClass =
                "status-ditolak";

        }


        if (
            item.status === "menunggu" ||
            item.status === "menunggu_pengembalian"
        ) {

            statusClass =
                "status-menunggu";

        }


        const jenisBuku =
            formatJenisBuku(
                book.jenis_buku
            );


        const semester =
            formatSemester(
                book.jenis_buku,
                book.semester
            );


        html += `

            <div class="history-item">

                <div class="history-book">

                    <div class="history-book-icon">

                        <i class="fa-solid fa-book"></i>

                    </div>

                    <div>

                        <strong>

                            ${escapeHtml(
                                book.judul ?? '-'
                            )}

                        </strong>

                        <span>

                            ${escapeHtml(
                                jenisBuku
                            )}

                            ${
                                book.jenis_buku ===
                                'paket'
                                    ? ' • ' +
                                      escapeHtml(
                                          semester
                                      )
                                    : ''
                            }

                        </span>

                        <span>

                            ${escapeHtml(
                                item.tanggal_pinjam ?? '-'
                            )}

                        </span>

                    </div>

                </div>


                <div
                    class="history-status ${statusClass}"
                >

                    ${escapeHtml(
                        formatStatus(
                            item.status
                        )
                    )}

                </div>


                <div class="history-date">

                    <span>
                        Dikembalikan
                    </span>

                    <strong>

                        ${escapeHtml(
                            item.tanggal_kembali ?? '-'
                        )}

                    </strong>

                </div>

            </div>

        `;

    });


    html += `

        </div>

    `;


    return html;

}


/* =========================================================
   SCAN LAGI
========================================================= */

function scanLagi() {

    tutupBorrowingPopup();

    tutupJournalModal();


    barcodeResult.innerHTML =
        "-";


    statusScan.innerHTML =
        "Menunggu Scan...";


    statusScanResult.innerHTML =
        "Menunggu Scan...";


    statusScanResult.classList.remove(
        "success"
    );


    scanResult.innerHTML = `

        <div class="empty-scan">

            <div class="empty-scan-icon">

                <i class="fa-solid fa-qrcode"></i>

            </div>

            <h3>
                Arahkan QR Code Siswa
            </h3>

            <p>
                Tempatkan QR Code di dalam area kamera.
            </p>

        </div>

    `;


    dataLiterasi = [];

    dataPaket = [];

    dataSiswa = null;

    dataAcademicSetting = null;


    const manualBarcode =
        document.getElementById(
            "manualBarcode"
        );


    if (manualBarcode) {

        manualBarcode.value = "";

    }


    if (scannerRunning) {

        scanner.stop()

            .catch(() => {})

            .finally(() => {

                scannerRunning = false;

                mulaiScan();

            });

    } else {

        mulaiScan();

    }

}


/* =========================================================
   ENTER INPUT
========================================================= */

const manualBarcodeInput =
    document.getElementById(
        "manualBarcode"
    );


if (manualBarcodeInput) {

    manualBarcodeInput.addEventListener(
        "keydown",
        function(e) {

            if (e.key === "Enter") {

                e.preventDefault();

                prosesBarcodeManual();

            }

        }
    );

}


/* =========================================================
   HISTORY MODAL
========================================================= */

function bukaHistory() {

    const modal =
        document.getElementById(
            "historyModal"
        );


    if (modal) {

        modal.classList.add("show");

    }

}


function tutupHistory() {

    const modal =
        document.getElementById(
            "historyModal"
        );


    if (modal) {

        modal.classList.remove("show");

    }

}


/* =========================================================
   REWARD MODAL
========================================================= */

function bukaReward() {

    const modal =
        document.getElementById(
            "rewardModal"
        );


    if (modal) {

        modal.classList.add("show");

    }

}


function tutupReward() {

    const modal =
        document.getElementById(
            "rewardModal"
        );


    if (modal) {

        modal.classList.remove("show");

    }

}


/* =========================================================
   CLOSE BORROWING POPUP
========================================================= */

const borrowingPopup =
    document.getElementById(
        "borrowingPopup"
    );


if (borrowingPopup) {

    borrowingPopup.addEventListener(
        "click",
        function(e) {

            if (e.target === this) {

                tutupBorrowingPopup();

            }

        }
    );

}


/* =========================================================
   CLOSE JOURNAL MODAL
========================================================= */

const journalModal =
    document.getElementById(
        "journalModal"
    );


if (journalModal) {

    journalModal.addEventListener(
        "click",
        function(e) {

            if (e.target === this) {

                tutupJournalModal();

            }

        }
    );

}


/* =========================================================
   CLOSE HISTORY
========================================================= */

const historyModal =
    document.getElementById(
        "historyModal"
    );


if (historyModal) {

    historyModal.addEventListener(
        "click",
        function(e) {

            if (e.target === this) {

                tutupHistory();

            }

        }
    );

}


/* =========================================================
   CLOSE REWARD
========================================================= */

const rewardModal =
    document.getElementById(
        "rewardModal"
    );


if (rewardModal) {

    rewardModal.addEventListener(
        "click",
        function(e) {

            if (e.target === this) {

                tutupReward();

            }

        }
    );

}


/* =========================================================
   ESCAPE
========================================================= */

document.addEventListener(
    "keydown",
    function(e) {

        if (e.key !== "Escape") {
            return;
        }


        tutupBorrowingPopup();

        tutupJournalModal();

        tutupHistory();

        tutupReward();

    }
);


/* =========================================================
   START
========================================================= */

mulaiScan();

</script>

@endsection