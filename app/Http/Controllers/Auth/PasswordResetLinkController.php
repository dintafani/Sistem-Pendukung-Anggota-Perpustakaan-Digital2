<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link through WhatsApp.
     */
    public function store(
        Request $request,
        WhatsAppService $whatsapp
    ): RedirectResponse {

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email tidak ditemukan.'
                ]);
        }

        // Pastikan nomor WhatsApp tersedia
        if (!$user->no_telepon) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Nomor WhatsApp akun belum terdaftar.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BUAT TOKEN RESET PASSWORD
        |--------------------------------------------------------------------------
        */

        $token = Password::createToken($user);

        /*
        |--------------------------------------------------------------------------
        | BUAT LINK RESET PASSWORD
        |--------------------------------------------------------------------------
        */

        $resetUrl = url(
            route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ], false)
        );

        /*
        |--------------------------------------------------------------------------
        | PESAN WHATSAPP
        |--------------------------------------------------------------------------
        */

        $message =
            "🔐 *Reset Kata Sandi Perpustakaan Digital*\n\n" .
            "Halo {$user->name},\n\n" .
            "Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.\n\n" .
            "Silakan klik link berikut untuk membuat kata sandi baru:\n\n" .
            $resetUrl . "\n\n" .
            "⚠️ Link ini hanya dapat digunakan untuk proses reset kata sandi.\n\n" .
            "Jika Anda tidak meminta reset kata sandi, abaikan pesan ini.\n\n" .
            "— Perpustakaan Digital";

        /*
        |--------------------------------------------------------------------------
        | KIRIM WHATSAPP
        |--------------------------------------------------------------------------
        */

        $sent = $whatsapp->send(
            $user->no_telepon,
            $message
        );

        if (!$sent) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Pesan WhatsApp gagal dikirim. Pastikan WhatsApp Bridge aktif.'
                ]);
        }

        return back()->with(
            'status',
            'Link reset kata sandi telah dikirim melalui WhatsApp.'
        );
    }
}