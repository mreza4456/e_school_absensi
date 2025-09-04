<?php

namespace App\Filament\Resources\KelasResource\Pages;

use App\Filament\Imports\KelasImporter;
use App\Filament\Resources\KelasResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Actions\Imports\Jobs\ImportCsv;
use Filament\Resources\Pages\ListRecords;

class ListKelas extends ListRecords
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(KelasImporter::class)
                ->job(ImportCsv::class),
            Actions\CreateAction::make()->label('Tambah'),
        ];
    }
}
