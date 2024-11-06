<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manajemen Sekolah';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelas')
                ->description('Masukan data kelas')
                ->schema([
                    Forms\Components\Select::make('sekolah_id')
                        ->required()
                        ->relationship(name: 'sekolah', titleAttribute: 'nama')
                        ->searchable()
                        ->preload()
                        ->hidden(fn () => Auth::user()->sekolah_id !== null)
                        ->dehydrated(),
                    Forms\Components\TextInput::make('nama_kelas')
                        ->required()
                        ->maxLength(255),
                ])->columns(2),
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
                Tables\Columns\TextColumn::make('sekolah.nama')
                    ->hidden(fn () => Auth::user()->sekolah_id != null)
                    ->url(fn ($record) => route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah_id]))
                    ->icon('heroicon-m-building-library')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->tooltip('Klik untuk melihat detail sekolah'),
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('siswa_count')
                    ->label('Jumlah Siswa')
                    ->counts('siswa'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort(fn () => Auth::user()->sekolah_id ? 'nama_kelas' : 'sekolah.nama')
            ->filters([
                SelectFilter::make('sekolah')
                    ->relationship('sekolah', 'nama')
                    ->label('Sekolah')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->hidden(fn () => Auth::user()->sekolah_id != null),
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user && $user->hasRole('admin_sekolah') || $user->hasRole('staff_sekolah')) {
                // If user is 'admin' or 'sekolah', filter by their sekolah_id
                $query->where('sekolah_id', $user->sekolah_id);
            }
        }

        return $query;
    }
}
