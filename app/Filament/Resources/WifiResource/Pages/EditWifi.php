<?php

namespace App\Filament\Resources\WifiResource\Pages;

use App\Filament\Resources\WifiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWifi extends EditRecord
{
    protected static string $resource = WifiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
