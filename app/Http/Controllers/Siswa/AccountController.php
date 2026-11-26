<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Notification;
use App\Models\Borrowing;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class AccountController extends Controller
{
    /**
     * Halaman akun siswa
     */
    public function index()
    {
        $user = Auth::user();

        // Jumlah buku yang sedang dipinjam
        $sedangDipinjam = Borrowing::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        // Jumlah seluruh transaksi peminjaman
        $jumlahDipinjam = Borrowing::where('user_id', $user->id)
            ->count();

        // Ranking siswa
        $ranking = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->where('poin', '>', $user->poin)
            ->count() + 1;

        // Riwayat poin
        $historiPoin = Notification::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('judul', 'Poin Bertambah')
                    ->orWhere('judul', 'Penukaran Reward');
            })
            ->latest()
            ->get();

        // Generate QR Code
        $qrCode = null;

        if (!empty($user->barcode)) {

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($user->barcode)
                ->size(300)
                ->margin(10)
                ->build();

            $qrCode = base64_encode($result->getString());
        }

        return view('siswa.account', compact(
            'user',
            'ranking',
            'historiPoin',
            'qrCode',
            'sedangDipinjam',
            'jumlahDipinjam'
        ));
    }

    /**
     * Update profil siswa
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'kelas' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,jfif|max:2048',
        ]);

        // Update foto profil
        if ($request->hasFile('foto')) {

            if (
                $user->foto &&
                Storage::disk('public')->exists($user->foto)
            ) {
                Storage::disk('public')->delete($user->foto);
            }

            $path = $request->file('foto')
                ->store('foto-profil', 'public');

            $user->foto = $path;
        }

        // Update data profil
        $user->name = $request->name;
        $user->email = $request->email;
        $user->kelas = $request->kelas;
        $user->no_telepon = $request->no_telepon;

        $user->save();

        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }
}