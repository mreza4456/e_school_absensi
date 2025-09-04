<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Imports\SiswaImporter;
use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(SiswaImporter::class),
            Actions\CreateAction::make()->label('Tambah'),
        ];
    }
}
