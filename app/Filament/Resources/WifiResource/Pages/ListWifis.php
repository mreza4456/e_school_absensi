<?php

namespace App\Filament\Resources\WifiResource\Pages;

use App\Filament\Resources\WifiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWifis extends ListRecords
{
    protected static string $resource = WifiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
