<?php

namespace App\Filament\Widgets;

use App\Models\Mesin;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SuperAdminStaffMachinesTable extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Machines need to be set';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Mesin::query()
                    ->with(['organization', 'vendor'])
                    ->where('keterangan', 'Belum Diset')
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_mesin')
                    ->url(fn ($record) => route('filament.admin.resources.mesins.view', ['record' => $record->id]))
                    ->icon('heroicon-m-cpu-chip')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->tooltip('Click to view machine details'),

                TextColumn::make('vendor.nama')
                    ->label('Vendor')
                    ->url(fn ($record) => route('filament.admin.resources.vendors.view', ['record' => $record->vendor_id]))
                    ->icon('heroicon-m-building-office')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->tooltip('Click to view vendor details'),

                TextColumn::make('organization.nama')
                    ->label('Organization'),

                TextColumn::make('keterangan')
                    ->label('Activation Status')
                    ->color(fn ($record): string => match ($record->keterangan) {
                        'Sudah Aktif' => 'success',
                        'Belum Diset' => 'primary',
                        'Tidak Aktif' => 'danger',
                        default => 'gray',
                    })
                    ->badge()
                    ->sortable()
                    ->searchable(),

                IconColumn::make('status')
                    ->boolean()
                    ->label('Machine Status'),
            ])
            ->filters([
                SelectFilter::make('vendor')
                    ->relationship('vendor', 'nama')
                    ->label('Vendor')
                    ->placeholder('All Vendors')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('organization')
                    ->relationship('organization', 'nama')
                    ->label('Organization')
                    ->placeholder('All Organizations')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('keterangan')
                    ->options([
                        'Sudah Aktif' => 'Active',
                        'Belum Diset' => 'Not Set',
                        'Tidak Aktif' => 'Inactive',
                    ])
                    ->label('Activation Status')
                    ->native(false)
                    ->placeholder('All Status'),

                TernaryFilter::make('status')
                    ->label('Machine Status')
                    ->placeholder('All Status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->native(false),
            ], layout: FiltersLayout::Dropdown);
    }
}
