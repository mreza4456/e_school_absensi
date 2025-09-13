<?php

namespace App\Filament\Resources\GroupsResource\Pages;

use App\Filament\Resources\GroupsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGroups extends ViewRecord
{
    protected static string $resource = GroupsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
