<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WifiResource\Pages;
use App\Filament\Resources\WifiResource\RelationManagers;
use App\Models\Wifi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WifiResource extends Resource
{
    protected static ?string $model = Wifi::class;

    protected static ?string $navigationIcon = 'heroicon-o-wifi';

    protected static ?string $navigationGroup = 'Vendor';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            return $user->hasRole('admin_sekolah') || $user->hasRole('staff_sekolah') ? 'Mesin' : 'Vendor';
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Wifi Information')
                ->description('Enter Wifi Information')
                ->schema([
                    Forms\Components\Select::make('mesin_id')
                        ->required()
                        ->relationship(name: 'mesin', titleAttribute: 'kode_mesin')
                        ->searchable()
                        ->disabled(fn () => Auth::user()->sekolah_id !== null)
                        ->preload()
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('ssid')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->revealable()
                        ->password()
                        ->maxLength(255),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mesin.kode_mesin')
                    ->url(fn ($record) => route('filament.admin.resources.mesins.view', ['record' => $record->mesin_id]))
                    ->icon('heroicon-m-cpu-chip')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->tooltip('Klik untuk melihat detail mesin'),
                Tables\Columns\TextColumn::make('ssid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('password')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('mesin.kode_mesin')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListWifis::route('/'),
            'create' => Pages\CreateWifi::route('/create'),
            'view' => Pages\ViewWifi::route('/{record}'),
            'edit' => Pages\EditWifi::route('/{record}/edit'),
        ];
    }
}
