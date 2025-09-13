<?php

namespace App\Filament\Resources\MembersResource\Pages;

use App\Filament\Imports\MembersImporter;
use App\Filament\Resources\MembersResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MembersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(MembersImporter::class),
            Actions\CreateAction::make()->label('Add'),
        ];
    }
}
