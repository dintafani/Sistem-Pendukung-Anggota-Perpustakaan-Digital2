<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EDIT ULASAN
    |--------------------------------------------------------------------------
    */

    public function edit(Review $review)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK PEMILIK ULASAN
        |--------------------------------------------------------------------------
        */

        if ($review->user_id !== Auth::id()) {
            abort(
                403,
                'Anda tidak memiliki akses ke ulasan ini.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD RELASI
        |--------------------------------------------------------------------------
        */

        $review->load([
            'book',
            'borrowing',
        ]);


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN FORM EDIT
        |--------------------------------------------------------------------------
        */

      return view(
    'siswa.history.edit-review',
    compact('review')
);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE ULASAN
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Review $review
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK PEMILIK ULASAN
        |--------------------------------------------------------------------------
        */

        if ($review->user_id !== Auth::id()) {
            abort(
                403,
                'Anda tidak memiliki akses ke ulasan ini.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'ulasan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'rating.required' =>
                'Rating wajib dipilih.',

            'rating.integer' =>
                'Rating harus berupa angka.',

            'rating.min' =>
                'Rating minimal 1.',

            'rating.max' =>
                'Rating maksimal 5.',

            'ulasan.max' =>
                'Ulasan maksimal 1000 karakter.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE ULASAN
        |--------------------------------------------------------------------------
        */

        $review->update([
            'rating' =>
                $validated['rating'],

            'ulasan' =>
                $validated['ulasan'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HISTORI
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('siswa.history')
            ->with(
                'success',
                'Rating dan ulasan berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS ULASAN
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Review $review
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK PEMILIK ULASAN
        |--------------------------------------------------------------------------
        */

        if ($review->user_id !== Auth::id()) {
            abort(
                403,
                'Anda tidak memiliki akses ke ulasan ini.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS ULASAN
        |--------------------------------------------------------------------------
        */

        $review->delete();


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE HISTORI
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('siswa.history')
            ->with(
                'success',
                'Rating dan ulasan berhasil dihapus.'
            );
    }
}