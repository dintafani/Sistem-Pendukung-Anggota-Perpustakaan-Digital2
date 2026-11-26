<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL ANGGOTA AKTIF
        |--------------------------------------------------------------------------
        */

        $totalAnggota = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | ANGGOTA YANG DIVERIFIKASI HARI INI
        |--------------------------------------------------------------------------
        */

        $anggotaHariIni = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->whereDate('updated_at', today())
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL BUKU
        |--------------------------------------------------------------------------
        */

        $totalBuku = Book::count();


        /*
        |--------------------------------------------------------------------------
        | BUKU YANG MASIH TERSEDIA
        |--------------------------------------------------------------------------
        */

        $bukuAktif = Book::where('stok', '>', 0)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | BUKU SEDANG DIPINJAM
        |--------------------------------------------------------------------------
        */

        $sedangDipinjam = Borrowing::where('status', 'dipinjam')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL MENUNGGU VERIFIKASI
        |--------------------------------------------------------------------------
        */

        $totalPending = User::where('role', 'siswa')
            ->where('status', 'pending')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | ANGGOTA PENDING TERBARU
        |--------------------------------------------------------------------------
        */

        $anggotaPending = User::where('role', 'siswa')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AKTIVITAS TERBARU
        |--------------------------------------------------------------------------
        */

        $activities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AKTIVITAS MINGGU INI
        |--------------------------------------------------------------------------
        */

        $aktivitasMingguan = [];

        for ($i = 6; $i >= 0; $i--) {

            $tanggal = Carbon::today()->subDays($i);

            /*
            |--------------------------------------------------------------
            | PEMINJAMAN
            |--------------------------------------------------------------
            */

            $peminjaman = Borrowing::whereDate(
                'tanggal_pinjam',
                $tanggal
            )->count();


            /*
            |--------------------------------------------------------------
            | PENGEMBALIAN
            |--------------------------------------------------------------
            */

            $pengembalian = Borrowing::whereDate(
                'tanggal_kembali',
                $tanggal
            )->count();


            /*
            |--------------------------------------------------------------
            | TOTAL AKTIVITAS
            |--------------------------------------------------------------
            */

            $jumlah = $peminjaman + $pengembalian;


            $aktivitasMingguan[] = [
                'tanggal' => $tanggal->format('Y-m-d'),

                'hari' => $tanggal
                    ->locale('id')
                    ->translatedFormat('D'),

                'peminjaman' => $peminjaman,

                'pengembalian' => $pengembalian,

                'jumlah' => $jumlah,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | NILAI MAKSIMAL GRAFIK AKTIVITAS
        |--------------------------------------------------------------------------
        */

        $maksAktivitas = collect($aktivitasMingguan)
            ->max('jumlah');

        if (!$maksAktivitas || $maksAktivitas < 1) {
            $maksAktivitas = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK ANGGOTA TERVERIFIKASI 7 HARI
        |--------------------------------------------------------------------------
        */

        $grafikAnggota = [];

        for ($i = 6; $i >= 0; $i--) {

            $tanggal = Carbon::today()->subDays($i);


            $jumlah = User::where('role', 'siswa')
                ->where('status', 'aktif')
                ->whereDate('updated_at', $tanggal)
                ->count();


            $grafikAnggota[] = [
                'tanggal' => $tanggal->format('Y-m-d'),

                'hari' => $tanggal
                    ->locale('id')
                    ->translatedFormat('D'),

                'jumlah' => $jumlah,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK BUKU 7 HARI
        |--------------------------------------------------------------------------
        */

        $grafikBuku = [];

        for ($i = 6; $i >= 0; $i--) {

            $tanggal = Carbon::today()->subDays($i);


            $jumlah = Book::whereDate(
                'created_at',
                $tanggal
            )->count();


            $grafikBuku[] = [
                'tanggal' => $tanggal->format('Y-m-d'),

                'hari' => $tanggal
                    ->locale('id')
                    ->translatedFormat('D'),

                'jumlah' => $jumlah,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK PEMINJAMAN 7 HARI
        |--------------------------------------------------------------------------
        */

        $grafikPeminjaman = [];

        for ($i = 6; $i >= 0; $i--) {

            $tanggal = Carbon::today()->subDays($i);


            $jumlah = Borrowing::whereDate(
                'tanggal_pinjam',
                $tanggal
            )->count();


            $grafikPeminjaman[] = [
                'tanggal' => $tanggal->format('Y-m-d'),

                'hari' => $tanggal
                    ->locale('id')
                    ->translatedFormat('D'),

                'jumlah' => $jumlah,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | GRAFIK PENDING 7 HARI
        |--------------------------------------------------------------------------
        */

        $grafikPending = [];

        for ($i = 6; $i >= 0; $i--) {

            $tanggal = Carbon::today()->subDays($i);


            $jumlah = User::where('role', 'siswa')
                ->where('status', 'pending')
                ->whereDate('created_at', $tanggal)
                ->count();


            $grafikPending[] = [
                'tanggal' => $tanggal->format('Y-m-d'),

                'hari' => $tanggal
                    ->locale('id')
                    ->translatedFormat('D'),

                'jumlah' => $jumlah,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | NILAI MAKSIMAL GRAFIK
        |--------------------------------------------------------------------------
        */

        $maksAnggota = max(
            collect($grafikAnggota)->max('jumlah') ?? 0,
            1
        );


        $maksBuku = max(
            collect($grafikBuku)->max('jumlah') ?? 0,
            1
        );


        $maksPeminjaman = max(
            collect($grafikPeminjaman)->max('jumlah') ?? 0,
            1
        );


        $maksPending = max(
            collect($grafikPending)->max('jumlah') ?? 0,
            1
        );


        /*
        |--------------------------------------------------------------------------
        | TOP 3 BUKU PALING BANYAK DIPINJAM
        |--------------------------------------------------------------------------
        */

        $topBooks = Borrowing::query()
            ->select('book_id')
            ->selectRaw('COUNT(*) as total_dipinjam')
            ->whereNotNull('book_id')
            ->groupBy('book_id')
            ->orderByDesc('total_dipinjam')
            ->with('book')
            ->take(3)
            ->get()
            ->map(function ($borrowing) {

                if (!$borrowing->book) {
                    return null;
                }

                return (object) [
                    'judul' => $borrowing->book->judul,

                    'penulis' => $borrowing->book->penulis,

                    'total_dipinjam' => $borrowing->total_dipinjam,
                ];
            })
            ->filter()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | KIRIM SEMUA DATA KE DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('petugas.dashboard', compact(

            /*
            |--------------------------------------------------------------
            | STATISTIK UTAMA
            |--------------------------------------------------------------
            */

            'totalAnggota',
            'anggotaHariIni',

            'totalBuku',
            'bukuAktif',

            'sedangDipinjam',

            'totalPending',


            /*
            |--------------------------------------------------------------
            | DATA TABEL
            |--------------------------------------------------------------
            */

            'anggotaPending',
            'activities',


            /*
            |--------------------------------------------------------------
            | GRAFIK KECIL
            |--------------------------------------------------------------
            */

            'grafikAnggota',
            'grafikBuku',
            'grafikPeminjaman',
            'grafikPending',


            /*
            |--------------------------------------------------------------
            | NILAI MAKSIMAL GRAFIK KECIL
            |--------------------------------------------------------------
            */

            'maksAnggota',
            'maksBuku',
            'maksPeminjaman',
            'maksPending',


            /*
            |--------------------------------------------------------------
            | GRAFIK AKTIVITAS MINGGUAN
            |--------------------------------------------------------------
            */

            'aktivitasMingguan',
            'maksAktivitas',


            /*
            |--------------------------------------------------------------
            | TOP BUKU
            |--------------------------------------------------------------
            */

            'topBooks'
        ));
    }
}