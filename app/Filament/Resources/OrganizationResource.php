<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Kota;
use App\Models\Organization;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Organization';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        // ... (form tetap sama dengan hasil revisi sebelumnya)
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->height(50),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Organization Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenjang')
                    ->label('Education Level')
                    ->badge(),

                Tables\Columns\TextColumn::make('nama_kepala')
                    ->label('Principal Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('provinsi.nama')
                    ->label('Province')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kota.nama')
                    ->label('City / Regency')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('members_count')
                    ->label('Total Members') // ✅ diubah dari "Jumlah Siswa"
                    ->counts('members')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('jenjang')
                    ->label('Education Level')
                    ->options([
                        'TK' => 'TK',
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                        'SMK' => 'SMK',
                    ]),
                Filter::make('status')
                    ->label('Active')
                    ->query(fn (Builder $query): Builder => $query->where('status', true)),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Organization Details')
                    ->schema([
                        Grid::make(2)->schema([
                            ImageEntry::make('logo')
                                ->label('Organization Logo')
                                ->circular()
                                ->columnSpanFull(),
                            TextEntry::make('npsn')
                                ->label('NPSN'),
                            TextEntry::make('nama')
                                ->label('Organization Name'),
                            TextEntry::make('jenjang')
                                ->label('Education Level'),
                            TextEntry::make('nama_kepala')
                                ->label('Principal Name'),
                            TextEntry::make('no_telp')
                                ->label('Phone Number'),
                            TextEntry::make('email')
                                ->label('Email'),
                            TextEntry::make('alamat')
                                ->label('Full Address')
                                ->columnSpanFull(),
                            TextEntry::make('provinsi.nama')
                                ->label('Province'),
                            TextEntry::make('kota.nama')
                                ->label('City / Regency'),
                        ]),
                    ]),

                Section::make('Additional Info')
                    ->schema([
                        TextEntry::make('timezone')
                            ->label('Timezone'),
                        TextEntry::make('members_count')
                            ->label('Total Members') // ✅ diubah juga di Infolist
                            ->badge()
                            ->color('info'),
                        TextEntry::make('status')
                            ->label('Active Status')
                            ->boolean(),
                    ]),

                Section::make('Operational Schedule')
                    ->schema([
                        RepeatableEntry::make('jadwalHarian')
                            ->schema([
                                TextEntry::make('hari')
                                    ->label('Day'),
                                TextEntry::make('is_libur')
                                    ->label('Holiday')
                                    ->boolean(),
                                TextEntry::make('jam_masuk')
                                    ->label('Start Time'),
                                TextEntry::make('jam_masuk_selesai')
                                    ->label('Late Limit'),
                                TextEntry::make('jam_istirahat')
                                    ->label('Break Start'),
                                TextEntry::make('jam_istirahat_selesai')
                                    ->label('Break End'),
                                TextEntry::make('jam_pulang')
                                    ->label('End Time'),
                                TextEntry::make('jam_pulang_selesai')
                                    ->label('Exit Limit'),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
            'view' => Pages\ViewOrganization::route('/{record}'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user && $user->hasRole('admin_organization') || $user->hasRole('staff_organization')) {
                // If user is 'admin' or 'Organization', filter by their Organization_id
                $query->where('id', $user->Organization_id);
            }
        }

        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') ? true : false;
    }

    // Global Search
    public static function getGloballySearchableAttributes(): array
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') ? ['nama', 'npsn', 'provinsi.nama', 'kota.nama'] : [];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->nama;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            $record->provinsi->nama . ', ' . $record->kota->nama,
        ];
    }
}
