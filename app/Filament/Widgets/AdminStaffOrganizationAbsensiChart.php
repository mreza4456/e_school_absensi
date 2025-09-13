<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\JadwalHarian;
use App\Models\Members;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AdminStaffOrganizationAbsensiChart extends ChartWidget
{
    use HasWidgetShield;
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Attendance';

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        if (request()->is('admin')) {
            return false;
        }
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasRole(['admin_organization', 'staff_organization']);
    }

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

    protected function getData(): array
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

        // Query absensi dengan filter tanggal dan organization
        $absensi = Absensi::query()
            ->where('organization_id', $organizationId)
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Add filter groups jika dipilih
        if ($groupId) {
            $absensi->whereHas('members', function (Builder $query) use ($groupId) {
                $query->where('groups_id', $groupId);
            });
        }

        // Hitung status absensi
        $hadir = (clone $absensi)->where('keterangan', 'Present')->count();
        $terlambat = (clone $absensi)->where('keterangan', 'Late')->count();
        $sakit = (clone $absensi)->where('keterangan', 'Sick')->count();
        $izin = (clone $absensi)->where('keterangan', 'Permit')->count();

        // Cari members tanpa absensi (Alpha)
        $membersTanpaAbsensi = $members->filter(function ($member) use ($startDate, $endDate, $organizationId) {
            // Ambil hari aktif dari jadwal harian
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
                    continue; // Skip kalau libur
                }

                $hasAbsensi = $member->absensi()
                    ->whereDate('tanggal', $date->format('Y-m-d'))
                    ->exists();

                if ($hasAbsensi) {
                    return false; // Tidak Alpha kalau ada absensi
                }
            }

            return true; // Alpha kalau tidak ada absensi di hari aktif
        });

        $alpha = $membersTanpaAbsensi->count();

        return [
            'datasets' => [
                [
                    'label' => 'Attendance Members Status',
                    'data' => [$hadir, $terlambat, $sakit, $izin, $alpha],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.7)',   // Green-500 Present
                        'rgba(245, 158, 11, 0.7)',  // Amber-500 Late
                        'rgba(59, 130, 246, 0.7)',  // Blue-500 Sick
                        'rgba(132, 204, 22, 0.7)',  // Lime-500 Permit
                        'rgba(239, 68, 68, 0.7)',   // Red-500 Alpha
                    ],
                ],
            ],
            'labels' => ['Present', 'Late', 'Sick', 'Permit', 'Alpha'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
