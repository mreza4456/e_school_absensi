<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class AdminStaffOrganizationAnalisisAbsensi extends ChartWidget
{
    protected static ?string $heading = 'Attendance Analysis';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        if (request()->is('admin')) {
            return false;
        }

        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        // roles baru: admin_organization & staff_organization
        return $user->hasRole(['admin_organization', 'staff_organization']);
    }

    protected function getData(): array
    {
        $organizationId = Auth::user()->organization_id;
        $now = Carbon::now();

        // Ambil data absensi per bulan
        $attendanceData = [];
        $labels = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($now->year, $month, 1)->startOfMonth();
            $endOfMonth   = Carbon::create($now->year, $month, 1)->endOfMonth();

            $totalMembers = \App\Models\Members::where('organization_id', $organizationId)->count();

            $presentCount = Absensi::where('organization_id', $organizationId)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->whereIn('keterangan', ['Present', 'Late'])
                ->distinct('members_id')
                ->count('members_id');

            $percentage = $totalMembers > 0 ? round(($presentCount / $totalMembers) * 100, 2) : 0;

            $attendanceData[] = $percentage;
            $labels[] = $startOfMonth->format('F');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Attendance Percentage',
                    'data' => $attendanceData,
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54,162,235,0.2)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
