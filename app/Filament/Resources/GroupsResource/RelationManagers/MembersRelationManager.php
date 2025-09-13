<?php

namespace App\Filament\Resources\GroupsResource\RelationManagers;

use App\Models\groups;
use App\Models\Members;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members'; // harus lowercase sesuai relasi di model
    protected static ?string $title = 'Members';

    public function form(Form $form): Form
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        return $form
            ->schema([
                Forms\Components\Section::make('Members Data')
                    ->description('Enter Member Data')
                    ->schema([
                        Forms\Components\Select::make('organization_id')
                            ->label('Organization')
                            ->required()
                            ->relationship(name: 'organization', titleAttribute: 'nama')
                            ->searchable()
                            ->default(fn () => Auth::user()->organization_id ?? null)
                            ->hidden(fn () => Auth::user()->organization_id !== null)
                            ->preload()
                            ->afterStateUpdated(function (callable $set) {
                                $set('groups_id', null);
                            }),

                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->required()
                            ->unique(Members::class, 'nis', ignoreRecord: true)
                            ->numeric(),

                        Forms\Components\TextInput::make('nama')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('panggilan')
                            ->label('Short Name')
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
                                    ->password()
                                    ->revealable(),
                            ])
                            ->default([
                                ['type' => 'rfid', 'value' => null],
                                ['type' => 'fingerprint', 'value' => null],
                                ['type' => 'retina', 'value' => null],
                                ['type' => 'face_id', 'value' => null],
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('groups & Other Data')
                    ->description('Input Member groups Data')
                    ->schema([
                        Forms\Components\Select::make('groups_id')
                            ->label('groups')
                            ->required()
                            ->options(fn (Forms\Get $get): Collection => Groups::query()
                                ->where('organization_id', $get('organization_id'))
                                ->pluck('groups_name', 'id'))
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('jk')
                            ->label('Gender')
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('groups.groups_name')
                    ->label('groups')
                    ->url(fn ($record) => route('filament.admin.resources.groups.view', ['record' => $record->groups_id]))
                    ->hidden(fn () => Auth::user()->organization_id != null)
                    ->icon('heroicon-m-user-groups')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Click to view groups details'),

                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('panggilan')
                    ->label('Short Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jk')
                    ->label('Gender')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Male' : 'Female')
                    ->color(fn ($record) => $record->jk === 'L' ? 'info' : 'danger')
                    ->badge(),

                Tables\Columns\TextColumn::make('telp_ortu')
                    ->label('Contact')
                    ->url(function ($record) {
                        $phone = preg_replace('/[^0-9]/', '', $record->telp_ortu);
                        if (Str::startsWith($phone, '0')) {
                            $phone = Str::substr($phone, 1);
                        }
                        $phone = '62' . $phone;
                        return 'https://wa.me/' . $phone;
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-phone')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->tooltip('Click for WhatsApp Chat'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
