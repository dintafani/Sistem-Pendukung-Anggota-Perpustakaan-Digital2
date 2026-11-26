<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrowing;

class SyncBookCopies extends Command
{
    protected $signature = 'books:sync-copies';

    protected $description = 'Membuat eksemplar untuk buku lama dan menghubungkan peminjaman aktif';

    public function handle()
    {
        $this->info('Memulai sinkronisasi eksemplar buku...');

        $jumlahBuku = 0;
        $jumlahEksemplar = 0;
        $jumlahPeminjaman = 0;

        Book::chunkById(100, function ($books) use (
            &$jumlahBuku,
            &$jumlahEksemplar,
            &$jumlahPeminjaman
        ) {
            foreach ($books as $book) {
                DB::transaction(function () use (
                    $book,
                    &$jumlahBuku,
                    &$jumlahEksemplar,
                    &$jumlahPeminjaman
                ) {
                    $book = Book::whereKey($book->id)
                        ->lockForUpdate()
                        ->first();

                    $statusAktif = [
                        'dipinjam',
                        'menunggu_pengembalian',
                    ];

                    // Ambil peminjaman yang benar-benar masih memegang buku.
                    $borrowings = Borrowing::where('book_id', $book->id)
                        ->whereIn('status', $statusAktif)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    // Pastikan setiap peminjaman aktif memiliki eksemplar.
                    foreach ($borrowings as $borrowing) {
                        if ($borrowing->book_copy_id) {
                            $copy = BookCopy::where('id', $borrowing->book_copy_id)
                                ->lockForUpdate()
                                ->first();

                            if ($copy) {
                                if ($copy->status !== 'dipinjam') {
                                    $copy->update(['status' => 'dipinjam']);
                                }

                                continue;
                            }

                            $borrowing->book_copy_id = null;
                            $borrowing->save();
                        }

                        // Cari eksemplar tersedia.
                        $copy = BookCopy::where('book_id', $book->id)
                            ->where('status', 'tersedia')
                            ->orderBy('nomor_eksemplar')
                            ->lockForUpdate()
                            ->first();

                        // Jika belum ada, buat eksemplar baru.
                        if (!$copy) {
                            $nomor = (int) BookCopy::where(
                                'book_id',
                                $book->id
                            )->max('nomor_eksemplar') + 1;

                            $copy = BookCopy::create([
                                'book_id' => $book->id,
                                'nomor_eksemplar' => $nomor,
                                'kode_eksemplar' => 'BK-' . $book->id . '-' . $nomor,
                                'rak' => $book->rak,
                                'status' => 'tersedia',
                            ]);

                            $jumlahEksemplar++;
                        }

                        $copy->update(['status' => 'dipinjam']);

                        $borrowing->update([
                            'book_copy_id' => $copy->id,
                        ]);

                        $jumlahPeminjaman++;
                    }

                    // Hitung eksemplar yang masih tersedia.
                    $jumlahTersedia = BookCopy::where('book_id', $book->id)
                        ->where('status', 'tersedia')
                        ->count();

                    // Tambahkan eksemplar sampai sesuai dengan stok buku.
                    $kekurangan = max(
                        0,
                        (int) $book->stok - $jumlahTersedia
                    );

                    $nomorTerakhir = (int) BookCopy::where(
                        'book_id',
                        $book->id
                    )->max('nomor_eksemplar');

                    for ($i = 1; $i <= $kekurangan; $i++) {
                        $nomor = $nomorTerakhir + $i;

                        BookCopy::create([
                            'book_id' => $book->id,
                            'nomor_eksemplar' => $nomor,
                            'kode_eksemplar' => 'BK-' . $book->id . '-' . $nomor,
                            'rak' => $book->rak,
                            'status' => 'tersedia',
                        ]);

                        $jumlahEksemplar++;
                    }

                    $jumlahBuku++;
                });
            }
        });

        $this->newLine();
        $this->info('Sinkronisasi selesai.');
        $this->info("Buku diproses: {$jumlahBuku}");
        $this->info("Eksemplar baru dibuat: {$jumlahEksemplar}");
        $this->info("Peminjaman lama dihubungkan: {$jumlahPeminjaman}");

        return self::SUCCESS;
    }
}
