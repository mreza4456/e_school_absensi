<?php

namespace App\Providers\Filament;

use App\Filament\Pages\AbsensiDashboard;
use App\Filament\Pages\Dashboard;
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
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->brandName('eSchool')
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
                'Sekolah',
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
                'profile' => MenuItem::make()
                    ->label(fn() => Auth::user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
                    //If you are using tenancy need to check with the visible method where ->company() is the relation between the user and tenancy model as you called
                    ->visible(function (): bool {
                        return Auth::user() !== null;
                    }),
            ])
            ->breadcrumbs(false)
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
