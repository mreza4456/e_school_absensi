<?php

namespace App\Filament\Imports;

use App\Models\Groups;
use App\Models\Organization;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;

class GroupsImporter extends Importer
{
    protected static ?string $model = groups::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('organization')
                ->requiredMapping()
                ->relationship(resolveUsing: function (string $state): ?Organization {
                    return Organization::query()
                        ->where('nama', $state)
                        ->first();
                })
                ->rules(['nullable']),
            ImportColumn::make('groups_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?groups
    {
        // return groups::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new groups();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your groups import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
