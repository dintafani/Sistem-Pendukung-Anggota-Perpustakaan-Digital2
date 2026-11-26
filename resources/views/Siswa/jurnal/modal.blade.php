

@php

    /*
    |--------------------------------------------------------------------------
    | DATA UTAMA
    |--------------------------------------------------------------------------
    */

    $borrowing = $borrowing
        ?? $item
        ?? null;

    if (!$borrowing) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | BUKU
    |--------------------------------------------------------------------------
    */

    $book = $book
        ?? ($borrowing->book ?? null);


    /*
    |--------------------------------------------------------------------------
    | JURNAL
    |--------------------------------------------------------------------------
    */

    $journal = $journal
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | ID MODAL
    |--------------------------------------------------------------------------
    */

    $modalId = $modalId
        ?? 'journalModal-' . $borrowing->id;


    /*
    |--------------------------------------------------------------------------
    | MODE
    |--------------------------------------------------------------------------
    */

    $isEdit = $journal !== null;


    /*
    |--------------------------------------------------------------------------
    | URL STORE
    |--------------------------------------------------------------------------
    */

    $storeUrl = $journalStoreAction
        ?? route(
            'siswa.jurnal.store',
            $borrowing->id
        );


    /*
    |--------------------------------------------------------------------------
    | URL UPDATE
    |--------------------------------------------------------------------------
    */

    $updateUrl = $journalUpdateAction
        ?? (
            $journal
                ? route(
                    'siswa.jurnal.update',
                    $journal->id
                )
                : ''
        );


    /*
    |--------------------------------------------------------------------------
    | DATA FORM
    |--------------------------------------------------------------------------
    */

    $ringkasan = old(
        'ringkasan',
        $journal->ringkasan ?? ''
    );

    $yangDipelajari = old(
        'yang_dipelajari',
        $journal->yang_dipelajari ?? ''
    );

    $bagianMenarik = old(
        'bagian_menarik',
        $journal->bagian_menarik ?? ''
    );

@endphp


<div
    id="{{ $modalId }}"
    class="journal-modal"
    aria-hidden="true"
