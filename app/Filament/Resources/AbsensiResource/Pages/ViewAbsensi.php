<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAbsensi extends ViewRecord
{
    protected static string $resource = AbsensiResource::class;
   public function getTitle(): string
{
    return __('View Attendance');
}
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit'),
        ];
    }
}
