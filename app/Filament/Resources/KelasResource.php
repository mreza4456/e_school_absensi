<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers;
use App\Filament\Resources\KelasResource\RelationManagers\SiswaRelationManager;
use App\Models\Kelas;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Sekolah';

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
                            ->hidden(fn() => Auth::user()->sekolah_id !== null)
                            ->dehydrated(),
                        Forms\Components\TextInput::make('nama_kelas')
                            ->label('Nama Kelas')
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
                    ->hidden(fn() => Auth::user()->sekolah_id != null)
                    ->url(fn($record) => route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah_id]))
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
            ])->defaultSort(fn() => Auth::user()->sekolah_id ? 'nama_kelas' : 'sekolah.nama')
            ->filters([
                SelectFilter::make('sekolah')
                    ->relationship('sekolah', 'nama')
                    ->label('Sekolah')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->hidden(fn() => Auth::user()->sekolah_id != null),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Class Basic Information Section
                Section::make('Informasi Kelas')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // School Information
                                TextEntry::make('sekolah.nama')
                                    ->label('Sekolah')
                                    ->icon('heroicon-m-building-library')
                                    ->color('primary')
                                    ->badge()
                                    ->url(fn($record) => route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah_id]))
                                    ->hidden(fn() => Auth::user()->sekolah_id !== null),

                                // Class Name
                                TextEntry::make('nama_kelas')
                                    ->label('Nama Kelas')
                                    ->icon('heroicon-m-user-group')
                                    ->color('info')
                                    ->badge()
                            ])
                    ]),

                // Student Information Section
                Section::make('Daftar Siswa')
                    ->schema([
                        // Total Students in the Class
                        TextEntry::make('total_siswa')
                            ->label('Jumlah Siswa')
                            ->state(fn($record) => $record->siswa()->count())
                            ->icon('heroicon-m-users')
                            ->color('primary')
                            ->badge(),

                        // Repeatable Entry for Students
                        RepeatableEntry::make('siswa')
                            ->label('Siswa dalam Kelas')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        // Student Name with Link to Student Detail
                                        TextEntry::make('nama')
                                            ->label('Nama Siswa')
                                            ->url(fn($record) => route('filament.admin.resources.siswas.view', ['record' => $record->id]))
                                            ->icon('heroicon-m-user'),

                                        // Student Gender
                                        TextEntry::make('jk')
                                            ->label('Jenis Kelamin')
                                            ->formatStateUsing(fn($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                                            ->color(fn($record) => $record->jk === 'L' ? 'info' : 'danger')
                                            ->badge(),

                                        // Student Contact Information
                                        TextEntry::make('telp_ortu')
                                            ->label('Telepon Orang Tua')
                                            ->icon('heroicon-m-phone')
                                            ->url(function ($record) {
                                                $phone = preg_replace('/[^0-9]/', '', $record->telp_ortu);
                                                $phone = Str::startsWith($phone, '0') ? Str::substr($phone, 1) : $phone;
                                                $phone = '62' . $phone;
                                                return 'https://wa.me/' . $phone;
                                            })
                                            ->openUrlInNewTab()
                                    ])
                            ])
                            ->grid(2)
                    ]),

                // Attendance Statistics Section
                Section::make('Statistik Kehadiran')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                // Total Attendance Entries
                                TextEntry::make('total_absensi')
                                    ->label('Total Absensi')
                                    ->state(fn($record) => $record->siswa->flatMap->absensi->count())
                                    ->icon('heroicon-m-document-check')
                                    ->color('primary')
                                    ->badge(),

                                // Attendance Status Distribution
                                TextEntry::make('status_absensi')
                                    ->label('Distribusi Status')
                                    ->state(function ($record) {
                                        $absensiStatus = $record->siswa->flatMap->absensi
                                            ->groupBy('keterangan')
                                            ->map->count()
                                            ->toArray();
                                        return json_encode($absensiStatus);
                                    })
                                    ->formatStateUsing(function ($state) {
                                        $statuses = json_decode($state, true);
                                        return collect($statuses)
                                            ->map(fn($count, $status) => "{$status}: {$count}")
                                            ->implode(', ');
                                    })
                                    ->icon('heroicon-m-chart-pie')
                                    ->color('info')
                            ])
                    ]),

                // Metadata Section
                Section::make('Informasi Tambahan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diubah')
                                    ->dateTime(),

                                TextEntry::make('deleted_at')
                                    ->label('Dihapus Pada')
                                    ->dateTime(),
                            ])
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SiswaRelationManager::class
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

    public static function getGloballySearchableAttributes(): array
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') ? ['nama_kelas', 'sekolah.nama'] : ['nama_kelas'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->nama_kelas;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $result = [];
        if (Auth::user()->sekolah_id != null) {
            $result = [
                "Siswa: " => $record->siswa->count(),
                "Laki-laki: " => $record->siswa->where('jk', 'L')->count(),
                "Perempuan: " => $record->siswa->where('jk', 'P')->count(),
            ];
        } else {
            $result = [
                "Sekolah: " . $record->sekolah->nama
            ];
        }
        return $result;
    }
}
