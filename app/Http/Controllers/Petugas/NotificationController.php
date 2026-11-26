<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Halaman Notifikasi Petugas
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('petugas.notifikasi', compact('notifications'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function read($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update([
            'dibaca' => true,
        ]);

        return redirect()->back();
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function readAll()
    {
        Notification::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->update([
                'dibaca' => true,
            ]);

        return redirect()->back()->with(
            'success',
            'Semua notifikasi telah dibaca.'
        );
    }

    /**
     * Hapus satu notifikasi
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return redirect()->back()->with(
            'success',
            'Notifikasi berhasil dihapus.'
        );
    }

    /**
     * Hapus semua 
     */
    public function destroyAll()
    {
        Notification::where('user_id', Auth::id())->delete();

        return redirect()->back()->with(
            'success',
            'Semua notifikasi berhasil dihapus.'
        );
    }
}