<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validasi email & password
        $request->authenticate();

        // Regenerasi session
        $request->session()->regenerate();

        // User yang login
        $user = Auth::user();

        // Khusus siswa
        if ($user->role == 'siswa') {

            // Jika masih pending
            if ($user->status == 'pending') {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/')
                    ->with('login_error',
                        'Akun Anda sedang menunggu persetujuan petugas perpustakaan. Silakan login kembali setelah akun disetujui.');
            }

            // Jika ditolak
            if ($user->status == 'ditolak') {

                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/')
                    ->with('login_error',
                        'Pendaftaran Anda ditolak oleh petugas perpustakaan. Silakan hubungi petugas.');
            }
        }

        // Login berhasil
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}