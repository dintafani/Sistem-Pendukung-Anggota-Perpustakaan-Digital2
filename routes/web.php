<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


use App\Models\Book;
use App\Models\User;
use App\Models\Borrowing;


use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController as SiswaDashboardController;

use App\Http\Controllers\Petugas\AccountController as PetugasAccountController;
use App\Http\Controllers\Petugas\DashboardController;
use App\Http\Controllers\Petugas\VerifikasiAnggotaController;
use App\Http\Controllers\Petugas\SearchController;
use App\Http\Controllers\Petugas\ArchiveController;
use App\Http\Controllers\Petugas\PosterController;
use App\Http\Controllers\Petugas\InventarisController;
use App\Http\Controllers\Petugas\AcademicSettingController;
use App\Http\Controllers\Petugas\NotificationController as PetugasNotificationController;

use App\Http\Controllers\Siswa\NotificationController as SiswaNotificationController;
use App\Http\Controllers\Siswa\AccountController;
use App\Http\Controllers\Siswa\LeaderboardController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReadingJournalController;


/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {

    $query = Book::query();

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('judul', 'like', '%' . $search . '%')
                ->orWhere('penulis', 'like', '%' . $search . '%')
                ->orWhere('kategori', 'like', '%' . $search . '%');

        });
    }

  /*
|--------------------------------------------------------------------------
| FILTER KATEGORI
|--------------------------------------------------------------------------
*/

if (
    $request->filled('kategori') &&
    $request->kategori !== 'Semua'
) {

    $kategori = trim($request->kategori);

    $query->where(function ($q) use ($kategori) {

        $q->where('kategori', $kategori)
            ->orWhere('kategori', 'like', $kategori . ',%')
            ->orWhere('kategori', 'like', '%, ' . $kategori . ',%')
            ->orWhere('kategori', 'like', '%, ' . $kategori);
    });
}
    /*
    |--------------------------------------------------------------------------
    | DATA BUKU
    |--------------------------------------------------------------------------
    */

    $books = $query
        ->withAvg('reviews', 'rating')
        ->latest()
        ->take(6)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    */

    $categories = Book::select('kategori')
        ->whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->distinct()
        ->pluck('kategori');

    /*
    |--------------------------------------------------------------------------
    | STATISTIK
    |--------------------------------------------------------------------------
    */

    $totalBuku = Book::count();

    $totalSiswa = User::where('role', 'siswa')
        ->where('status', 'aktif')
        ->count();

    $totalPeminjaman = Borrowing::count();

    return view(
        'welcome',
        compact(
            'books',
            'categories',
            'totalBuku',
            'totalSiswa',
            'totalPeminjaman'
        )
    );

})->name('welcome');

/*
|--------------------------------------------------------------------------
| SEMUA KOLEKSI BUKU WELCOME
|--------------------------------------------------------------------------
*/

