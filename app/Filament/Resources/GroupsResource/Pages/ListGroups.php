<?php

namespace App\Filament\Resources\GroupsResource\Pages;

use App\Filament\Imports\GroupsImporter;
use App\Filament\Resources\GroupsResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Actions\Imports\Jobs\ImportCsv;
use Filament\Resources\Pages\ListRecords;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(GroupsImporter::class)
                ->job(ImportCsv::class),
            Actions\CreateAction::make()->label('Add'),
        ];
    }
}
