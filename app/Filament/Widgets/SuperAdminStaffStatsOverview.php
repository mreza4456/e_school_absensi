<?php

namespace App\Filament\Widgets;

use App\Models\Mesin;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Vendor;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuperAdminStaffStatsOverview extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengguna', User::count())
                ->description(User::where('status', true)->count(). " Pengguna aktif")
                ->descriptionIcon('heroicon-m-user', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Total Sekolah', Sekolah::count())
                ->description(Sekolah::where('status', true)->count(). " Sekolah aktif")
                ->descriptionIcon('heroicon-m-building-library', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            Stat::make('Total Vendor', Vendor::count())
                ->description(Vendor::where('status', true)->count(). " Vendor aktif")
                ->descriptionIcon('heroicon-m-building-office', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Total Mesin', Mesin::count())
                ->description(Mesin::where('status', true)->count(). " Mesin aktif")
                ->descriptionIcon('heroicon-m-cpu-chip', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
