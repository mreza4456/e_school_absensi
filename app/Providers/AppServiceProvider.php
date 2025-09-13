<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
            // 🔹 Hook untuk nama sekolah di awal topbar
            Filament::registerRenderHook(
                'panels::topbar.start',
                fn () => view('components.custom-topbar-school', [
                    'schoolName' => Auth::user()?->organization?->nama,
                ]),
            );

            // 🔹 Hook untuk language switcher di user menu
          
        });
    }
}