>


    {{-- =========================================================
        OVERLAY
    ========================================================== --}}

    <div
        class="journal-modal-overlay"
        onclick="closeStudentJournalModal('{{ $modalId }}')"
    ></div>


    {{-- =========================================================
        CONTAINER
    ========================================================== --}}

    <div
        class="journal-modal-container"
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $modalId }}-title"
    >


        {{-- =====================================================
            HEADER
        ====================================================== --}}

        <div class="student-journal-modal-header">

            <div class="student-journal-modal-heading">

                <span class="student-journal-modal-icon">
                    <i class="fas fa-book-open"></i>
                </span>

                <div>

                    <span class="student-journal-modal-label">
                        JURNAL LITERASI
                    </span>

                    <h2 id="{{ $modalId }}-title">
                        Jurnal Bacaan
                    </h2>

                    <p>
                        Ceritakan pengalamanmu setelah membaca buku.
                    </p>

                </div>

            </div>


            {{-- =================================================
                CLOSE
            ================================================== --}}

            <button
                type="button"
                class="student-journal-close"
                onclick="closeStudentJournalModal('{{ $modalId }}')"
                aria-label="Tutup modal"
            >

                <i class="fas fa-times"></i>

            </button>

        </div>


        {{-- =====================================================
            BOOK INFORMATION
        ====================================================== --}}

        <div class="student-journal-book">

            <div class="student-journal-book-cover">

                @if(
                    $book &&
                    !empty($book->cover)
                )

                    <img
                        src="{{ asset('storage/' . ltrim($book->cover, '/')) }}"
                        alt="{{ $book->judul ?? 'Cover buku' }}"
                        onerror="this.onerror=null;this.src='{{ asset('images/default-book.png') }}';"
                    >

                @else

                    <img
                        src="{{ asset('images/default-book.png') }}"
                        alt="Cover buku"
                    >

                @endif

            </div>


            <div class="student-journal-book-info">

                <span>
                    BUKU YANG DIBACA
                </span>

                <strong>
                    {{ $book->judul ?? 'Buku tidak ditemukan' }}
                </strong>

                <small>
                    {{ $book->penulis ?? '-' }}
                </small>

                <small class="student-journal-borrowing-id">
                    Peminjaman #{{ $borrowing->id }}
                </small>

            </div>

        </div>


        {{-- =====================================================
            INFO JURNAL
        ====================================================== --}}

        <div class="student-journal-info">

            <i class="fas fa-info-circle"></i>

            <span>
                Isi jurnal berdasarkan buku yang kamu baca.
                Jurnal dapat disimpan terlebih dahulu dan akan
                menjadi bagian dari proses pengembalian buku.
            </span>

        </div>


        {{-- =====================================================
            FORM JURNAL
        ====================================================== --}}

        <form
            id="studentJournalForm-{{ $borrowing->id }}-{{ $journal?->id ?? 'new' }}"
            class="student-journal-form"
            method="POST"
            action="{{ $isEdit ? $updateUrl : $storeUrl }}"
            data-store-url="{{ $storeUrl }}"
            data-update-url="{{ $updateUrl }}"
            autocomplete="off"
        >

            @csrf


            {{-- =================================================
                METHOD UPDATE
            ================================================== --}}

            @if($isEdit)

                @method('PUT')

            @endif


            {{-- =================================================
                BORROWING ID
            ================================================== --}}

            <input
                type="hidden"
                name="borrowing_id"
                value="{{ $borrowing->id }}"
            >


            {{-- =================================================
                BOOK ID
            ================================================== --}}

            <input
                type="hidden"
                name="book_id"
                value="{{ $borrowing->book_id }}"
            >


            {{-- =================================================
                ACTION
            ================================================== --}}

            <input
                type="hidden"
                name="action"
                value="draft"
                class="journal-action-input"
            >


            {{-- =================================================
                RINGKASAN
            ================================================== --}}

            <div class="student-journal-form-group">

                <label for="{{ $modalId }}-ringkasan">

                    <span>

                        <i class="fas fa-align-left"></i>

                        Ringkasan Buku

                    </span>

                    <small>
                        Wajib diisi
                    </small>

                </label>


                <textarea
                    id="{{ $modalId }}-ringkasan"
                    name="ringkasan"
                    rows="5"
                    maxlength="2000"
                    placeholder="Tuliskan ringkasan isi buku yang kamu baca..."
                    required
                >{{ $ringkasan }}</textarea>


                <div class="student-journal-help">

                    Ringkas isi buku dengan bahasamu sendiri.

                </div>

            </div>


            {{-- =================================================
                YANG DIPELAJARI
            ================================================== --}}

            <div class="student-journal-form-group">

                <label for="{{ $modalId }}-yang-dipelajari">

                    <span>

                        <i class="fas fa-lightbulb"></i>

                        Yang Saya Pelajari

                    </span>

                    <small>
                        Wajib diisi
                    </small>

                </label>


                <textarea
                    id="{{ $modalId }}-yang-dipelajari"
                    name="yang_dipelajari"
                    rows="4"
                    maxlength="2000"
                    placeholder="Apa pengetahuan atau pelajaran yang kamu dapatkan dari buku ini?"
                    required
                >{{ $yangDipelajari }}</textarea>


                <div class="student-journal-help">

                    Jelaskan hal baru yang kamu dapatkan
                    setelah membaca.

                </div>

            </div>


            {{-- =================================================
                BAGIAN MENARIK
            ================================================== --}}

            <div class="student-journal-form-group">

                <label for="{{ $modalId }}-bagian-menarik">

                    <span>

                        <i class="fas fa-star"></i>

                        Bagian yang Menarik

                    </span>

                    <small>
                        Wajib diisi
                    </small>

                </label>


                <textarea
                    id="{{ $modalId }}-bagian-menarik"
                    name="bagian_menarik"
                    rows="4"
                    maxlength="2000"
                    placeholder="Bagian apa yang paling menarik atau berkesan menurutmu?"
                    required
                >{{ $bagianMenarik }}</textarea>


                <div class="student-journal-help">

                    Kamu bisa menceritakan tokoh, kejadian,
                    informasi, atau bagian buku yang paling berkesan.

                </div>

            </div>


            {{-- =================================================
                ERROR VALIDASI
            ================================================== --}}

            @if($errors->any())

                <div class="student-journal-errors">

                    <div class="student-journal-errors-title">

                        <i class="fas fa-exclamation-triangle"></i>

                        Periksa kembali jurnal kamu.

                    </div>


                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =================================================
                FOOTER
            ================================================== --}}

            <div class="student-journal-modal-footer">


                {{-- =================================================
                    BATAL
                ================================================== --}}

                <button
                    type="button"
                    class="student-journal-btn-cancel"
                    onclick="closeStudentJournalModal('{{ $modalId }}')"
                >

                    <i class="fas fa-times"></i>

                    Batal

                </button>


                {{-- =================================================
                    SIMPAN
                ================================================== --}}

                <button
                    type="submit"
                    class="student-journal-btn-submit"
                >

                    <i class="fas fa-save"></i>

                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Jurnal' }}

                </button>

            </div>


            {{-- =================================================
                HASIL SIMPAN
            ================================================== --}}

            <div
                class="journal-submit-result"
                style="display:none;"
            ></div>

        </form>

    </div>

