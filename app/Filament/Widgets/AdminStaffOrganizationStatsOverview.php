<?php

namespace App\Filament\Widgets;

use App\Models\Groups;
use App\Models\Mesin;
use App\Models\Members;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AdminStaffOrganizationStatsOverview extends BaseWidget
{
    use HasWidgetShield, InteractsWithPageFilters;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        return $user->hasRole(['admin_organization', 'staff_organization']);
    }

    protected function getStats(): array
    {
        $organizationId = Auth::user()->organization_id;

        $totalGroups = Groups::where('organization_id', $organizationId)->count();
        $avgMembersPerGroup = 0;

        if ($totalGroups > 0) {
            $avgMembersPerGroup = round(
                Members::where('organization_id', $organizationId)->count() / $totalGroups
            );
        }

        return [
            Stat::make('Users', User::where('organization_id', $organizationId)->count())
                ->description(User::where('status', true)->where('organization_id', $organizationId)->count() . " active users")
                ->descriptionIcon('heroicon-m-user', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Members', Members::where('organization_id', $organizationId)->count())
                ->description(Members::where('status', true)->where('organization_id', $organizationId)->count() . " active members")
                ->descriptionIcon('heroicon-m-users', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            Stat::make('Groups', $totalGroups)
                ->description("Avg. " . $avgMembersPerGroup . " members per group")
                ->descriptionIcon('heroicon-m-building-office', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Machines', Mesin::where('organization_id', $organizationId)->count())
                ->description(Mesin::where('status', true)->where('organization_id', $organizationId)->count() . " active machines")
                ->descriptionIcon('heroicon-m-cpu-chip', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