Route::get('/koleksi-buku', function (Request $request) {

    $query = Book::query();


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where('judul', 'like', '%' . $search . '%')
                ->orWhere('penulis', 'like', '%' . $search . '%')
                ->orWhere('kategori', 'like', '%' . $search . '%');

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER KATEGORI
    |
    | Database:
    | Novel, Contoh
    |
    | Klik Novel  -> buku muncul
    | Klik Contoh -> buku muncul
    |--------------------------------------------------------------------------
    */

    if (
        $request->filled('kategori') &&
        $request->kategori !== 'Semua'
    ) {

        $kategori = trim($request->kategori);

        $query->whereRaw(
            'FIND_IN_SET(?, REPLACE(kategori, ", ", ","))',
            [$kategori]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SEMUA BUKU
    |--------------------------------------------------------------------------
    */

    $books = $query
        ->withAvg('reviews', 'rating')
        ->latest()
        ->get();


    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |
    | Database:
    |
    | Novel, Contoh
    | Novel, Fiksi
    | Komik
    |
    | Hasil:
    |
    | Novel
    | Contoh
    | Fiksi
    | Komik
    |--------------------------------------------------------------------------
    */

    $categories = Book::query()
        ->whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->pluck('kategori')
        ->flatMap(function ($kategori) {

            return explode(',', $kategori);

        })
        ->map(function ($kategori) {

            return trim($kategori);

        })
        ->filter(function ($kategori) {

            return $kategori !== '';

        })
        ->unique(function ($kategori) {

            return strtolower($kategori);

        })
        ->sort()
        ->values();


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN HALAMAN
    |--------------------------------------------------------------------------
    */

    return view(
        'koleksibukuwelcome',
        compact(
            'books',
            'categories'
        )
    );

})->name('welcome.koleksi');
/*
|--------------------------------------------------------------------------
| DETAIL BUKU
|--------------------------------------------------------------------------
*/

Route::get(
    '/book/{book}',
    function (Book $book) {

        $book->load([
            'reviews.user'
        ]);

        return view(
            'books.show',
            compact('book')
        );

    }
)->name('welcome.book.show');


/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/dashboard',
        function () {

            if (Auth::user()->role === 'petugas') {

                return redirect()->route(
                    'petugas.dashboard'
                );
            }

            if (Auth::user()->role === 'siswa') {

                return redirect()->route(
                    'siswa.dashboard'
                );
            }

            abort(403);
        }
    )->name('dashboard');

});

/*
|--------------------------------------------------------------------------
|Daftar Hadir
|--------------------------------------------------------------------------
*/

Route::get('/daftar-hadir', [AttendanceController::class, 'create'])
    ->name('attendance.create');

Route::post('/daftar-hadir', [AttendanceController::class, 'store'])
    ->name('attendance.store');

/*
|--------------------------------------------------------------------------
| SISWA
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'isSiswa'
])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [
                SiswaDashboardController::class,
                'index'
            ]
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | KOLEKSI BUKU
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/books',
            [
                SiswaDashboardController::class,
                'books'
            ]
        )->name('books.index');

        Route::get(
            '/books/{book}',
            [
                SiswaDashboardController::class,
                'show'
            ]
        )->name('books.show');


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/notifikasi',
            [
                SiswaNotificationController::class,
                'index'
            ]
        )->name('notifikasi');

        Route::delete(
            '/notifikasi/{id}',
            [
                SiswaNotificationController::class,
                'destroy'
            ]
        )->name('notifikasi.destroy');

        Route::delete(
            '/notifikasi',
            [
                SiswaNotificationController::class,
                'destroyAll'
            ]
        )->name('notifikasi.destroyAll');

        Route::patch(
            '/notifikasi/{id}/read',
            [
                SiswaNotificationController::class,
                'read'
            ]
        )->name('notifikasi.read');

        Route::patch(
            '/notifikasi/read-all',
            [
                SiswaNotificationController::class,
                'readAll'
            ]
        )->name('notifikasi.readAll');


       /*
|--------------------------------------------------------------------------
| PEMINJAMAN BUKU SISWA
|--------------------------------------------------------------------------
*/

Route::post(
    '/books/{book}/borrow',
    [
        BorrowingController::class,
        'store'
    ]
)->name('borrow.store');


/*
|--------------------------------------------------------------------------
| HISTORI PEMINJAMAN
|--------------------------------------------------------------------------
*/

Route::get(
    '/history',
    [
        BorrowingController::class,
        'history'
    ]
)->name('history');



/*
|--------------------------------------------------------------------------
| PENGEMBALIAN BUKU
|--------------------------------------------------------------------------
*/

Route::post(
    '/history/{borrowing}/return',
    [
        BorrowingController::class,
        'returnBook'
    ]
)->name('return.store');


/*
|--------------------------------------------------------------------------
| READING JOURNAL
|--------------------------------------------------------------------------
*/

Route::post(
    '/jurnal/{borrowing}',
    [
        ReadingJournalController::class,
        'store'
    ]
)->name('jurnal.store');

Route::put(
    '/jurnal/{readingJournal}',
    [
        ReadingJournalController::class,
        'update'
    ]
)->name('jurnal.update');

