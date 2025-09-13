<?php

namespace App\Filament\Widgets;

use App\Models\Groups;
use App\Models\Members;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;

class AdminStaffOrganizationMembersOftenLate extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Members with Most Late Attendance';

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        if (request()->is('admin')) {
            return false;
        }

        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        return $user->hasRole(['admin_organization', 'staff_organization']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Members::query()
                    ->where('organization_id', Auth::user()->organization_id)
                    ->withCount(['absensi' => function (Builder $query) {
                        $query->where('keterangan', 'Terlambat');
                    }])
                    ->whereHas('absensi', function (Builder $query) {
                        $query->where('keterangan', 'Terlambat');
                    })
                    ->orderByDesc('absensi_count');
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Member Name')
                    ->url(fn ($record) => route('filament.admin.resources.members.view', ['record' => $record->id]))
                    ->icon('heroicon-m-user')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Click to view member details'),
                Tables\Columns\TextColumn::make('groups.nama')
                    ->label('Group')
                    ->url(function ($record) {
                        $group = Groups::query()
                            ->where('id', $record->group_id)
                            ->where('organization_id', $record->organization_id)
                            ->first();

                        return $group
                            ? route('filament.admin.resources.groups.view', ['record' => $group->id])
                            : null;
                    })
                    ->icon('heroicon-m-user-group')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Click to view group details'),
                Tables\Columns\TextColumn::make('nickname')
                    ->label('Nickname'),
                Tables\Columns\TextColumn::make('absensi_count')
                    ->label('Total Late'),
            ])
            ->filters([
                SelectFilter::make('group_id')
                    ->label('Group')
                    ->placeholder('All Groups')
                    ->options(function () {
                        return Groups::where('organization_id', Auth::user()->organization_id)
                            ->pluck('groups_name', 'id')
                            ->toArray();
                    })
                    ->searchable(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from_date')->label('From Date'),
                        DatePicker::make('to_date')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereHas(
                                    'absensi',
                                    fn (Builder $query) => $query->whereDate('tanggal', '>=', $date)
                                )
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query, $date): Builder => $query->whereHas(
                                    'absensi',
                                    fn (Builder $query) => $query->whereDate('tanggal', '<=', $date)
                                )
                            );
                    }),
            ]);
    }
}
