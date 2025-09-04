<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
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
        Filament::serving(function () {
            // Tambahkan hook ke awal topbar
            Filament::registerRenderHook('panels::topbar.start', function () {
                // Ambil nama sekolah, bisa dari database atau session
                $schoolName = Auth::user()?->sekolah?->nama ?? null;

                return view('components.custom-topbar-school', [
                    'schoolName' => $schoolName,
                ]);
            });
        });
    }
}