</div>


{{-- =========================================================
    STYLE MODAL
========================================================= --}}

<style>

.journal-modal-container {

    width: min(700px, 100%);

    max-height: calc(100vh - 40px);

    overflow-y: auto;

}


.student-journal-modal-header {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 15px;

    padding: 18px 20px;

    border-bottom: 1px solid #f1f5f9;

}


.student-journal-modal-heading {

    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 0;

}


.student-journal-modal-icon {

    width: 42px;

    height: 42px;

    flex: 0 0 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 11px;

    background: #fff7ed;

    color: #ea580c;

    font-size: 17px;

}


.student-journal-modal-label {

    display: block;

    margin-bottom: 2px;

    color: #ea580c;

    font-size: 8px;

    font-weight: 900;

    letter-spacing: .8px;

}


.student-journal-modal-heading h2 {

    margin: 0;

    color: #1e293b;

    font-size: 18px;

    font-weight: 900;

}


.student-journal-modal-heading p {

    margin: 3px 0 0;

    color: #64748b;

    font-size: 10px;

}


.student-journal-close {

    width: 34px;

    height: 34px;

    flex: 0 0 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    border: 0;

    border-radius: 8px;

    background: #f8fafc;

    color: #64748b;

    cursor: pointer;

    transition: .2s ease;

}


.student-journal-close:hover {

    background: #fee2e2;

    color: #dc2626;

}


/* =========================================================
   BOOK
========================================================= */

.student-journal-book {

    display: flex;

    align-items: center;

    gap: 12px;

    margin: 16px 20px;

    padding: 11px;

    border: 1px solid #fed7aa;

    border-radius: 11px;

    background: #fffaf5;

}


.student-journal-book-cover {

    width: 48px;

    height: 65px;

    flex: 0 0 48px;

    overflow: hidden;

    border-radius: 6px;

    background: #f1f5f9;

}


.student-journal-book-cover img {

    width: 100%;

    height: 100%;

    object-fit: cover;

}


.student-journal-book-info {

    min-width: 0;

}


.student-journal-book-info span {

    display: block;

    margin-bottom: 3px;

    color: #ea580c;

    font-size: 7px;

    font-weight: 900;

    letter-spacing: .6px;

}


.student-journal-book-info strong {

    display: block;

    overflow: hidden;

    color: #1e293b;

    font-size: 12px;

    font-weight: 800;

    text-overflow: ellipsis;

    white-space: nowrap;

}


