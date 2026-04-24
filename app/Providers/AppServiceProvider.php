<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\View::composer('layouts.navigation', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->role === 'admin') {
                    $count = \App\Models\Peminjaman::whereIn('status', ['menunggu', 'menunggu_kembali'])
                        ->orWhere(function($query) {
                            $query->where('status', 'disetujui')
                                  ->where('tanggal_kembali_target', '<', now());
                        })
                        ->count();
                    $view->with('pendingRequestsCount', $count);
                } elseif ($user->role === 'siswa') {
                    $count = \App\Models\Peminjaman::where('user_id', $user->id)
                        ->where('status', 'disetujui')
                        ->count();
                    $view->with('activeLoansCount', $count);
                }
            }
        });
    }
}
