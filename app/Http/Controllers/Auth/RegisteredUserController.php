<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'nis' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_telepon' => ['required','string','max:15'],

        ]);

        // Cek apakah ada akun yang sebelumnya ditolak
$userDitolak = User::where('nis', $request->nis)
    ->where('email', $request->email)
    ->where('status', 'ditolak')
    ->first();

if ($userDitolak) {
    $userDitolak->update([
        'name'        => $request->name,
        'kelas'       => $request->kelas,
        'no_telepon'  => $request->no_telepon,
        'password'    => Hash::make($request->password),
        'status'      => 'pending',
    ]);

        // Notifikasi ke Petugas
    $petugas = User::where('role', 'petugas')->get();

    foreach ($petugas as $admin) {
        Notification::create([
            'user_id' => $admin->id,
            'judul'   => 'Pendaftaran Ulang',
            'pesan'   => 'Siswa "' . $userDitolak->name . '" (NIS: ' . $userDitolak->nis . ') telah mendaftar ulang setelah sebelumnya ditolak dan menunggu persetujuan akun.',
            'jenis'   => 'info',
        ]);
    }
     return redirect('/')
        ->with(
            'register_success',
            'Registrasi berhasil. Akun Anda kembali menunggu persetujuan petugas perpustakaan.'
        );

}

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'status' => 'pending',
            'poin' => 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Notifikasi ke Petugas
        |--------------------------------------------------------------------------
        */

        $petugas = User::where('role', 'petugas')->get();

        foreach ($petugas as $admin) {

            Notification::create([
                'user_id' => $admin->id,
                'judul'   => 'Pendaftaran Baru',
                'pesan'   => 'Siswa "' . $user->name . '" (NIS: '.$user->nis.') telah mendaftar dan menunggu persetujuan akun.',
                'jenis'   => 'info',
            ]);
        }

        event(new Registered($user));

        // Tidak login otomatis
        return redirect('/')
            ->with('register_success',
                'Registrasi berhasil. Akun Anda sedang menunggu persetujuan petugas perpustakaan.');
    }
}