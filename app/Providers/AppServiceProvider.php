<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\Notifikasi;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $notif = Notifikasi::where('user_id', auth()->id())
                    ->latest()
                    ->take(5)
                    ->get();

                $unread = Notifikasi::where('user_id', auth()->id())
                    ->where('sudah_dibaca', false)
                    ->count();
            } else {
                $notif = collect();
                $unread = 0;
            }

            $view->with([
                'notifikasi' => $notif,
                'notifCount' => $unread,
            ]);
        });

        Paginator::useBootstrap();
    }
}