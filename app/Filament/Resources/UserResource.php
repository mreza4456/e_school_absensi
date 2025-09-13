<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'User';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        assert($user instanceof User);

        return $form
            ->schema([
                Forms\Components\Section::make('Profile Information')
                    ->description('Update your account\'s profile information.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('avatar_url')
                                    ->image()
                                    ->avatar()
                                    ->directory('users-profiles')
                                    ->visibility('public')
                                    ->columnSpanFull()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('400')
                                    ->imageResizeTargetHeight('400'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                    ]),

                Forms\Components\Section::make('Roles')
                    ->description('Manage user roles and account status.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('roles')
                                    ->relationship(
                                        name: 'roles',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn($query) => $query->where('id', '!=', 2),
                                    )
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        if ($record) {
                                            $currentRoles = $record->roles->pluck('id')->toArray();
                                            $component->state($currentRoles);
                                        }
                                    })
                            ])
                            ->columns(2),
                    ]),
                Forms\Components\Toggle::make('status')
                    ->required(),
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

                Tables\Columns\TextColumn::make('institution_type')
                    ->hidden(function ($record) {
                        return $record && $record->organization || $record && $record->vendor ? false : true;
                    })
                    ->label('Organization/Vendor')
                    ->state(function ($record) {
                        return $record->organization?->nama ?? $record->vendor?->nama;
                    })
                    ->badge()
                    ->color('primary')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('organization', fn($q) => $q->where('nama', 'like', "%{$search}%"))
                            ->orWhereHas('vendor', fn($q) => $q->where('nama', 'like', "%{$search}%"));
                    })
                    ->description(function ($record) {
                        return $record->organization ? 'Organization' : 'Vendor';
                    })
                    ->url(function ($record) {
                        if ($record->organization) {
                            return route('filament.admin.resources.organizations.view', ['record' => $record->organization->id]);
                        } elseif ($record->vendor) {
                            return route('filament.admin.resources.vendors.view', ['record' => $record->vendor->id]);
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user && $user->hasRole('admin_organization') || $user->hasRole('staff_organization')) {
                $query->where('organization_id', $user->organization_id);
            } elseif ($user && $user->hasRole('vendor')) {
                $query->where('vendor_id', $user->vendor_id);
            }
        }

        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') || $user->hasRole('admin_organization') ? true : false;
    }
}
