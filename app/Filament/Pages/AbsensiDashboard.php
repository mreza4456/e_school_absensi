<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStaffOrganizationAbsensiChart;
use App\Filament\Widgets\AdminStaffOrganizationAbsensiOverview;
use App\Filament\Widgets\AdminStaffOrganizationAnalisisAbsensi;
use App\Filament\Widgets\AdminStaffOrganizationSiswaSeringTerlambat;
use App\Filament\Widgets\AdminStaffOrganizationSiswaTerlambat;
use App\Models\Groups;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class AbsensiDashboard extends Page
{
    use HasFiltersForm;

    // default range konsisten dengan form default
    public $dateRange = '7_hari_terakhir';
    public ?int $groups = null;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.absensi-dashboard';
    protected static ?string $title = 'Dashboard Attendance';

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('dateRange')
                    ->options([
                        'hari_ini' => 'Today',
                        'kemarin' => 'Yesterday',
                        '7_hari_terakhir' => 'Last 7 Days',
                        '30_hari_terakhir' => 'Last 30 Days',
                        'bulan_ini' => 'This Month',
                        'bulan_lalu' => 'Last Month',
                    ])
                    ->label('Range')
                    ->default($this->dateRange)
                    ->native(false)
                    ->live(),

                Select::make('groups')
                    ->options(function () {
                        $user = Auth::user();
                        if (! $user) {
                            return [];
                        }
                        $organization_id = $user->organization_id;
                        if (! $organization_id) {
                            return [];
                        }

                        return Groups::where('organization_id', $organization_id)
                            ->pluck('groups_name', 'id')
                            ->toArray();
                    })
                    ->default($this->groups)
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->label('Groups')
                    ->placeholder('Choose Groups'),
            ]);
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }

        return $user->hasRole(['admin_organization', 'staff_organization']);
    }
}
