<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /*
     * Tampilkan semua notifikasi siswa
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'siswa.notifikasi',
            compact('notifications')
        );
    }

    /*
     * Hapus satu notifikasi
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return back()->with(
            'success',
            'Notifikasi berhasil dihapus.'
        );
    }

    /*
     * Hapus semua notifikasi
     */
    public function destroyAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return back()->with(
            'success',
            'Semua notifikasi berhasil dihapus.'
        );
    }

    /*
     * Tandai satu notifikasi sudah dibaca
     */
    public function read($id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notif->update([
            'dibaca' => true
        ]);

        return back();
    }

    /*
     * Tandai semua notifikasi sudah dibaca
     */
    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->update([
                'dibaca' => true
            ]);

        return back();
    }
}