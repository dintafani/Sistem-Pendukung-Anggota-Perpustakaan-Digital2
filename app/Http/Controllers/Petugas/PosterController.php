<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Poster;
use App\Models\Book;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PosterController extends Controller
{
    /*
   daftar poster
     */
    public function createPromosi()
{
    $books = Book::orderBy('judul')->get();

    return view('petugas.posters.createpromosi', compact('books'));
}
    
    public function index()
    {
        $posters = Poster::with('book')
            ->orderBy('urutan')
            ->orderByDesc('id')
            ->get();

        return view(
            'petugas.posters.index',
            compact('posters')
        );
    }


    /*
    FORM TAMBAH POSTER
     */

   public function create()
{
    $books = Book::orderBy('judul')->get();

    return view(
        'petugas.posters.create',
        compact('books')
    );
}

    /*
SIMPAN POSTER
     */

    public function store(Request $request)

    {
        // KHUSUS SIMPAN PROMOSI
if ($request->input('jenis') === 'promosi') {

    $request->validate([
        'book_id' => ['required', 'exists:books,id'],
    ], [
        'book_id.required' => 'Silakan pilih buku terlebih dahulu.',
        'book_id.exists' => 'Buku yang dipilih tidak ditemukan.',
    ]);

    $book = Book::findOrFail($request->book_id);

    if (!$book->cover || !Storage::disk('public')->exists($book->cover)) {
        return back()
            ->withInput()
            ->withErrors([
                'book_id' => 'Buku yang dipilih belum memiliki cover.'
            ]);
    }

    // Salin cover buku agar file cover asli tidak ikut terhapus
    // ketika promosi dihapus.
    $extension = pathinfo($book->cover, PATHINFO_EXTENSION);
    $gambarPath = 'posters/promosi-buku-' . $book->id . '-' . time() . '.' . $extension;

    Storage::disk('public')->copy($book->cover, $gambarPath);

    Poster::create([
        'book_id' => $book->id,
        'judul' => $book->judul,
        'deskripsi' => null,
        'gambar' => $gambarPath,
        'status' => true,
        'urutan' => 0,
    ]);

    return redirect()
        ->route('petugas.posters.index')
        ->with('success', 'Promosi buku berhasil ditambahkan.');
}
        $request->validate([
            'book_id' => [
    'nullable',
    'exists:books,id',
],
            'judul' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'gambar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'urutan' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ], [

            'book_id.required' =>
                'Buku wajib dipilih.',

            'book_id.exists' =>
                'Buku yang dipilih tidak ditemukan.',

            'judul.required' =>
                'Judul poster wajib diisi.',

            'gambar.required' =>
                'Gambar poster wajib diupload.',

            'gambar.image' =>
                'File harus berupa gambar.',

            'gambar.mimes' =>
                'Format poster harus JPG, JPEG, PNG, atau WEBP.',

            'gambar.max' =>
                'Ukuran poster maksimal 5 MB.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA BUKU
        |--------------------------------------------------------------------------
        */

       $book = $request->book_id
    ? Book::find($request->book_id)
    : null;


        /*
        |--------------------------------------------------------------------------
        | UPLOAD GAMBAR
        |--------------------------------------------------------------------------
        */

        $gambarPath = $request
            ->file('gambar')
            ->store('posters', 'public');


        /*
        |--------------------------------------------------------------------------
        | SIMPAN POSTER
        |--------------------------------------------------------------------------
        */

        $poster = Poster::create([
            'book_id' =>
                $request->book_id,

            'judul' =>
                $request->judul,

            'deskripsi' =>
                $request->deskripsi,

            'gambar' =>
                $gambarPath,

            'status' =>
                $request->has('status'),

            'urutan' =>
                $request->urutan ?? 0,
        ]);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN HISTORI AKTIVITAS
        |--------------------------------------------------------------------------
        */

ActivityLog::create([
    'user_id' => Auth::id(),

    'aktivitas' => 'Tambah Poster',

    'keterangan' => $book
        ? 'Menambahkan poster "' .
            $poster->judul .
            '" untuk buku "' .
            $book->judul .
            '"'
        : 'Menambahkan poster informasi "' .
            $poster->judul .
            '" tanpa terkait buku',
]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('petugas.posters.index')
            ->with(
                'success',
                'Poster berhasil ditambahkan.'
            );
    }


    /*
 FORM EDIT
     */

    public function edit(Poster $poster)
    {
        $books = Book::orderBy('judul')->get();

        return view(
            'petugas.posters.edit',
            compact(
                'poster',
                'books'
            )
        );
    }


    /*
UPDATE POSTER
     */

  public function update(
    Request $request,
    Poster $poster
) {

    $request->validate([

        'book_id' => [
            'nullable',
            'exists:books,id',
        ],
            'judul' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'urutan' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ], [

            'book_id.required' =>
                'Buku wajib dipilih.',

            'book_id.exists' =>
                'Buku yang dipilih tidak ditemukan.',

            'judul.required' =>
                'Judul poster wajib diisi.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA LAMA UNTUK HISTORI
        |--------------------------------------------------------------------------
        */

        $judulLama = $poster->judul;

        $bookLama = $poster->book
            ? $poster->book->judul
            : 'Buku tidak diketahui';


        /*
        |--------------------------------------------------------------------------
        | AMBIL BUKU BARU
        |--------------------------------------------------------------------------
        */

       $bookBaru = $request->book_id
    ? Book::find($request->book_id)
    : null;

        /*
        |--------------------------------------------------------------------------
        | UPDATE GAMBAR
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('gambar')) {

            if (
                $poster->gambar &&
                Storage::disk('public')->exists(
                    $poster->gambar
                )
            ) {
                Storage::disk('public')->delete(
                    $poster->gambar
                );
            }


            $poster->gambar =
                $request
                    ->file('gambar')
                    ->store('posters', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $poster->book_id =
            $request->book_id;

        $poster->judul =
            $request->judul;

        $poster->deskripsi =
            $request->deskripsi;

        $poster->status =
            $request->has('status');

        $poster->urutan =
            $request->urutan ?? 0;


        $poster->save();


        /*
        |--------------------------------------------------------------------------
        | SIMPAN HISTORI AKTIVITAS
        |--------------------------------------------------------------------------
        */

       ActivityLog::create([
    'user_id' => Auth::id(),

    'aktivitas' => 'Edit Poster',

    'keterangan' => $bookBaru
        ? 'Mengubah poster "' .
            $judulLama .
            '" menjadi "' .
            $poster->judul .
            '" untuk buku "' .
            $bookBaru->judul .
            '"'
        : 'Mengubah poster "' .
            $judulLama .
            '" menjadi poster informasi "' .
            $poster->judul .
            '" tanpa terkait buku',
]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('petugas.posters.index')
            ->with(
                'success',
                'Poster berhasil diperbarui.'
            );
    }


    /*
  APUS POSTER
     */

    public function destroy(Poster $poster)
    {
        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA SEBELUM DIHAPUS
        |--------------------------------------------------------------------------
        */

        $judulPoster = $poster->judul;

        $namaBuku = $poster->book
            ? $poster->book->judul
            : 'Buku tidak diketahui';


        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE GAMBAR
        |--------------------------------------------------------------------------
        */

        if (
            $poster->gambar &&
            Storage::disk('public')->exists(
                $poster->gambar
            )
        ) {
            Storage::disk('public')->delete(
                $poster->gambar
            );
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS DATABASE
        |--------------------------------------------------------------------------
        */

        $poster->delete();


        /*
        |--------------------------------------------------------------------------
        | SIMPAN HISTORI AKTIVITAS
        |--------------------------------------------------------------------------
        */

        ActivityLog::create([
            'user_id' =>
                Auth::id(),

            'aktivitas' =>
                'Hapus Poster',

            'keterangan' =>
                'Menghapus poster "' .
                $judulPoster .
                '" dari buku "' .
                $namaBuku .
                '"',
        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('petugas.posters.index')
            ->with(
                'success',
                'Poster berhasil dihapus.'
            );
    }
}
