<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\JadwalHarian;
use App\Models\Members;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AdminStaffOrganizationAbsensiOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getDateRange(): array
    {
        $dateRange = $this->filters['dateRange'] ?? 'hari_ini';
        $now = Carbon::now();

        return match ($dateRange) {
            'hari_ini' => [$now->startOfDay(), $now->copy()->endOfDay()],
            'kemarin' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            '7_hari_terakhir' => [$now->copy()->subDays(6)->startOfDay(), $now->endOfDay()],
            '30_hari_terakhir' => [$now->copy()->subDays(29)->startOfDay(), $now->endOfDay()],
            'bulan_ini' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
            'bulan_lalu' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
            default => [$now->copy()->subDays(6)->startOfDay(), $now->endOfDay()],
        };
    }

    public static function canView(): bool
    {
        if (request()->is('admin')) {
            return false;
        }
        return true;
    }

    protected function getStats(): array
    {
        [$startDate, $endDate] = $this->getDateRange();
        $groupId = $this->filters['groups'] ?? null;
        $organizationId = Auth::user()->organization_id;

        // Query Members berdasarkan groups dan organization
        $membersQuery = Members::where('organization_id', $organizationId);
        if ($groupId) {
            $membersQuery->where('groups_id', $groupId);
        }
        $members = $membersQuery->get();

        // Query Absensi dengan filter tanggal dan organization
        $absensi = Absensi::query()
            ->where('organization_id', $organizationId)
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Tambah filter groups kalau dipilih
        if ($groupId) {
            $absensi->whereHas('members', function (Builder $query) use ($groupId) {
                $query->where('groups_id', $groupId);
            });
        }

        // Hitung status absensi
        $hadir = (clone $absensi)->whereIn('keterangan', ['Present', 'Late'])->count();
        $terlambat = (clone $absensi)->where('keterangan', 'Late')->count();
        $sakit = (clone $absensi)->where('keterangan', 'Sick')->count();
        $izin = (clone $absensi)->where('keterangan', 'Permit')->count();

        // Cari members tanpa absensi (Alpha)
        $membersTanpaAbsensi = $members->filter(function ($member) use ($startDate, $endDate, $organizationId) {
            $activeDays = JadwalHarian::where('organization_id', $organizationId)
                ->where('is_libur', false)
                ->pluck('hari')
                ->toArray();

            $dayMapping = [
                'Senin' => 'Monday',
                'Selasa' => 'Tuesday',
                'Rabu' => 'Wednesday',
                'Kamis' => 'Thursday',
                'Jumat' => 'Friday',
                'Sabtu' => 'Saturday',
                'Minggu' => 'Sunday',
            ];
            $activeEnglishDays = array_map(fn($day) => $dayMapping[$day] ?? null, $activeDays);

            $period = Carbon::parse($startDate)->toPeriod($endDate);

            foreach ($period as $date) {
                if (!in_array($date->format('l'), $activeEnglishDays)) {
                    continue;
                }

                $hasAbsensi = $member->absensi()
                    ->whereDate('tanggal', $date->format('Y-m-d'))
                    ->exists();

                if ($hasAbsensi) {
                    return false;
                }
            }

            return true;
        });

        $alpha = $membersTanpaAbsensi->count();

        return [
            Stat::make('Present', $hadir)
                ->description("$terlambat Members Late")
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Sick', $sakit)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            Stat::make('Permit', $izin)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Alpha', $alpha)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),
        ];
    }
}
