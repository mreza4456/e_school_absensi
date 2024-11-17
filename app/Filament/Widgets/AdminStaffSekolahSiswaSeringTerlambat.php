<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Siswa;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahSiswaSeringTerlambat extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Siswa Paling Sering Terlambat';

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['admin_sekolah', 'staff_sekolah']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Siswa::query()
                    ->where('sekolah_id', Auth::user()->sekolah_id)
                    ->withCount(['absensi' => function (Builder $query) {
                        $query->where('keterangan', 'Terlambat');
                    }])
                    ->whereHas('absensi', function (Builder $query) {
                        $query->where('keterangan', 'Terlambat');
                    })  // Mengganti having dengan whereHas
                    ->orderByDesc('absensi_count')
                    ->limit(10);
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama Siswa')
                    ->url(fn ($record) => route('filament.admin.resources.siswas.view', ['record' => $record->id]))
                    ->icon('heroicon-m-academic-cap')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Klik untuk melihat detail siswa'),
                Tables\Columns\TextColumn::make('panggilan')->label('Panggilan'),
                Tables\Columns\TextColumn::make('absensi_count')->label('Total Terlambat'),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->placeholder('Semua Kelas')
                    ->options(function () {
                        return Kelas::where('sekolah_id', Auth::user()->sekolah_id)
                            ->pluck('nama_kelas', 'id')
                            ->toArray();
                    })
                    ->searchable(),
            ]);
    }
}
