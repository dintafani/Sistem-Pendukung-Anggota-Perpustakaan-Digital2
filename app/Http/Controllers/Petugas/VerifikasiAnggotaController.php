<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class VerifikasiAnggotaController extends Controller
{
    /**
     * Halaman Verifikasi Anggota
     */
    public function index()
    {
        $anggotaPending = User::where('role', 'siswa')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $anggotaAktif = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view('petugas.anggota.index', compact(
            'anggotaPending',
            'anggotaAktif'
        ));
    }

    /**
     * Setujui Anggota
     */
    public function setujui($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status'  => 'aktif',
            'barcode' => 'QR' . time() . $user->id,
        ]);

        // Notifikasi ke siswa
        Notification::create([
            'user_id' => $user->id,
            'judul'   => 'Akun Disetujui',
            'pesan'   => 'Selamat! Akun Anda telah disetujui oleh petugas perpustakaan. Anda sekarang dapat login dan menggunakan sistem.',
            'jenis'   => 'success',
        ]);
  // Kirim WhatsApp
        if (!empty($user->no_telepon)) {
            try {
                $wa = new WhatsAppService();

                $wa->send(
                    $user->no_telepon,
                    "📚 *Perpustakaan Digital*\n\n" .
                    "✅ *Akun Telah Disetujui*\n\n" .
                    "Halo, {$user->name}.\n\n" .
                    "Akun perpustakaan kamu telah disetujui oleh petugas.\n\n" .
                    "Sekarang kamu sudah dapat login dan menggunakan layanan perpustakaan.\n\n" .
                    "Silakan login menggunakan email dan password yang telah kamu daftarkan.\n\n" .
                    "Terima kasih."
                );
            } catch (\Throwable $e) {
                Log::error(
                    'Gagal mengirim WhatsApp persetujuan akun: ' .
                    $e->getMessage()
                );
            }
        }

        // Histori aktivitas
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => 'Verifikasi Anggota',
            'keterangan' => 'Menyetujui anggota "' . $user->name . '"',
        ]);

        return back()->with(
            'success',
            'Anggota berhasil disetujui.'
        );
    }

    /**
     * Tolak Anggota
     */
    public function tolak($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'ditolak',
        ]);

        // Notifikasi ke siswa
        Notification::create([
            'user_id' => $user->id,
            'judul'   => 'Akun Ditolak',
            'pesan'   => 'Maaf, pendaftaran akun Anda ditolak oleh petugas perpustakaan. Silakan hubungi petugas untuk informasi lebih lanjut.',
            'jenis'   => 'danger',
        ]);
        
// Kirim WhatsApp
if (!empty($user->no_telepon)) {
    try {
        $wa = new WhatsAppService();

        $wa->send(
            $user->no_telepon,
            "📚 *Perpustakaan Digital*\n\n" .
            "❌ *Pendaftaran Akun Ditolak*\n\n" .
            "Halo, {$user->name}.\n\n" .
            "Mohon maaf, pendaftaran akun perpustakaan kamu ditolak oleh petugas.\n\n" .
            "Silakan hubungi petugas perpustakaan untuk mendapatkan informasi lebih lanjut mengenai pendaftaran akun kamu.\n\n" .
            "Terima kasih."
        );
    } catch (\Throwable $e) {
        Log::error(
            'Gagal mengirim WhatsApp penolakan akun: ' .
            $e->getMessage()
        );
    }
}
        // Histori aktivitas
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => 'Tolak Anggota',
            'keterangan' => 'Menolak anggota "' . $user->name . '"',
        ]);

        return back()->with(
            'success',
            'Anggota berhasil ditolak.'
        );
    }

    /**
     * Hapus Anggota
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $nama = $user->name;

        $user->delete();

        // Histori aktivitas
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'aktivitas'  => 'Hapus Anggota',
            'keterangan' => 'Menghapus anggota "' . $nama . '"',
        ]);

        return back()->with(
            'success',
            'Anggota berhasil dihapus.'
        );
    }
}