.student-journal-book-info small {

    display: block;

    margin-top: 3px;

    color: #64748b;

    font-size: 9px;

}


.student-journal-borrowing-id {

    color: #94a3b8 !important;

    font-size: 8px !important;

}


/* =========================================================
   INFO
========================================================= */

.student-journal-info {

    display: flex;

    align-items: flex-start;

    gap: 8px;

    margin: 0 20px 16px;

    padding: 10px 11px;

    border: 1px solid #bfdbfe;

    border-radius: 8px;

    background: #eff6ff;

    color: #1d4ed8;

    font-size: 9px;

    line-height: 1.5;

}


.student-journal-info i {

    margin-top: 1px;

    font-size: 11px;

}


/* =========================================================
   FORM
========================================================= */

.student-journal-form {

    padding: 0 20px 20px;

}


.student-journal-form-group {

    margin-bottom: 15px;

}


.student-journal-form-group label {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    margin-bottom: 6px;

    color: #334155;

    font-size: 10px;

    font-weight: 800;

}


.student-journal-form-group label span {

    display: flex;

    align-items: center;

    gap: 5px;

}


.student-journal-form-group label i {

    color: #ea580c;

}


.student-journal-form-group label small {

    color: #94a3b8;

    font-size: 8px;

    font-weight: 600;

}


.student-journal-form-group textarea {

    width: 100%;

    min-height: 90px;

    box-sizing: border-box;

    resize: vertical;

    padding: 10px 11px;

    border: 1px solid #e2e8f0;

    border-radius: 8px;

    outline: none;

    background: #ffffff;

    color: #334155;

    font-family: inherit;

    font-size: 10px;

    line-height: 1.55;

    transition: .2s ease;

}


.student-journal-form-group textarea:focus {

    border-color: #f97316;

    box-shadow:

        0 0 0 3px

        rgba(249,115,22,.08);

}


.student-journal-form-group textarea::placeholder {

    color: #94a3b8;

}


.student-journal-help {

    margin-top: 4px;

    color: #94a3b8;

    font-size: 8px;

    line-height: 1.4;

}


/* =========================================================
   ERROR
========================================================= */

.student-journal-errors {

    margin-bottom: 15px;

    padding: 10px 11px;

    border: 1px solid #fecaca;

    border-radius: 8px;

    background: #fef2f2;

    color: #991b1b;

    font-size: 9px;

}


.student-journal-errors-title {

    display: flex;

    align-items: center;

    gap: 6px;

    margin-bottom: 5px;

    font-weight: 800;

}


.student-journal-errors ul {

    margin: 0;

    padding-left: 17px;

}


.student-journal-errors li {

    margin: 2px 0;

}


/* =========================================================
   FOOTER
========================================================= */

.student-journal-modal-footer {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 8px;

    padding-top: 15px;

    border-top: 1px solid #f1f5f9;

}


.student-journal-btn-cancel,
.student-journal-btn-submit {

    min-height: 36px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 0 14px;

    border-radius: 8px;

    font-family: inherit;

    font-size: 9px;

    font-weight: 800;

    cursor: pointer;

    transition: .2s ease;

}


.student-journal-btn-cancel {

    border: 1px solid #e2e8f0;

    background: #ffffff;

    color: #64748b;

}


.student-journal-btn-cancel:hover {

    background: #f8fafc;

    color: #334155;

}


.student-journal-btn-submit {

    border: 1px solid #ea580c;

    background: #ea580c;

    color: #ffffff;

}


.student-journal-btn-submit:hover {

    background: #c2410c;

    border-color: #c2410c;

    transform: translateY(-1px);

    box-shadow:

        0 5px 12px

        rgba(234,88,12,.18);

}


.student-journal-btn-submit:disabled {

    opacity: .6;

    cursor: wait;

    transform: none;

}


/* =========================================================
   RESULT
========================================================= */

.journal-submit-result {

    margin-top: 12px;

    padding: 10px 12px;

    border-radius: 8px;

    font-size: 10px;

}


