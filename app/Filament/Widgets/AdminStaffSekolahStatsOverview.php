<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Mesin;
use App\Models\Siswa;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahStatsOverview extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasRole(['admin_sekolah', 'staff_sekolah']);
    }

    protected function getStats(): array
    {
        $rataRataSiswaPerKelas = 0;
        $totalKelas = Kelas::where('sekolah_id', Auth::user()->sekolah_id)->count();
        if ($totalKelas > 0) {
            $rataRataSiswaPerKelas = round(Siswa::where('sekolah_id', Auth::user()->sekolah_id)->count() / $totalKelas);
        }

        return [
            Stat::make('Pengguna', User::where('sekolah_id', Auth::user()->sekolah_id)->count())
                ->description(User::where('status', true)->where('sekolah_id', Auth::user()->sekolah_id)->count(). " Pengguna aktif")
                ->descriptionIcon('heroicon-m-user', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Siswa', Siswa::where('sekolah_id', Auth::user()->sekolah_id)->count())
                ->description(Siswa::where('status', true)->where('sekolah_id', Auth::user()->sekolah_id)->count(). " Siswa aktif")
                ->descriptionIcon('heroicon-m-building-library', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            Stat::make('Kelas', Kelas::where('sekolah_id', Auth::user()->sekolah_id)->count())
                ->description("Rata-rata " . $rataRataSiswaPerKelas . " Siswa per Kelas")
                ->descriptionIcon('heroicon-m-building-office', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Mesin', Mesin::where('sekolah_id', Auth::user()->sekolah_id)->count())
                ->description(Mesin::where('status', true)->where('sekolah_id', Auth::user()->sekolah_id)->count(). " Mesin aktif")
                ->descriptionIcon('heroicon-m-cpu-chip', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
