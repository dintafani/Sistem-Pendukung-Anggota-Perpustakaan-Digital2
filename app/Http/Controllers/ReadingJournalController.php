<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\ReadingJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingJournalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX SISWA
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $journals = ReadingJournal::where(
            'user_id',
            Auth::id()
        )
        ->with([
            'borrowing.book',
            'borrowing.review',
        ])
        ->latest()
        ->get();

        return view(
            'siswa.jurnal.index',
            compact('journals')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE SISWA
    |--------------------------------------------------------------------------
    */

    public function create($borrowingId)
    {
        $borrowing = Borrowing::where(
            'id',
            $borrowingId
        )
        ->where(
            'user_id',
            Auth::id()
        )
        ->with([
            'book',
            'review',
            'readingJournal',
        ])
        ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | CEK BUKU
        |--------------------------------------------------------------------------
        */

        if (!$borrowing->book) {
            return redirect()
                ->route('siswa.history')
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | JURNAL HANYA UNTUK LITERASI
        |--------------------------------------------------------------------------
        */

        if ($borrowing->book->jenis_buku !== 'literasi') {
            return redirect()
                ->route('siswa.history')
                ->with(
                    'error',
                    'Jurnal literasi hanya dapat diisi untuk buku literasi.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS PEMINJAMAN
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $borrowing->status,
            [
                'dipinjam',
                'menunggu_pengembalian',
            ],
            true
        )) {
            return redirect()
                ->route('siswa.history')
                ->with(
                    'error',
                    'Jurnal belum dapat diisi karena status peminjaman belum aktif.'
                );
        }


        return view(
            'siswa.jurnal.form',
            compact('borrowing')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE SISWA
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Borrowing $borrowing
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK PEMILIK
        |--------------------------------------------------------------------------
        */

        if (
            (int) $borrowing->user_id !==
            (int) Auth::id()
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $borrowing->load([
            'book',
            'readingJournal',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK BUKU
        |--------------------------------------------------------------------------
        */

        if (!$borrowing->book) {
            return back()
                ->with(
                    'error',
                    'Buku tidak ditemukan.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CEK JENIS BUKU
        |--------------------------------------------------------------------------
        */

        if ($borrowing->book->jenis_buku !== 'literasi') {
            return back()
                ->with(
                    'error',
                    'Jurnal hanya dapat dibuat untuk buku literasi.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $borrowing->status,
            [
                'dipinjam',
                'menunggu_pengembalian',
            ],
            true
        )) {
            return back()
                ->with(
                    'error',
                    'Jurnal tidak dapat disimpan karena status peminjaman tidak valid.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'ringkasan' => [
                    'required',
                    'string',
                    'max:5000',
                ],

                'yang_dipelajari' => [
                    'required',
                    'string',
                    'max:5000',
                ],

                'bagian_menarik' => [
                    'required',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'ringkasan.required' =>
                    'Ringkasan buku wajib diisi.',

                'ringkasan.max' =>
                    'Ringkasan maksimal 5000 karakter.',

                'yang_dipelajari.required' =>
                    'Bagian yang dipelajari wajib diisi.',

                'yang_dipelajari.max' =>
                    'Bagian yang dipelajari maksimal 5000 karakter.',

                'bagian_menarik.required' =>
                    'Bagian menarik wajib diisi.',

                'bagian_menarik.max' =>
                    'Bagian menarik maksimal 5000 karakter.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DATA JURNAL
        |--------------------------------------------------------------------------
        */

        $journalData = [
            'user_id' =>
                Auth::id(),

            'borrowing_id' =>
                $borrowing->id,

            'book_id' =>
                $borrowing->book_id,

            'ringkasan' =>
                $validated['ringkasan'],

            'yang_dipelajari' =>
                $validated['yang_dipelajari'],

            'bagian_menarik' =>
                $validated['bagian_menarik'],
        ];


        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE
        |--------------------------------------------------------------------------
        */

        if ($borrowing->readingJournal) {

            $borrowing->readingJournal->update(
                $journalData
            );

            $message =
                'Jurnal literasi berhasil diperbarui.';

        } else {

            ReadingJournal::create(
                $journalData
            );

            $message =
                'Jurnal literasi berhasil disimpan.';
        }


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HISTORI SISWA
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('siswa.history')
            ->with(
                'success',
                $message
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT SISWA
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $journal = ReadingJournal::where(
            'id',
            $id
        )
        ->where(
            'user_id',
            Auth::id()
        )
        ->with([
            'borrowing.book',
        ])
        ->firstOrFail();


        if (!$journal->borrowing) {
            return redirect()
                ->route('siswa.history')
                ->with(
                    'error',
                    'Data peminjaman jurnal tidak ditemukan.'
                );
        }


        return view(
            'siswa.jurnal.form',
            [
                'journal' =>
                    $journal,

                'borrowing' =>
                    $journal->borrowing,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SISWA
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ReadingJournal $readingJournal
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK PEMILIK
        |--------------------------------------------------------------------------
        */

        if (
            (int) $readingJournal->user_id !==
            (int) Auth::id()
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'ringkasan' => [
                    'required',
                    'string',
                    'max:5000',
                ],

                'yang_dipelajari' => [
                    'required',
                    'string',
                    'max:5000',
                ],

                'bagian_menarik' => [
                    'required',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'ringkasan.required' =>
                    'Ringkasan buku wajib diisi.',

                'ringkasan.max' =>
                    'Ringkasan maksimal 5000 karakter.',

                'yang_dipelajari.required' =>
                    'Bagian yang dipelajari wajib diisi.',

                'yang_dipelajari.max' =>
                    'Bagian yang dipelajari maksimal 5000 karakter.',

                'bagian_menarik.required' =>
                    'Bagian menarik wajib diisi.',

                'bagian_menarik.max' =>
                    'Bagian menarik maksimal 5000 karakter.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $readingJournal->update([
            'ringkasan' =>
                $validated['ringkasan'],

            'yang_dipelajari' =>
                $validated['yang_dipelajari'],

            'bagian_menarik' =>
                $validated['bagian_menarik'],
        ]);


        return redirect()
            ->route('siswa.history')
            ->with(
                'success',
                'Jurnal literasi berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SISWA
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $journal = ReadingJournal::where(
            'id',
            $id
        )
        ->where(
            'user_id',
            Auth::id()
        )
        ->with([
            'borrowing.book',
            'borrowing.review',
        ])
        ->firstOrFail();


        return view(
            'siswa.jurnal.show',
            compact('journal')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY SISWA
    |--------------------------------------------------------------------------
    */

    public function destroy(
        ReadingJournal $readingJournal
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK PEMILIK
        |--------------------------------------------------------------------------
        */

        if (
            (int) $readingJournal->user_id !==
            (int) Auth::id()
        ) {
            abort(403);
        }


        $readingJournal->delete();


        return redirect()
            ->route('siswa.history')
            ->with(
                'success',
                'Jurnal literasi berhasil dihapus.'
            );
    }
}