Route::post(
    '/jurnal/{readingJournal}/submit',
    [
        ReadingJournalController::class,
        'submit'
    ]
)->name('jurnal.submit');

Route::get(
    '/jurnal/{readingJournal}',
    [
        ReadingJournalController::class,
        'show'
    ]
)->name('jurnal.show');

/*
|--------------------------------------------------------------------------
| ULASAN BUKU SISWA
|--------------------------------------------------------------------------
*/

Route::get(
    '/review/{review}/edit',
    [
        ReviewController::class,
        'edit'
    ]
)->name('review.edit');

Route::put(
    '/review/{review}',
    [
        ReviewController::class,
        'update'
    ]
)->name('review.update');

Route::delete(
    '/review/{review}',
    [
        ReviewController::class,
        'destroy'
    ]
)->name('review.destroy');
        /*
        |--------------------------------------------------------------------------
        | LEADERBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/leaderboard',
            [
                LeaderboardController::class,
                'index'
            ]
        )->name('leaderboard');


        /*
        |--------------------------------------------------------------------------
        | AKUN SISWA
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/account',
            [
                AccountController::class,
                'index'
            ]
        )->name('account');

        Route::put(
            '/account',
            [
                AccountController::class,
                'update'
            ]
        )->name('account.update');

    });


/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'isPetugas'
])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {


    /*
|--------------------------------------------------------------------------
| DAFTAR HADIR
|--------------------------------------------------------------------------
*/

Route::get(
    '/daftar-hadir',
    [
        AttendanceController::class,
        'index'
    ]
)->name('attendance.index');


Route::get(
    '/daftar-hadir/export',
    [
        AttendanceController::class,
        'exportExcel'
    ]
)->name('attendance.export');

 /*
        |--------------------------------------------------------------------------
        | SEMUA KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        // Halaman semua data kunjungan
        Route::get(
            '/daftar-hadir/semua',
            [
                AttendanceController::class,
                'show'
            ]
        )->name('attendance.all');


        // Export semua data kunjungan
        Route::get(
            '/daftar-hadir/semua/export',
            [
                AttendanceController::class,
                'exportAll'
            ]
        )->name('attendance.exportAll');

        /*
        |--------------------------------------------------------------------------
        | ARSIP
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/archive',
            [
                ArchiveController::class,
                'index'
            ]
        )->name('archive');

        Route::get(
            '/archive/anggota',
            [
                ArchiveController::class,
                'anggota'
            ]
        )->name('archive.anggota');

        Route::get(
            '/archive/aktivitas',
            [
                ArchiveController::class,
                'aktivitas'
            ]
        )->name('archive.aktivitas');

        Route::get(
            '/archive/transaksi',
            [
                ArchiveController::class,
                'transaksi'
            ]
        )->name('archive.transaksi');

        Route::get(
            '/archive/transaksi/export',
            [
                ArchiveController::class,
                'exportTransaksi'
            ]
        )->name('archive.transaksi.export');

        Route::get(
            '/archive/aktivitas/export',
            [
                ArchiveController::class,
                'exportAktivitas'
            ]
        )->name('archive.aktivitas.export');

        Route::get(
            '/archive/anggota/export',
            [
                ArchiveController::class,
                'exportAnggota'
            ]
        )->name('archive.anggota.export');


        /*
        |--------------------------------------------------------------------------
        | ARSIP INVENTARIS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/archive/inventaris',
            [
                InventarisController::class,
                'index'
            ]
        )->name('archive.inventaris');

        Route::get(
            '/archive/inventaris/create',
            [
                InventarisController::class,
                'create'
            ]
        )->name('archive.inventaris.create');

        Route::post(
            '/archive/inventaris',
            [
                InventarisController::class,
                'store'
            ]
        )->name('archive.inventaris.store');

        Route::get(
            '/archive/inventaris/export',
            [
                InventarisController::class,
                'exportInventaris'
            ]
        )->name('archive.inventaris.export');

        Route::get(
            '/archive/inventaris/{inventaris}/edit',
            [
                InventarisController::class,
                'edit'
            ]
        )->name('archive.inventaris.edit');

        Route::put(
            '/archive/inventaris/{inventaris}',
            [
                InventarisController::class,
                'update'
            ]
        )->name('archive.inventaris.update');

        Route::delete(
            '/archive/inventaris/{inventaris}',
            [
                InventarisController::class,
                'destroy'
            ]
        )->name('archive.inventaris.destroy');


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [
                DashboardController::class,
                'index'
            ]
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | AKUN PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/account',
            [
                PetugasAccountController::class,
                'index'
            ]
        )->name('account');

        Route::post(
            '/account',
            [
                PetugasAccountController::class,
                'update'
            ]
        )->name('account.update');


       /*
|--------------------------------------------------------------------------
| POSTER
|--------------------------------------------------------------------------
*/

