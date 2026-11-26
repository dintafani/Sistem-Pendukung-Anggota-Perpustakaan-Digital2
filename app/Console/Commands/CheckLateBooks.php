<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use App\Models\Notification;
use App\Services\WhatsAppService;

class CheckLateBooks extends Command
{
    /**
     * Nama dan signature command.
     */
    protected $signature = 'books:check-late';

    /**
     * Deskripsi command.
     */
    protected $description = 'Mengecek buku terlambat dan mengirim notifikasi WhatsApp';

    /**
     * Jalankan command.
     */
    public function handle()
    {
        $borrowings = Borrowing::with(['book', 'user'])
            ->where('status', 'dipinjam')
            ->get();

        $hariIni = now()->startOfDay();

        foreach ($borrowings as $borrowing) {

            // Lewati jika belum terlambat
            if (!$borrowing->is_late) {
                continue;
            }

            $user = $borrowing->user;
            $book = $borrowing->book;

            // Pastikan user dan buku masih ada
            if (!$user || !$book) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Cari notifikasi terlambat terakhir untuk buku ini
            |--------------------------------------------------------------------------
            */

            $notifikasiTerakhir = Notification::where(
                'user_id',
                $user->id
            )
                ->where('judul', 'Buku Terlambat')
                ->where(
                    'pesan',
                    'like',
                    '%' . $book->judul . '%'
                )
                ->latest()
                ->first();

            $kirim = false;

            /*
            |--------------------------------------------------------------------------
            | 1. PERTAMA KALI TERLAMBAT
            |--------------------------------------------------------------------------
            */

            if (!$notifikasiTerakhir) {

                $kirim = true;
            }

            /*
            |--------------------------------------------------------------------------
            | 2. SETIAP HARI SENIN
            |--------------------------------------------------------------------------
            */

            elseif ($hariIni->isMonday()) {

                $tanggalNotif = $notifikasiTerakhir
                    ->created_at
                    ->startOfDay();

                // Jangan kirim dua kali pada Senin yang sama
                if (!$tanggalNotif->equalTo($hariIni)) {
                    $kirim = true;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | KIRIM NOTIFIKASI
            |--------------------------------------------------------------------------
            */

            if (!$kirim) {
                continue;
            }

            $pesanDatabase =
                'Buku "' .
                $book->judul .
                '" telah melewati batas pengembalian (' .
                $borrowing->tanggal_jatuh_tempo->format('d-m-Y') .
                '). Segera kembalikan ke perpustakaan.';

            /*
            |--------------------------------------------------------------------------
            | Simpan notifikasi ke database
            |--------------------------------------------------------------------------
            */

            Notification::create([
                'user_id' => $user->id,
                'judul'   => 'Buku Terlambat',
                'pesan'   => $pesanDatabase,
                'jenis'   => 'danger',
                'dibaca'  => false,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Kirim WhatsApp
            |--------------------------------------------------------------------------
            */

            if (!empty($user->no_telepon)) {

                try {

                    $wa = new WhatsAppService();

                    $wa->send(
                        $user->no_telepon,

                        "📚 *Notifikasi Perpustakaan Digital*\n\n" .
                        "⚠️ *Buku Terlambat*\n\n" .
                        "Halo, {$user->name}.\n\n" .
                        'Buku "' . $book->judul . '"' .
                        " telah melewati batas pengembalian.\n\n" .
                        "Tanggal jatuh tempo: " .
                        $borrowing->tanggal_jatuh_tempo->format('d-m-Y') .
                        "\n\n" .
                        "Buku tersebut masih tercatat sebagai terlambat.\n\n" .
                        "Segera kembalikan buku ke perpustakaan."
                    );

                    $this->info(
                        'WhatsApp terkirim ke ' .
                        $user->name .
                        ' - ' .
                        $book->judul
                    );

                } catch (\Throwable $e) {

                    \Log::error(
                        'Gagal mengirim WhatsApp buku terlambat: ' .
                        $e->getMessage()
                    );

                    $this->error(
                        'Gagal mengirim WhatsApp ke ' .
                        $user->name
                    );
                }
            }
        }

        $this->info('Pengecekan buku terlambat selesai.');

        return Command::SUCCESS;
    }
}