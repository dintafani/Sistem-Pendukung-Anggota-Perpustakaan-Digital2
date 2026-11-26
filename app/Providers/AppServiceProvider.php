<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION UNREAD COUNT
        |--------------------------------------------------------------------------
        |
        | Variabel $unreadCount akan tersedia pada:
        |
        | - layouts.siswa
        | - layouts.petugas
        |
        | Jadi Blade dapat langsung menggunakan:
        |
        | @if($unreadCount > 0)
        |
        */

        View::composer(
            [
                'layouts.siswa',
                'layouts.petugas',
            ],
            function ($view) {

                $unreadCount = 0;

                /*
                |--------------------------------------------------------------------------
                | CEK LOGIN
                |--------------------------------------------------------------------------
                */

                if (Auth::check()) {

                    $unreadCount = Notification::where(
                        'user_id',
                        Auth::id()
                    )
                    ->where('dibaca', false)
                    ->count();
                }

                /*
                |--------------------------------------------------------------------------
                | KIRIM KE VIEW
                |--------------------------------------------------------------------------
                */

                $view->with(
                    'unreadCount',
                    $unreadCount
                );
            }
        );
    }
}