Route::get(
    '/posters',
    [
        PosterController::class,
        'index'
    ]
)->name('posters.index');

// TAMBAH PENGUMUMAN
Route::get(
    '/posters/create',
    [
        PosterController::class,
        'create'
    ]
)->name('posters.create');

// TAMBAH PROMOSI
Route::get(
    '/posters/createpromosi',
    [
        PosterController::class,
        'createPromosi'
    ]
)->name('posters.createpromosi');

Route::post(
    '/posters',
    [
        PosterController::class,
        'store'
    ]
)->name('posters.store');

Route::get(
    '/posters/{poster}/edit',
    [
        PosterController::class,
        'edit'
    ]
)->name('posters.edit');

Route::put(
    '/posters/{poster}',
    [
        PosterController::class,
        'update'
    ]
)->name('posters.update');

Route::patch(
    '/posters/{poster}/toggle',
    [
        PosterController::class,
        'toggle'
    ]
)->name('posters.toggle');

Route::delete(
    '/posters/{poster}',
    [
        PosterController::class,
        'destroy'
    ]
)->name('posters.destroy');

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/search',
            [
                SearchController::class,
                'index'
            ]
        )->name('search');


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/notifikasi',
            [
                PetugasNotificationController::class,
                'index'
            ]
        )->name('notifikasi');

        Route::patch(
            '/notifikasi/{id}/read',
            [
                PetugasNotificationController::class,
                'read'
            ]
        )->name('notifikasi.read');

        Route::patch(
            '/notifikasi/read-all',
            [
                PetugasNotificationController::class,
                'readAll'
            ]
        )->name('notifikasi.readAll');

        Route::delete(
            '/notifikasi/{id}',
            [
                PetugasNotificationController::class,
                'destroy'
            ]
        )->name('notifikasi.destroy');

        Route::delete(
            '/notifikasi',
            [
                PetugasNotificationController::class,
                'destroyAll'
            ]
        )->name('notifikasi.destroyAll');


        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI ANGGOTA
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/verifikasi-anggota',
            [
                VerifikasiAnggotaController::class,
                'index'
            ]
        )->name('verifikasi');

        Route::post(
            '/verifikasi-anggota/{id}/setujui',
            [
                VerifikasiAnggotaController::class,
                'setujui'
            ]
        )->name('verifikasi.setujui');

        Route::post(
            '/verifikasi-anggota/{id}/tolak',
            [
                VerifikasiAnggotaController::class,
                'tolak'
            ]
        )->name('verifikasi.tolak');

        Route::delete(
            '/archive/anggota/{user}',
            [
                ArchiveController::class,
                'destroyAnggota'
            ]
        )->name('archive.anggota.destroy');


        /*
        |--------------------------------------------------------------------------
        | BUKU PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/books',
            [
                BookController::class,
                'index'
            ]
        )->name('books.index');

        Route::get(
            '/books/create',
            [
                BookController::class,
                'create'
            ]
        )->name('books.create');

        Route::post(
            '/books',
            [
                BookController::class,
                'store'
            ]
        )->name('books.store');
        Route::get(
    '/books/export',
    [
        BookController::class,
        'exportExcel'
    ]
)->name('books.export');

        Route::get(
            '/books/{book}/edit',
            [
                BookController::class,
                'edit'
            ]
        )->name('books.edit');

        Route::put(
            '/books/{book}',
            [
                BookController::class,
                'update'
            ]
        )->name('books.update');

        Route::delete(
            '/books/{book}',
            [
                BookController::class,
                'destroy'
            ]
        )->name('books.destroy');


Route::put(
    '/books/{book}/copies/{copy}/rusak',
    [BookController::class, 'markCopyDamaged']
)->name('books.copies.damage');

        /*
        |--------------------------------------------------------------------------
        | IMPORT BUKU
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/books/import',
            [
                BookController::class,
                'importForm'
            ]
        )->name('books.import.form');

        Route::post(
            '/books/import',
            [
                BookController::class,
                'import'
            ]
        )->name('books.import');

        Route::get(
            '/books/import/template',
            [
                BookController::class,
                'downloadTemplate'
            ]
        )->name('books.template');


        /*
        |--------------------------------------------------------------------------
        | PENGATURAN AKADEMIK
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'academic-settings',
            AcademicSettingController::class
        );

        Route::patch(
            'academic-settings/{academicSetting}/activate',
            [
                AcademicSettingController::class,
                'activate'
            ]
        )->name('academic-settings.activate');
         /*
        |--------------------------------------------------------------------------
        | MODE UJIAN
        |--------------------------------------------------------------------------
        |
        | Tombol "Aktifkan Mode Ujian"
        |
        */

        Route::patch(
            '/academic-settings/{academic_setting}/aktifkan-ujian',
            [AcademicSettingController::class, 'aktifkanUjian']
        )->name('academic-settings.aktifkan-ujian');


        /*
        |--------------------------------------------------------------------------
        | NONAKTIFKAN MODE UJIAN
        |--------------------------------------------------------------------------
        |
        | Tombol "Nonaktifkan Mode Ujian"
        |
        */

        Route::patch(
            '/academic-settings/{academic_setting}/nonaktifkan-ujian',
            [AcademicSettingController::class, 'nonaktifkanUjian']
        )->name('academic-settings.nonaktifkan-ujian');

    


        /*
        |--------------------------------------------------------------------------
        | PEMINJAMAN PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/borrowings',
            [
                BorrowingController::class,
                'index'
            ]
        )->name('borrowings.index');

        Route::get(
            '/scan',
            [
                BorrowingController::class,
                'scan'
            ]
        )->name('scan');

        Route::post(
            '/scan/process',
            [
                BorrowingController::class,
                'scanProcess'
            ]
        )->name('scan.process');

        Route::post(
            '/borrowings/{borrowing}/approve',
            [
                BorrowingController::class,
                'approve'
            ]
        )->name('borrowings.approve');

        Route::post(
            '/borrowings/{borrowing}/reject',
            [
                BorrowingController::class,
                'reject'
            ]
        )->name('borrowings.reject');
        /*
|--------------------------------------------------------------------------
| JURNAL BACAAN PETUGAS
|--------------------------------------------------------------------------
*/

Route::get(
    '/jurnal/{readingJournal}',
    [
        ReadingJournalController::class,
        'showPetugas'
    ]
)->name('jurnal.show');


        /*
        |--------------------------------------------------------------------------
        | PENGEMBALIAN
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/borrowings/{borrowing}/approve-return',
            [
                BorrowingController::class,
                'approveReturn'
            ]
        )->name('borrowings.approveReturn');


Route::post(
    '/borrowings/{borrowing}/reject-return',
    [BorrowingController::class, 'rejectReturn']
)->name('borrowings.rejectReturn');

        /*
        |--------------------------------------------------------------------------
        | TUKAR HADIAH
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/users/{user}/reward',
            [
                BorrowingController::class,
                'redeemPoint'
            ]
        )->name('users.redeem');

    });


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [
            ProfileController::class,
            'edit'
        ]
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [
            ProfileController::class,
            'update'
        ]
    )->name('profile.update');

    Route::delete(
        '/profile',
        [
            ProfileController::class,
            'destroy'
        ]
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';