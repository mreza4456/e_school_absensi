<?php

namespace App\Providers\Filament;

use App\Filament\Resources\UserResource\Widgets\YourModelWidget;
use App\Filament\Widgets\AbsensiTrendChart;
use App\Filament\Widgets\GlobalAbsensiOverview;
use App\Filament\Widgets\GlobalDeviceStatus;
use App\Filament\Widgets\GlobalStatsOverview;
use App\Filament\Widgets\KeterlambatanPerKelasChart;
use App\Filament\Widgets\MesinStatsOverview;
use App\Filament\Widgets\SchoolAbsensiOverview;
use App\Filament\Widgets\SchoolClassOverview;
use App\Filament\Widgets\SchoolDeviceStatus;
use App\Filament\Widgets\VendorDeviceOverview;
use App\Filament\Widgets\VendorSchoolOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Glob;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->passwordReset()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigationGroups([
                'Manajemen Pengguna',
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label(fn () => Auth::user()->sekolah_id ? 'Profil Sekolah' : 'Profil Vendor')
                    ->url(function () {
                        if (Auth::user()->sekolah_id) {
                            return route('filament.admin.resources.sekolahs.view', ['record' => Auth::user()->sekolah_id]);
                        } elseif (Auth::user()->vendor_id) {
                            return route('filament.admin.resources.vendors.view', ['record' => Auth::user()->vendor_id]);
                        }
                        return '#';
                    })
                    ->icon('heroicon-o-building-library')
                    ->visible(fn () => Auth::user()->sekolah_id !== null || Auth::user()->vendor_id !== null),

            ])
            ->spa()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ]);
    }
}
