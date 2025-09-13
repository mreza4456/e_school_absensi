<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembersResource\Pages;
use App\Models\Members;
use App\Models\Groups;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
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

class MembersResource extends Resource
{
    protected static ?string $model = Members::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Organization';

    protected static ?string $navigationLabel = 'Members';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Members Data')
                ->description('Enter Members Data')
                ->schema([
                    Forms\Components\Select::make('organization_id')
                        ->label('Organization')
                        ->required()
                        ->placeholder('Choose Organization')
                        ->relationship(name: 'organization', titleAttribute: 'nama')
                        ->searchable()
                        ->default(fn () => Auth::user()->sekolah_id ?? null)
                        ->hidden(fn () => Auth::user()->sekolah_id !== null)
                        ->preload()
                        ->afterStateUpdated(function (callable $set) {
                            $set('groups', null);
                        }),
                    Forms\Components\TextInput::make('nis')
                        ->label('NIS')
                        ->required()
                        ->unique(Members::class, 'nis', ignoreRecord: true)
                        ->numeric(),
                    Forms\Components\TextInput::make('nama')
                        ->label('FullName')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('panggilan')
                        ->label('NickName')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Repeater::make('uidType')
                        ->relationship()
                        ->label('UID')
                        ->schema([
                            Forms\Components\Select::make('type')
                                ->label('Type')
                                ->options([
                                    'rfid' => 'RFID',
                                    'fingerprint' => 'Fingerprint',
                                    'retina' => 'Retina',
                                    'face_id' => 'Face ID',
                                ])
                                ->native(false)
                                ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->label('Attendance Code')
                                    ->password() // Mengatur input sebagai password
                                    ->revealable(),
                        ])
                        ->default([
                            ['type' => 'rfid', 'value' => null],
                            ['type' => 'fingerprint', 'value' => null],
                            ['type' => 'retina', 'value' => null],
                            ['type' => 'face_id', 'value' => null],
                        ])
                        // ->addable($user->hasRole('super_admin'))
                        ->columnSpanFull(),
                ])
                ->columns(2), // Optional: Set columns to 2 for better layout in this section

                Forms\Components\Section::make('Groups Data and Others')
                ->description('Enter Members Groups Data')
                ->schema([
                    Forms\Components\Select::make('groups_id')
                        ->label('groups')
                        ->required()
                        ->options(fn (Forms\Get $get): array =>
    $get('organization_id')
        ? Groups::query()
            ->where('organization_id', $get('organization_id'))
            ->pluck('groups_name', 'id')
            ->toArray()
        : []
)
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('jk')
                        ->label('Genders')
                        ->required()
                        ->options(['L' => 'Male', 'P' => 'Female'])
                        ->native(false),
                    Forms\Components\TextInput::make('telp_ortu')
                        ->label('Contact')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                        ])
                        ->columns(2),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('organization.nama')
                ->label('Organization')
                ->url(fn($record) => route('filament.admin.resources.organizations.view', ['record' => $record->organization_id]))
                ->hidden(fn() => Auth::user()->organization_id != null)
                ->icon('heroicon-m-building-library')
                ->color('primary')
                ->badge()
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('groups.groups_name')
                ->label('Groups')
                ->url(fn($record) => $record->groups
                    ? route('filament.admin.resources.groups.view', ['record' => $record->groups_id])
                    : null
                )
                ->icon('heroicon-m-user-group')
                ->color('primary')
                ->badge()
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nis')
                ->label('NIS')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nama')
                ->label('Member Name')
                ->searchable(),

            Tables\Columns\TextColumn::make('panggilan')
                ->label('Nickname')
                ->searchable(),

            Tables\Columns\TextColumn::make('jk')
                ->label('Gender')
                ->formatStateUsing(fn($state) => $state === 'L' ? 'Male' : 'Female')
                ->badge()
                ->color(fn($state) => $state === 'L' ? 'info' : 'danger'),

            Tables\Columns\TextColumn::make('telp_ortu')
                ->label('Contact'),

            Tables\Columns\IconColumn::make('status')
                ->label('Status')
                ->boolean()
                ->trueIcon('heroicon-m-check-circle')
                ->falseIcon('heroicon-m-x-circle')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('organization')
                ->relationship('organization', 'nama')
                ->label('Organization')
                ->searchable()
                ->preload()
                ->native(false)
                ->hidden(fn() => Auth::user()->organization_id != null),

            SelectFilter::make('groups')
                ->relationship('groups', 'groups_name')
                ->label('Groups')
                ->searchable()
                ->preload()
                ->native(false),
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
                Section::make('Member Information')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('organization.nama')
                                ->label('Organization')
                                ->icon('heroicon-m-building-library')
                                ->color('primary')
                                ->badge()
                                ->url(fn($record) => route('filament.admin.resources.organizations.view', ['record' => $record->organization_id]))
                                ->hidden(fn() => Auth::user()->organization_id !== null),

                            TextEntry::make('groups.groups_name')
                                ->label('Groups')
                                ->icon('heroicon-m-user-group')
                                ->color('primary')
                                ->badge()
                                ->url(fn($record) => $record->groups
                                    ? route('filament.admin.resources.groups.view', ['record' => $record->groups_id])
                                    : null),

                            TextEntry::make('nama')
                                ->label('Member Name')
                                ->icon('heroicon-m-user'),

                            TextEntry::make('jk')
                                ->label('Gender')
                                ->formatStateUsing(fn($state) => $state === 'L' ? 'Male' : 'Female')
                                ->badge()
                                ->color(fn($state) => $state === 'L' ? 'info' : 'danger'),

                            TextEntry::make('telp_ortu')
                                ->label('Parent Contact')
                                ->icon('heroicon-m-phone'),
                        ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMembers::route('/create'),
            'view' => Pages\ViewMembers::route('/{record}'),
            'edit' => Pages\EditMembers::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user instanceof User) {
            if ($user->hasRole('admin_organization') || $user->hasRole('staff_organization')) {
                $query->where('organization_id', $user->organization_id);
            }
        }

        return $query;
    }

    public static function getGloballySearchableAttributes(): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        return $user->hasRole('super_admin') || $user->hasRole('staff')
            ? ['nama', 'organization.nama', 'groups.groups_name']
            : ['nama', 'groups.groups_name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->nama;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        if (Auth::user()->organization_id != null) {
            return [
                "Groups" => $record->groups?->groups_name ?? '-',
                "Gender" => $record->jk == 'L' ? 'Male' : 'Female',
            ];
        } else {
            return [
                "Organization" => $record->organization?->nama ?? '-',
            ];
        }
    }
}
