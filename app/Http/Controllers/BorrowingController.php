<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use App\Models\Review;
use App\Models\Notification;
use App\Models\AcademicSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\WhatsAppService;

class BorrowingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX PETUGAS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $awalBulan = Carbon::now()->startOfMonth();
        $akhirBulan = Carbon::now()->endOfMonth();

        $totalTransaksi = Borrowing::whereBetween(
            'tanggal_pinjam',
            [$awalBulan, $akhirBulan]
        )->count();

        $totalDipinjam = Borrowing::where(
            'status',
            'dipinjam'
        )->count();

        $totalKembali = Borrowing::where(
            'status',
            'dikembalikan'
        )
            ->whereBetween('tanggal_kembali', [$awalBulan, $akhirBulan])
            ->count();

        $transaksiTerbaru = Borrowing::with([
            'user',
            'book',
            'bookCopy',
        ])
            ->whereBetween('tanggal_pinjam', [$awalBulan, $akhirBulan])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $borrowings = Borrowing::with([
            'user',
            'book',
            'bookCopy',
            'review',
            'readingJournal',
        ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view(
            'petugas.borrowings.index',
            compact(
                'borrowings',
                'totalTransaksi',
                'totalDipinjam',
                'totalKembali',
                'transaksiTerbaru'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SISWA AJUKAN PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function store(Book $book)
    {
        $user = Auth::user();

        $statusAktif = [
            'menunggu',
            'dipinjam',
            'menunggu_pengembalian',
        ];

        if ((int) $book->stok <= 0) {
            return back()->with('error', 'Stok buku sedang habis.');
        }

        if (!in_array($book->jenis_buku, ['literasi', 'paket'], true)) {
            return back()->with('error', 'Jenis buku belum ditentukan atau tidak valid.');
        }

        $cekBukuSama = Borrowing::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', $statusAktif)
            ->exists();

        if ($cekBukuSama) {
            return back()->with(
                'error',
                'Buku ini masih dalam proses peminjaman atau pengembalian.'
            );
        }

        if ($book->jenis_buku === 'literasi') {
            $jumlahLiterasi = Borrowing::where('user_id', $user->id)
                ->whereIn('status', $statusAktif)
                ->whereHas('book', function ($query) {
                    $query->where('jenis_buku', 'literasi');
                })
                ->count();

            if ($jumlahLiterasi >= 3) {
                return back()->with('error', 'Maksimal meminjam 3 buku literasi.');
            }
        }

        if ($book->jenis_buku === 'paket') {
            if (!$book->semester) {
                return back()->with('error', 'Semester buku paket belum ditentukan.');
            }

            if (!in_array((int) $book->semester, [1, 2], true)) {
                return back()->with('error', 'Semester buku paket tidak valid.');
            }

            $semesterAktif = AcademicSetting::aktif();

            if (!$semesterAktif) {
                return back()->with('error', 'Belum ada semester aktif yang ditentukan.');
            }

            $modeUjian = (int) ($semesterAktif->mode_ujian ?? 0) === 1;

            if (!$modeUjian) {
                if ((int) $book->semester !== (int) $semesterAktif->semester) {
                    return back()->with(
                        'error',
                        'Buku paket ini bukan untuk semester yang sedang aktif.'
                    );
                }

                $kelasData = strtolower(trim((string) $user->kelas));
                preg_match('/^(xii|xi|x)\b/i', $kelasData, $matches);
                $kelas = $matches[1] ?? '';

                $batasPaket = match ($kelas) {
                    'x' => (int) ($semesterAktif->batas_paket_kelas_10 ?? 0),
                    'xi' => (int) ($semesterAktif->batas_paket_kelas_11 ?? 0),
                    'xii' => (int) ($semesterAktif->batas_paket_kelas_12 ?? 0),
                    default => 0,
                };

                if ($batasPaket <= 0) {
                    return back()->with(
                        'error',
                        'Batas peminjaman buku paket untuk kelas siswa ini belum ditentukan.'
                    );
                }

                $jumlahPaket = Borrowing::where('user_id', $user->id)
                    ->whereIn('status', $statusAktif)
                    ->whereHas('book', function ($query) use ($semesterAktif) {
                        $query->where('jenis_buku', 'paket')
                            ->where('semester', $semesterAktif->semester);
                    })
                    ->count();

                if ($jumlahPaket >= $batasPaket) {
                    return back()->with(
                        'error',
                        'Batas peminjaman buku paket untuk kelas ' .
                        strtoupper($kelas) . ' adalah ' . $batasPaket . ' buku.'
                    );
                }
            }
        }

        try {
            DB::transaction(function () use ($user, $book) {
                Borrowing::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'tanggal_pinjam' => now(),
                    'status' => 'menunggu',
                ]);

                Notification::create([
                    'user_id' => $user->id,
                    'judul' => 'Pengajuan Peminjaman',
                    'pesan' => 'Pengajuan peminjaman buku "' .
                        $book->judul .
                        '" menunggu persetujuan petugas.',
                    'jenis' => 'info',
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Pengajuan peminjaman gagal: ' . $e->getMessage());

            return back()->with(
                'error',
                'Pengajuan peminjaman gagal diproses: ' . $e->getMessage()
            );
        }

        return back()->with(
            'success',
            $book->jenis_buku === 'literasi'
                ? 'Pengajuan buku literasi berhasil dikirim.'
                : 'Pengajuan buku paket berhasil dikirim.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SISWA AJUKAN PENGEMBALIAN
    |--------------------------------------------------------------------------
    */

    public function returnBook(Request $request, Borrowing $borrowing)
    {
        if ((int) $borrowing->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $borrowing->load([
            'book',
            'review',
            'readingJournal',
            'user',
            'bookCopy',
        ]);

        if (!$borrowing->book) {
            return back()->with('error', 'Buku tidak ditemukan.');
        }

        if ($borrowing->status !== 'dipinjam') {
            return back()->with(
                'error',
                'Buku tidak dapat dikembalikan karena status peminjaman saat ini adalah: ' .
                $borrowing->status
            );
        }

        $jenisBuku = $borrowing->book->jenis_buku;

        if (!in_array($jenisBuku, ['literasi', 'paket'], true)) {
            return back()->with('error', 'Jenis buku tidak valid.');
        }

        $validated = $request->validate(
            [
                'rating' => ['required', 'integer', 'min:1', 'max:5'],
                'ulasan' => ['nullable', 'string', 'max:1000'],
            ],
            [
                'rating.required' => 'Rating buku wajib diisi sebelum mengembalikan buku.',
                'rating.integer' => 'Rating harus berupa angka.',
                'rating.min' => 'Rating minimal 1.',
                'rating.max' => 'Rating maksimal 5.',
                'ulasan.max' => 'Ulasan maksimal 1000 karakter.',
            ]
        );

        if ($jenisBuku === 'literasi' && !$borrowing->readingJournal) {
            return back()->with(
                'error',
                'Jurnal bacaan belum dibuat. Silakan isi jurnal terlebih dahulu.'
            );
        }

        try {
            DB::transaction(function () use ($borrowing, $validated) {
                $borrowingLocked = Borrowing::where('id', $borrowing->id)
                    ->lockForUpdate()
                    ->first();

                if (!$borrowingLocked || $borrowingLocked->status !== 'dipinjam') {
                    throw new \Exception('Status peminjaman sudah berubah.');
                }

                $reviewData = [
                    'borrowing_id' => $borrowingLocked->id,
                    'user_id' => Auth::id(),
                    'book_id' => $borrowingLocked->book_id,
                    'rating' => (int) $validated['rating'],
                    'ulasan' => $validated['ulasan'] ?? null,
                ];

                $review = Review::where('borrowing_id', $borrowingLocked->id)->first();

                if ($review) {
                    $review->update($reviewData);
                } else {
                    Review::create($reviewData);
                }

                $borrowingLocked->status = 'menunggu_pengembalian';
                $borrowingLocked->save();

                Notification::create([
                    'user_id' => $borrowingLocked->user_id,
                    'judul' => 'Pengajuan Pengembalian',
                    'pesan' => 'Pengembalian buku "' .
                        $borrowing->book->judul .
                        '" telah diajukan dan menunggu verifikasi petugas.',
                    'jenis' => 'info',
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Pengajuan pengembalian gagal: ' . $e->getMessage());

            return back()->with(
                'error',
                'Pengembalian gagal diproses: ' . $e->getMessage()
            );
        }

        if ($jenisBuku === 'literasi') {
            return back()->with(
                'success',
                'Rating berhasil disimpan. Jurnal sudah tersedia. Pengembalian buku literasi menunggu verifikasi petugas.'
            );
        }

        return back()->with(
            'success',
            'Rating berhasil disimpan. Pengembalian buku menunggu verifikasi petugas.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN SCAN / VERIFIKASI PETUGAS
    |--------------------------------------------------------------------------
    */

    public function scan()
    {
        $borrowings = Borrowing::with([
            'user',
            'book',
            'bookCopy',
            'review',
            'readingJournal',
        ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('petugas.scan', compact('borrowings'));
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES SCAN BARCODE
    |--------------------------------------------------------------------------
    */

    public function scanProcess(Request $request)
    {
        $request->validate([
            'barcode' => ['required', 'string'],
        ]);

        $user = User::where('barcode', $request->barcode)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak ditemukan.',
            ]);
        }

        $borrowings = Borrowing::with([
            'book',
            'bookCopy',
            'review',
            'readingJournal',
        ])
            ->where('user_id', $user->id)
            ->whereIn('status', [
                'menunggu',
                'dipinjam',
                'menunggu_pengembalian',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $data = [];

        foreach ($borrowings as $item) {
            $aksi = 'none';

            if ($item->status === 'menunggu') {
                $aksi = 'approve';
            }

            if ($item->status === 'menunggu_pengembalian') {
                $aksi = 'return';
            }

            $dueStatus = 'none';
            $daysLate = 0;
            $daysLeft = 0;

            if (
                $item->tanggal_jatuh_tempo &&
                in_array($item->status, ['dipinjam', 'menunggu_pengembalian'], true)
            ) {
                $today = Carbon::now()->startOfDay();
                $dueDate = Carbon::parse($item->tanggal_jatuh_tempo)->startOfDay();

                if ($today->greaterThan($dueDate)) {
                    $daysLate = $dueDate->diffInDays($today);
                    $dueStatus = 'late';
                } elseif ($today->equalTo($dueDate)) {
                    $dueStatus = 'today';
                } else {
                    $daysLeft = $today->diffInDays($dueDate);
                    $dueStatus = 'safe';
                }
            }

            $review = null;

            if ($item->review) {
                $review = [
                    'id' => $item->review->id,
                    'rating' => $item->review->rating,
                    'ulasan' => $item->review->ulasan,
                ];
            }

            $readingJournal = null;

            if ($item->readingJournal) {
                $readingJournal = [
                    'id' => $item->readingJournal->id,
                    'ringkasan' => $item->readingJournal->ringkasan,
                    'yang_dipelajari' => $item->readingJournal->yang_dipelajari,
                    'bagian_menarik' => $item->readingJournal->bagian_menarik,
                    'catatan_petugas' => $item->readingJournal->catatan_petugas,
                    'verified_by' => $item->readingJournal->verified_by,
                    'verified_at' => $item->readingJournal->verified_at
                        ? Carbon::parse($item->readingJournal->verified_at)->format('d-m-Y H:i')
                        : null,
                ];
            }

            $bookCopy = $item->bookCopy ? [
                'id' => $item->bookCopy->id,
                'nomor_eksemplar' => $item->bookCopy->nomor_eksemplar,
                'kode_eksemplar' => $item->bookCopy->kode_eksemplar,
                'rak' => $item->bookCopy->rak,
                'status' => $item->bookCopy->status,
            ] : null;

            $data[] = [
                'id' => $item->id,
                'status' => $item->status,
                'aksi' => $aksi,
                'tanggal_pinjam' => $item->tanggal_pinjam
                    ? Carbon::parse($item->tanggal_pinjam)->format('d-m-Y')
                    : null,
                'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo
                    ? Carbon::parse($item->tanggal_jatuh_tempo)->format('d-m-Y')
                    : null,
                'tanggal_kembali' => $item->tanggal_kembali
                    ? Carbon::parse($item->tanggal_kembali)->format('d-m-Y')
                    : null,
                'due_status' => $dueStatus,
                'days_late' => $daysLate,
                'days_left' => $daysLeft,
                'review' => $review,
                'reading_journal' => $readingJournal,
                'book' => [
                    'judul' => optional($item->book)->judul,
                    'jenis_buku' => optional($item->book)->jenis_buku,
                    'semester' => optional($item->book)->semester,
                    'rak' => optional($item->book)->rak,
                ],

                // Data eksemplar untuk tampilan Blade
                'book_copy' => $bookCopy,

                // Dipertahankan agar kode lama yang menggunakan eksemplar tetap berjalan
                'eksemplar' => $bookCopy ? [
                    'id' => $bookCopy['id'],
                    'nomor' => $bookCopy['nomor_eksemplar'],
                    'kode' => $bookCopy['kode_eksemplar'],
                    'rak' => $bookCopy['rak'],
                    'status' => $bookCopy['status'],
                ] : null,
            ];
        }

        $history = Borrowing::with([
            'book',
            'bookCopy',
            'review',
            'readingJournal',
        ])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $riwayat = [];

        foreach ($history as $item) {
            $bookCopy = $item->bookCopy ? [
                'id' => $item->bookCopy->id,
                'nomor_eksemplar' => $item->bookCopy->nomor_eksemplar,
                'kode_eksemplar' => $item->bookCopy->kode_eksemplar,
                'rak' => $item->bookCopy->rak,
                'status' => $item->bookCopy->status,
            ] : null;

            $riwayat[] = [
                'id' => $item->id,
                'book' => [
                    'judul' => optional($item->book)->judul,
                    'jenis_buku' => optional($item->book)->jenis_buku,
                    'semester' => optional($item->book)->semester,
                    'rak' => optional($item->book)->rak,
                ],
                'status' => $item->status,
                'tanggal_pinjam' => $item->tanggal_pinjam
                    ? Carbon::parse($item->tanggal_pinjam)->format('d-m-Y')
                    : null,
                'tanggal_jatuh_tempo' => $item->tanggal_jatuh_tempo
                    ? Carbon::parse($item->tanggal_jatuh_tempo)->format('d-m-Y')
                    : null,
                'tanggal_kembali' => $item->tanggal_kembali
                    ? Carbon::parse($item->tanggal_kembali)->format('d-m-Y')
                    : null,

                // Data eksemplar untuk tampilan riwayat
                'book_copy' => $bookCopy,

                // Dipertahankan untuk kompatibilitas kode lama
                'eksemplar' => $bookCopy ? [
                    'id' => $bookCopy['id'],
                    'nomor' => $bookCopy['nomor_eksemplar'],
                    'kode' => $bookCopy['kode_eksemplar'],
                    'rak' => $bookCopy['rak'],
                    'status' => $bookCopy['status'],
                ] : null,

                'review' => $item->review ? [
                    'rating' => $item->review->rating,
                    'ulasan' => $item->review->ulasan,
                ] : null,
                'reading_journal' => $item->readingJournal ? [
                    'ringkasan' => $item->readingJournal->ringkasan,
                    'yang_dipelajari' => $item->readingJournal->yang_dipelajari,
                    'bagian_menarik' => $item->readingJournal->bagian_menarik,
                    'catatan_petugas' => $item->readingJournal->catatan_petugas,
                    'verified_by' => $item->readingJournal->verified_by,
                    'verified_at' => $item->readingJournal->verified_at
                        ? Carbon::parse($item->readingJournal->verified_at)->format('d-m-Y H:i')
                        : null,
                ] : null,
            ];
        }

        $semesterAktif = AcademicSetting::aktif();

        $modeUjianAktif = $semesterAktif
            ? (int) ($semesterAktif->mode_ujian ?? 0) === 1
            : false;

        $kelasData = strtolower(trim((string) $user->kelas));
        preg_match('/^(xii|xi|x)\b/i', $kelasData, $matches);
        $kelas = $matches[1] ?? '';

        $batasPaketAktif = 0;

        if ($semesterAktif && !$modeUjianAktif) {
            $batasPaketAktif = match ($kelas) {
                'x' => (int) ($semesterAktif->batas_paket_kelas_10 ?? 0),
                'xi' => (int) ($semesterAktif->batas_paket_kelas_11 ?? 0),
                'xii' => (int) ($semesterAktif->batas_paket_kelas_12 ?? 0),
                default => 0,
            };
        }

        $jumlahPaketAktif = 0;

        if ($semesterAktif) {
            $jumlahPaketQuery = Borrowing::where('user_id', $user->id)
                ->whereIn('status', [
                    'menunggu',
                    'dipinjam',
                    'menunggu_pengembalian',
                ])
                ->whereHas('book', function ($query) use ($semesterAktif, $modeUjianAktif) {
                    $query->where('jenis_buku', 'paket');

                    if (!$modeUjianAktif) {
                        $query->where('semester', $semesterAktif->semester);
                    }
                });

            $jumlahPaketAktif = $jumlahPaketQuery->count();
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nis' => $user->nis,
                'kelas' => $user->kelas,
                'poin' => $user->poin,
                'semester_aktif' => $semesterAktif
                    ? (int) $semesterAktif->semester
                    : null,
                'mode_ujian' => $modeUjianAktif,
                'batas_paket' => $batasPaketAktif,
                'jumlah_paket' => $jumlahPaketAktif,
                'sisa_paket' => $modeUjianAktif
                    ? null
                    : max(0, $batasPaketAktif - $jumlahPaketAktif),
            ],
            'borrowings' => $data,
            'history' => $riwayat,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SETUJUI PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        $borrowing->load(['book', 'user']);

        if (!$borrowing->book) {
            return back()->with('error', 'Buku tidak ditemukan.');
        }

        if (!$borrowing->user) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $jenisBuku = $borrowing->book->jenis_buku;

        if (!in_array($jenisBuku, ['literasi', 'paket'], true)) {
            return back()->with('error', 'Jenis buku tidak valid.');
        }

        $semesterAktif = null;

        if ($jenisBuku === 'paket') {
            if (!$borrowing->book->semester) {
                return back()->with('error', 'Semester buku paket belum ditentukan.');
            }

            if (!in_array((int) $borrowing->book->semester, [1, 2], true)) {
                return back()->with('error', 'Semester buku paket tidak valid.');
            }

            $semesterAktif = AcademicSetting::aktif();

            if (!$semesterAktif) {
                return back()->with('error', 'Belum ada semester aktif yang ditentukan.');
            }

            $modeUjian = (int) ($semesterAktif->mode_ujian ?? 0) === 1;

            if (!$modeUjian) {
                if ((int) $borrowing->book->semester !== (int) $semesterAktif->semester) {
                    return back()->with(
                        'error',
                        'Semester buku paket tidak sesuai dengan semester aktif.'
                    );
                }

                $kelasData = strtolower(trim((string) $borrowing->user->kelas));
                preg_match('/^(xii|xi|x)\b/i', $kelasData, $matches);
                $kelas = $matches[1] ?? '';

                $batasPaket = match ($kelas) {
                    'x' => (int) ($semesterAktif->batas_paket_kelas_10 ?? 0),
                    'xi' => (int) ($semesterAktif->batas_paket_kelas_11 ?? 0),
                    'xii' => (int) ($semesterAktif->batas_paket_kelas_12 ?? 0),
                    default => 0,
                };

                if ($batasPaket <= 0) {
                    return back()->with(
                        'error',
                        'Batas peminjaman buku paket untuk kelas siswa ini belum ditentukan.'
                    );
                }

                $jumlahPaket = Borrowing::where('user_id', $borrowing->user_id)
                    ->whereIn('status', [
                        'menunggu',
                        'dipinjam',
                        'menunggu_pengembalian',
                    ])
                    ->where('id', '!=', $borrowing->id)
                    ->whereHas('book', function ($query) use ($semesterAktif) {
                        $query->where('jenis_buku', 'paket')
                            ->where('semester', $semesterAktif->semester);
                    })
                    ->count();

                if ($jumlahPaket >= $batasPaket) {
                    return back()->with(
                        'error',
                        'Batas peminjaman buku paket siswa untuk kelas ' .
                        strtoupper($kelas) . ' sudah tercapai. Maksimal ' .
                        $batasPaket . ' buku.'
                    );
                }
            }
        }

        if ($jenisBuku === 'literasi') {
            $jumlahLiterasi = Borrowing::where('user_id', $borrowing->user_id)
                ->whereIn('status', [
                    'menunggu',
                    'dipinjam',
                    'menunggu_pengembalian',
                ])
                ->where('id', '!=', $borrowing->id)
                ->whereHas('book', function ($query) {
                    $query->where('jenis_buku', 'literasi');
                })
                ->count();

            if ($jumlahLiterasi >= 3) {
                return back()->with(
                    'error',
                    'Maksimal 3 buku literasi siswa sudah tercapai.'
                );
            }
        }

        try {
            DB::transaction(function () use ($borrowing, $semesterAktif) {
                $borrowingLocked = Borrowing::where('id', $borrowing->id)
                    ->lockForUpdate()
                    ->first();

                if (!$borrowingLocked || $borrowingLocked->status !== 'menunggu') {
                    throw new \Exception('Peminjaman sudah diproses.');
                }

                $book = Book::where('id', $borrowingLocked->book_id)
                    ->lockForUpdate()
                    ->first();

                if (!$book) {
                    throw new \Exception('Buku tidak ditemukan.');
                }

                if ((int) $book->stok <= 0) {
                    throw new \Exception('Stok buku habis.');
                }

                $bookCopy = BookCopy::where('book_id', $book->id)
                    ->where('status', 'tersedia')
                    ->orderBy('nomor_eksemplar')
                    ->lockForUpdate()
                    ->first();

                if (!$bookCopy) {
                    throw new \Exception(
                        'Tidak ada eksemplar fisik buku yang tersedia.'
                    );
                }

                $bookCopy->status = 'dipinjam';
                $bookCopy->save();

                $borrowingLocked->book_copy_id = $bookCopy->id;

                $book->decrement('stok');

                $tanggalPinjam = now();

                if ($book->jenis_buku === 'paket') {
                    if (!$semesterAktif) {
                        throw new \Exception('Semester aktif tidak ditemukan.');
                    }

                    if ($semesterAktif->tanggal_selesai) {
                        $tanggalJatuhTempo = Carbon::parse(
                            $semesterAktif->tanggal_selesai
                        )->endOfDay();
                    } else {
                        $tanggalJatuhTempo = Carbon::now()->addDays(30)->endOfDay();
                    }
                } else {
                    $tanggalJatuhTempo = Carbon::now()->addDays(7)->endOfDay();
                }

                $borrowingLocked->status = 'dipinjam';
                $borrowingLocked->tanggal_pinjam = $tanggalPinjam;
                $borrowingLocked->tanggal_jatuh_tempo = $tanggalJatuhTempo;
                $borrowingLocked->save();

                if ($book->jenis_buku === 'paket') {
                    $modeUjian = $semesterAktif &&
                        (int) ($semesterAktif->mode_ujian ?? 0) === 1;

                    if ($modeUjian) {
                        $pesan = 'Peminjaman buku paket "' .
                            $book->judul .
                            '" telah disetujui dalam Mode Ujian. Buku paket Semester 1 dan Semester 2 dapat dipinjam selama Mode Ujian aktif. Buku harus dikembalikan paling lambat tanggal ' .
                            $tanggalJatuhTempo->format('d-m-Y') . '.';
                    } else {
                        $pesan = 'Peminjaman buku "' .
                            $book->judul .
                            '" telah disetujui. Buku harus dikembalikan paling lambat tanggal ' .
                            $tanggalJatuhTempo->format('d-m-Y') .
                            ' sesuai akhir semester.';
                    }
                } else {
                    $pesan = 'Peminjaman buku "' .
                        $book->judul .
                        '" telah disetujui. Buku harus dikembalikan paling lambat tanggal ' .
                        $tanggalJatuhTempo->format('d-m-Y') . '.';
                }

                Notification::create([
                    'user_id' => $borrowingLocked->user_id,
                    'judul' => 'Peminjaman Disetujui',
                    'pesan' => $pesan,
                    'jenis' => 'success',
                ]);

                $user = $borrowing->user;

                if ($user && $user->no_telepon) {
                    try {
                        $wa = new WhatsAppService();

                        $wa->send(
                            $user->no_telepon,
                            "📚 *Perpustakaan Digital*\n\n" .
                            "✅ *Peminjaman Disetujui*\n\n" .
                            $pesan
                        );
                    } catch (\Throwable $e) {
                        Log::error(
                            'Gagal mengirim WhatsApp peminjaman disetujui: ' .
                            $e->getMessage()
                        );
                    }
                }

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Peminjaman',
                    'keterangan' => 'Menyetujui peminjaman buku "' .
                        $book->judul .
                        '" oleh ' .
                        $borrowing->user->name .
                        '. Nomor eksemplar: ' .
                        $bookCopy->nomor_eksemplar . '.',
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Peminjaman gagal disetujui: ' . $e->getMessage());

            return back()->with(
                'error',
                'Peminjaman gagal diproses: ' . $e->getMessage()
            );
        }

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    /*
    |--------------------------------------------------------------------------
    | TOLAK PEMINJAMAN
    |--------------------------------------------------------------------------
    */

    public function reject(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        $borrowing->load(['book', 'user']);

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowingLocked = Borrowing::where('id', $borrowing->id)
                    ->lockForUpdate()
                    ->first();

                if (!$borrowingLocked || $borrowingLocked->status !== 'menunggu') {
                    throw new \Exception('Peminjaman sudah diproses.');
                }

                $borrowingLocked->status = 'ditolak';
                $borrowingLocked->save();

                Notification::create([
                    'user_id' => $borrowing->user_id,
                    'judul' => 'Peminjaman Ditolak',
                    'pesan' => 'Peminjaman buku "' .
                        optional($borrowing->book)->judul .
                        '" ditolak petugas.',
                    'jenis' => 'danger',
                ]);

                $user = $borrowing->user;

                if ($user && $user->no_telepon) {
                    try {
                        $wa = new WhatsAppService();

                        $wa->send(
                            $user->no_telepon,
                            "📚 *Perpustakaan Digital*\n\n" .
                            "❌ *Peminjaman Ditolak*\n\n" .
                            "Halo, {$user->name}.\n\n" .
                            "Peminjaman buku *\"" .
                            $borrowing->book->judul .
                            "\"* ditolak oleh petugas perpustakaan.\n\n" .
                            "Silakan mengajukan peminjaman kembali jika diperlukan.\n\n" .
                            "Terima kasih."
                        );
                    } catch (\Throwable $e) {
                        Log::error(
                            'Gagal mengirim WhatsApp penolakan peminjaman: ' .
                            $e->getMessage()
                        );
                    }
                }

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Peminjaman',
                    'keterangan' => 'Menolak peminjaman buku "' .
                        optional($borrowing->book)->judul .
                        '" oleh ' .
                        optional($borrowing->user)->name .
                        '.',
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'Peminjaman gagal ditolak: ' . $e->getMessage()
            );
        }

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | SETUJUI PENGEMBALIAN
    |--------------------------------------------------------------------------
    */

    public function approveReturn(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'menunggu_pengembalian') {
            return back()->with(
                'error',
                'Tidak ada pengembalian yang menunggu verifikasi.'
            );
        }

        $borrowing->load([
            'book',
            'user',
            'review',
            'readingJournal',
            'bookCopy',
        ]);

        if (!$borrowing->book) {
            return back()->with('error', 'Buku tidak ditemukan.');
        }

        if (!$borrowing->user) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $jenisBuku = $borrowing->book->jenis_buku;

        if (!in_array($jenisBuku, ['literasi', 'paket'], true)) {
            return back()->with('error', 'Jenis buku tidak valid.');
        }

        if (!$borrowing->review) {
            return back()->with('error', 'Rating buku belum tersedia.');
        }

        if ($jenisBuku === 'literasi' && !$borrowing->readingJournal) {
            return back()->with('error', 'Jurnal literasi belum tersedia.');
        }

        try {
            DB::transaction(function () use ($borrowing, $jenisBuku) {
                $borrowingLocked = Borrowing::where('id', $borrowing->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$borrowingLocked ||
                    $borrowingLocked->status !== 'menunggu_pengembalian'
                ) {
                    throw new \Exception(
                        'Status pengembalian sudah berubah.'
                    );
                }

                $book = Book::where('id', $borrowingLocked->book_id)
                    ->lockForUpdate()
                    ->first();

                if (!$book) {
                    throw new \Exception('Buku tidak ditemukan.');
                }

                $bookCopy = BookCopy::where(
                    'id',
                    $borrowingLocked->book_copy_id
                )
                    ->lockForUpdate()
                    ->first();

                if (!$bookCopy) {
                    throw new \Exception(
                        'Data eksemplar buku tidak ditemukan. Pengembalian tidak dapat diproses.'
                    );
                }

                if ($bookCopy->status !== 'dipinjam') {
                    throw new \Exception(
                        'Status eksemplar tidak valid untuk dikembalikan.'
                    );
                }

                $bookCopy->status = 'tersedia';
                $bookCopy->save();

                $book->increment('stok');

                $borrowingLocked->status = 'dikembalikan';
                $borrowingLocked->tanggal_kembali = now();
                $borrowingLocked->save();

                if ($jenisBuku === 'literasi') {
                    $borrowing->user->increment('poin', 10);

                    Notification::create([
                        'user_id' => $borrowing->user_id,
                        'judul' => 'Poin Bertambah',
                        'pesan' => 'Pengembalian buku berhasil. Kamu mendapatkan 10 poin literasi.',
                        'jenis' => 'success',
                    ]);

                    ActivityLog::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'Pengembalian',
                        'keterangan' => 'Menyetujui pengembalian buku literasi "' .
                            $book->judul .
                            '" oleh ' .
                            $borrowing->user->name .
                            ' dan menambahkan 10 poin literasi. Nomor eksemplar: ' .
                            $bookCopy->nomor_eksemplar . '.',
                    ]);
                }

                if ($jenisBuku === 'paket') {
                    ActivityLog::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'Pengembalian',
                        'keterangan' => 'Menyetujui pengembalian buku paket "' .
                            $book->judul .
                            '" oleh ' .
                            $borrowing->user->name .
                            '. Buku paket tidak mendapatkan poin literasi. Nomor eksemplar: ' .
                            $bookCopy->nomor_eksemplar . '.',
                    ]);
                }

                Notification::create([
                    'user_id' => $borrowing->user_id,
                    'judul' => 'Pengembalian Berhasil',
                    'pesan' => 'Buku "' .
                        $book->judul .
                        '" telah dikembalikan dan diverifikasi oleh petugas.',
                    'jenis' => 'success',
                ]);

                $user = $borrowing->user;

                if ($user && $user->no_telepon) {
                    try {
                        $wa = new WhatsAppService();

                        if ($jenisBuku === 'literasi') {
                            $pesanWA =
                                "📚 *Perpustakaan Digital*\n\n" .
                                "✅ *Pengembalian Buku Berhasil*\n\n" .
                                'Buku "' .
                                $book->judul .
                                "\" telah dikembalikan dan diverifikasi oleh petugas.\n\n" .
                                "🎉 Kamu mendapatkan *10 poin literasi* karena telah mengembalikan buku literasi.\n\n" .
                                "⭐ Terus tingkatkan aktivitas literasimu!";
                        } else {
                            $pesanWA =
                                "📚 *Perpustakaan Digital*\n\n" .
                                "✅ *Pengembalian Buku Berhasil*\n\n" .
                                'Buku "' .
                                $book->judul .
                                "\" telah dikembalikan dan diverifikasi oleh petugas.\n\n" .
                                "📖 Terima kasih telah mengembalikan buku.";
                        }

                        $wa->send($user->no_telepon, $pesanWA);
                    } catch (\Throwable $e) {
                        Log::error(
                            'Gagal mengirim WhatsApp pengembalian buku: ' .
                            $e->getMessage()
                        );
                    }
                }
            });
        } catch (\Throwable $e) {
            Log::error('Pengembalian gagal disetujui: ' . $e->getMessage());

            return back()->with(
                'error',
                'Pengembalian gagal diproses: ' . $e->getMessage()
            );
        }

        if ($jenisBuku === 'literasi') {
            return back()->with(
                'success',
                'Pengembalian buku literasi berhasil dan 10 poin bertambah.'
            );
        }

        return back()->with('success', 'Pengembalian buku paket berhasil.');
    }

    /*
    |--------------------------------------------------------------------------
    | TOLAK PENGEMBALIAN
    |--------------------------------------------------------------------------
    */

    public function rejectReturn(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'menunggu_pengembalian') {
            return back()->with(
                'error',
                'Pengembalian sudah diproses atau tidak sedang menunggu verifikasi.'
            );
        }

        $borrowing->load(['book', 'user']);

        if (!$borrowing->book) {
            return back()->with('error', 'Buku tidak ditemukan.');
        }

        if (!$borrowing->user) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        try {
            DB::transaction(function () use ($borrowing) {
                $borrowingLocked = Borrowing::where('id', $borrowing->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$borrowingLocked ||
                    $borrowingLocked->status !== 'menunggu_pengembalian'
                ) {
                    throw new \Exception(
                        'Status pengembalian sudah berubah.'
                    );
                }

                $borrowingLocked->status = 'dipinjam';
                $borrowingLocked->tanggal_kembali = null;
                $borrowingLocked->save();

                Notification::create([
                    'user_id' => $borrowing->user_id,
                    'judul' => 'Pengembalian Ditolak',
                    'pesan' => 'Pengembalian buku "' .
                        optional($borrowing->book)->judul .
                        '" ditolak oleh petugas. Buku dianggap belum berhasil dikembalikan. Silakan bawa atau ganti dengan buku yang sesuai untuk melakukan pengembalian kembali.',
                    'jenis' => 'danger',
                ]);

                $user = $borrowing->user;

                if ($user && $user->no_telepon) {
                    try {
                        $wa = new WhatsAppService();

                        $wa->send(
                            $user->no_telepon,
                            "📚 *Perpustakaan Digital*\n\n" .
                            "❌ *Pengembalian Ditolak*\n\n" .
                            "Halo, {$user->name}.\n\n" .
                            'Pengembalian buku "' .
                            optional($borrowing->book)->judul .
                            "\" ditolak oleh petugas perpustakaan.\n\n" .
                            "Buku dianggap belum berhasil dikembalikan dan masih tercatat sebagai *dipinjam*.\n\n" .
                            "Silakan membawa atau mengganti buku yang sesuai untuk melakukan pengembalian kembali.\n\n" .
                            "Terima kasih."
                        );
                    } catch (\Throwable $e) {
                        Log::error(
                            'Gagal mengirim WhatsApp penolakan pengembalian: ' .
                            $e->getMessage()
                        );
                    }
                }

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Pengembalian',
                    'keterangan' => 'Menolak pengembalian buku "' .
                        optional($borrowing->book)->judul .
                        '" oleh ' .
                        optional($borrowing->user)->name .
                        '. Buku belum dikembalikan dan stok tidak ditambahkan.',
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'Pengembalian gagal ditolak: ' . $e->getMessage()
            );
        }

        return back()->with(
            'success',
            'Pengembalian ditolak. Siswa harus membawa atau mengganti buku sebelum melakukan pengembalian kembali.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TUKAR REWARD
    |--------------------------------------------------------------------------
    */

    public function redeemPoint(Request $request, User $user)
    {
        $validated = $request->validate([
            'poin' => ['required', 'integer', 'min:1'],
        ]);

        $jumlahPoin = (int) $validated['poin'];

        try {
            DB::transaction(function () use ($user, $jumlahPoin) {
                $userLocked = User::where('id', $user->id)
                    ->lockForUpdate()
                    ->first();

                if (!$userLocked) {
                    throw new \Exception('Data siswa tidak ditemukan.');
                }

                if ((int) $userLocked->poin < $jumlahPoin) {
                    throw new \Exception('Poin siswa tidak mencukupi.');
                }

                $userLocked->decrement('poin', $jumlahPoin);

                Notification::create([
                    'user_id' => $userLocked->id,
                    'judul' => 'Penukaran Reward',
                    'pesan' => 'Sebanyak ' .
                        $jumlahPoin .
                        ' poin telah ditukarkan menjadi hadiah oleh petugas.',
                    'jenis' => 'success',
                ]);

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Tukar Reward',
                    'keterangan' => 'Menukarkan ' .
                        $jumlahPoin .
                        ' poin milik ' .
                        $userLocked->name .
                        '.',
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with(
                'error',
                'Penukaran poin gagal: ' . $e->getMessage()
            );
        }

        return back()->with('success', 'Poin siswa berhasil dipotong.');
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORI SISWA
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $borrowings = Borrowing::with([
            'book',
            'bookCopy',
            'review',
            'readingJournal',
        ])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $historiPoin = Notification::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->where('judul', 'Poin Bertambah')
                    ->orWhere('judul', 'Penukaran Reward');
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view(
            'siswa.history.index',
            compact('borrowings', 'historiPoin')
        );
    }
}