<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Poster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * =========================================================
     * DASHBOARD SISWA
     * =========================================================
     */
    public function index()
    {
        $user = auth()->user();

        $books = Book::latest()
            ->take(6)
            ->get();

        $totalBuku = Book::count();

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

        $sedangDipinjam = Borrowing::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        $borrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        $posters = Poster::with('book')
            ->aktif()
            ->get();

        $leaderboard = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->orderByDesc('poin')
            ->orderBy('name')
            ->take(3)
            ->get();

        $ranking = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->where('poin', '>', $user->poin)
            ->count() + 1;

        $badge = $user->badge;

        return view(
            'Siswa.dashboard',
            compact(
                'books',
                'totalBuku',
                'categories',
                'sedangDipinjam',
                'borrowings',
                'posters',
                'leaderboard',
                'ranking',
                'badge'
            )
        );
    }

    /**
     * =========================================================
     * KOLEKSI BUKU SISWA
     * =========================================================
     */
    public function books(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'judul',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'penulis',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'kategori',
                    'like',
                    '%' . $request->search . '%'
                );
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $kategori = trim($request->kategori);

            $query->whereRaw(
                "FIND_IN_SET(?, REPLACE(kategori, ', ', ','))",
                [$kategori]
            );
        }

        if ($request->filled('tersedia')) {
            $query->where('stok', '>', 0);
        }

        $books = $query
            ->withCount('borrowings')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $popularBooks = Book::query()
            ->withCount('borrowings')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('borrowings_count')
            ->take(10)
            ->get();

        $categories = Book::select('kategori')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view(
            'Siswa.books.index',
            compact(
                'books',
                'categories',
                'popularBooks'
            )
        );
    }

    /**
     * =========================================================
     * DETAIL BUKU
     * =========================================================
     */
    public function show(Book $book)
    {
        $book->load([
            'reviews.user'
        ]);

        return view(
            'Siswa.books.show',
            compact('book')
        );
    }

    /**
     * =========================================================
     * DASHBOARD ADMIN
     * =========================================================
     */
    public function admin()
    {
        return view('admin.dashboard');
    }
}