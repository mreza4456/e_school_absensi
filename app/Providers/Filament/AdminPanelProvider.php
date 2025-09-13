<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Register;
use App\Filament\Pages\AbsensiDashboard;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\UserResource\Widgets\YourModelWidget;
use App\Filament\Widgets\AbsensiTrendChart;
use App\Filament\Widgets\GlobalAbsensiOverview;
use App\Filament\Widgets\GlobalDeviceStatus;
use App\Filament\Widgets\GlobalStatsOverview;
use App\Filament\Widgets\KeterlambatanPerKelasChart;
use App\Filament\Widgets\MesinStatsOverview;
use App\Filament\Widgets\OrganizationAbsensiOverview;
use App\Filament\Widgets\OrganizationClassOverview;
use App\Filament\Widgets\OrganizationDeviceStatus;
use App\Filament\Widgets\VendorDeviceOverview;
use App\Filament\Widgets\VendorOrganizationOverview;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Platform;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Symfony\Component\Finder\Glob;
use Livewire\Livewire;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            // ->profile(isSimple: false)
            // ->registration(Register::class)
            ->passwordReset()
            ->emailVerification()
            ->brandName('eschool')
            ->sidebarCollapsibleOnDesktop(true)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'CTRL+K',
                Platform::Mac => '⌘K',
                default => null,
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                AbsensiDashboard::class,
            ])
            ->navigationGroups([
                'Organization',
            ])
           ->userMenuItems([
    MenuItem::make()
        ->label(fn () => Auth::user()->organization_id ? 'Profile' : 'Profil Vendor')
        ->url(function () {
            if (Auth::user()->organization_id) {
                return route('filament.admin.resources.organizations.view', ['record' => Auth::user()->organization_id]);
            } elseif (Auth::user()->vendor_id) {
                return route('filament.admin.resources.vendors.view', ['record' => Auth::user()->vendor_id]);
            }
            return '#';
        })
        ->icon('heroicon-o-building-library')
        ->visible(fn () => Auth::user()->organization_id !== null || Auth::user()->vendor_id !== null),

    'profile' => MenuItem::make()
        ->label(fn() => Auth::user()->name)
        ->url(fn (): string => EditProfilePage::getUrl())
        ->icon('heroicon-m-user-circle')
        ->visible(function (): bool {
            return Auth::user() !== null;
        }),

        
        
        ])
     ->renderHook(
    'panels::user-menu.before',
    function () {
        // Langsung render blade component dari resources/views/components/language-switcher.blade.php
        return view('components.language-switcher')->render();
    }
)

            ->breadcrumbs(false)
            ->spa()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // daftar widget secara kondisional di Filament::serving (lebih aman)
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
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('Profil')
                    ->setNavigationLabel('Profil')
                    ->setNavigationGroup('Group Profile')
                    ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    // ->canAccess(fn () => auth()->user()->id === 1)
                    ->shouldRegisterNavigation(false)
                    // ->shouldShowDeleteAccountForm(false)
                    // ->shouldShowSanctumTokens()
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm()
            ]);
    }
}
