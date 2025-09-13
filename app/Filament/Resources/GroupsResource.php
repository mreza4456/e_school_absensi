<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupsResource\Pages;
use App\Filament\Resources\GroupsResource\RelationManagers;
use App\Filament\Resources\GroupsResource\RelationManagers\MembersRelationManager;
use App\Models\Groups;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupsResource extends Resource
{
    protected static ?string $model = Groups::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Organization';

    protected static ?string $navigationLabel = 'Groups';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Organization Information')
                    ->description('Organization data input')
                    ->schema([
                        Forms\Components\Select::make('organization_id')
                            ->required()
                            ->relationship(name: 'organization', titleAttribute: 'nama')
                            ->searchable()
                            ->preload()
                            ->hidden(fn() => Auth::user()->organization_id !== null)
                            ->dehydrated(),
                        Forms\Components\TextInput::make('groups_name')
                            ->label('Group Name')
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
                Tables\Columns\TextColumn::make('organization.nama')
                    ->hidden(fn() => Auth::user()->organization_id != null)
                    ->url(fn($record) => route('filament.admin.resources.organizations.view', ['record' => $record->organization_id]))
                    ->icon('heroicon-m-building-library')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->tooltip('Click to see detail organization'),
                Tables\Columns\TextColumn::make('groups_name')
                    ->label('Group Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('members_count')
                    ->label('Number of Members')
                    ->counts('members'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort(fn() => Auth::user()->organization_id ? 'groups_name' : 'organization.nama')
            ->filters([
                SelectFilter::make('organization')
                    ->relationship('organization', 'nama')
                    ->label('Organization')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->hidden(fn() => Auth::user()->organization_id != null),
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
                // Organization Information Section
                Section::make('Organization Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('organization.nama')
                                    ->label('Organization Name')
                                    ->icon('heroicon-m-building-library')
                                    ->color('primary')
                                    ->badge()
                                    ->url(fn($record) => route('filament.admin.resources.organizations.view', ['record' => $record->organization_id]))
                                    ->hidden(fn() => Auth::user()->organization_id !== null),

                                TextEntry::make('groups_name')
                                    ->label('Group Name')
                                    ->icon('heroicon-m-user-group')
                                    ->color('info')
                                    ->badge()
                            ])
                    ]),

                // Members Information Section
                Section::make('Members List')
                    ->schema([
                        TextEntry::make('total_members')
                            ->label('Number of Members')
                            ->state(fn($record) => $record->members()->count())
                            ->icon('heroicon-m-users')
                            ->color('primary')
                            ->badge(),

                        RepeatableEntry::make('members')
                            ->label('Members in Group')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('nama')
                                            ->label('Member Name')
                                            ->url(fn($record) => route('filament.admin.resources.members.view', ['record' => $record->id]))
                                            ->icon('heroicon-m-user'),

                                        TextEntry::make('jk')
                                            ->label('Gender')
                                            ->formatStateUsing(fn($state) => $state === 'L' ? 'Male' : 'Female')
                                            ->color(fn($record) => $record->jk === 'L' ? 'info' : 'danger')
                                            ->badge(),

                                        TextEntry::make('telp_ortu')
                                            ->label('Contact')
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
                Section::make('Attendance Statistics')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('total_absensi')
                                    ->label('Total Attendance')
                                    ->state(fn($record) => $record->members->flatMap->absensi->count())
                                    ->icon('heroicon-m-document-check')
                                    ->color('primary')
                                    ->badge(),

                                TextEntry::make('status_absensi')
                                    ->label('Attendance Status Distribution')
                                    ->state(function ($record) {
                                        $absensiStatus = $record->members->flatMap->absensi
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
                Section::make('More Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->dateTime(),

                                TextEntry::make('deleted_at')
                                    ->label('Deleted At')
                                    ->dateTime(),
                            ])
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MembersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroups::route('/create'),
            'view' => Pages\ViewGroups::route('/{record}'),
            'edit' => Pages\EditGroups::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user && $user->hasRole('admin_organization') || $user->hasRole('staff_organization')) {
                $query->where('organization_id', $user->organization_id);
            }
        }

        return $query;
    }

    public static function getGloballySearchableAttributes(): array
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') ? ['groups_name', 'organization.nama'] : ['groups_name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->groups_name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        if (Auth::user()->organization_id != null) {
            return [
                "Members" => $record->members->count(),
                "Male" => $record->members->where('jk', 'L')->count(),
                "Female" => $record->members->where('jk', 'P')->count(),
            ];
        } else {
            return [
                "Organization" => $record->organization->nama,
            ];
        }
    }
}
