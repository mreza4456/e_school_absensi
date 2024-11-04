<?php

namespace App\Filament\Widgets;

use App\Models\Mesin;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class VendorMesinOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['vendor']);
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Mesin', Mesin::count())
                ->description(Mesin::where('status', true)->where('vendor_id', Auth::user()->vendor_id)->count(). " Pengguna aktif")
                ->descriptionIcon('heroicon-m-cpu-chip', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