.journal-submit-result.success {

    display: block !important;

    border: 1px solid #bbf7d0;

    background: #f0fdf4;

    color: #166534;

}


.journal-submit-result.error {

    display: block !important;

    border: 1px solid #fecdd3;

    background: #fff1f2;

    color: #be123c;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 600px) {

    .journal-modal {

        padding: 10px;

    }


    .journal-modal-container {

        max-height: calc(100vh - 20px);

        border-radius: 14px;

    }


    .student-journal-modal-header {

        padding: 15px;

    }


    .student-journal-book {

        margin: 13px 15px;

    }


    .student-journal-info {

        margin-left: 15px;

        margin-right: 15px;

    }


    .student-journal-form {

        padding: 0 15px 15px;

    }


    .student-journal-modal-heading h2 {

        font-size: 16px;

    }


    .student-journal-modal-heading p {

        font-size: 9px;

    }


    .student-journal-modal-footer {

        flex-direction: column-reverse;

    }


    .student-journal-btn-cancel,
    .student-journal-btn-submit {

        width: 100%;

    }

}

</style>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}

<script>

window.submitStudentJournal = async function(button) {

    const form =
        button.closest('.student-journal-form');


    if (!form) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    if (!form.checkValidity()) {

        form.reportValidity();

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    const url =
        form.getAttribute('action');


    if (!url) {

        alert(
            'URL jurnal tidak ditemukan.'
        );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | ACTION SELALU DRAFT
    |--------------------------------------------------------------------------
    */

    const actionInput =
        form.querySelector(
            'input[name="action"]'
        );


    if (actionInput) {

        actionInput.value =
            'draft';

    }


    /*
    |--------------------------------------------------------------------------
    | BUTTON
    |--------------------------------------------------------------------------
    */

    const buttons =
        form.querySelectorAll(
            'button'
        );


    buttons.forEach(function(btn) {

        btn.disabled = true;

    });


    /*
    |--------------------------------------------------------------------------
    | RESULT
    |--------------------------------------------------------------------------
    */

    const result =
        form.querySelector(
            '.journal-submit-result'
        );


    try {

        const formData =
            new FormData(form);


        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        */

        const response =
            await fetch(
                url,
                {
                    method: 'POST',

                    headers: {

                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                ?.getAttribute(
                                    'content'
                                ) || '',

                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'

                    },

                    body: formData

                }
            );


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        const contentType =
            response.headers.get(
                'content-type'
            ) || '';


        let data = null;


        if (
            contentType.includes(
                'application/json'
            )
        ) {

            data =
                await response.json();

        } else {

            const text =
                await response.text();


            throw new Error(
                text ||
                'Server tidak mengembalikan response JSON.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ERROR
        |--------------------------------------------------------------------------
        */

        if (
            !response.ok ||
            data.success !== true
        ) {

            throw new Error(
                data.message ||
                'Jurnal gagal disimpan.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        if (result) {

            result.className =
                'journal-submit-result success';

            result.style.display =
                'block';

            result.textContent =
                data.message ||
                'Jurnal berhasil disimpan.';

        }


        /*
        |--------------------------------------------------------------------------
        | RELOAD
        |--------------------------------------------------------------------------
        */

        setTimeout(
            function() {

                window.location.reload();

            },
            700
        );


    } catch (error) {

        console.error(
            'Jurnal:',
            error
        );


        /*
        |--------------------------------------------------------------------------
        | ERROR MESSAGE
        |--------------------------------------------------------------------------
        */

        if (result) {

            result.className =
                'journal-submit-result error';

            result.style.display =
                'block';

            result.textContent =
                error.message ||
                'Terjadi kesalahan saat menyimpan jurnal.';

        }


        /*
        |--------------------------------------------------------------------------
        | ENABLE BUTTON
        |--------------------------------------------------------------------------
        */

        buttons.forEach(function(btn) {

            btn.disabled = false;

        });

    }

};

</script>
