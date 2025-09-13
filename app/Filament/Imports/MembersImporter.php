<?php

namespace App\Filament\Imports;

use App\Models\Groups;
use App\Models\Organization;
use App\Models\Members;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;

class MembersImporter extends Importer
{
    protected static ?string $model = Members::class;

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
                ->rules(['required']),
            ImportColumn::make('groups')
                ->requiredMapping()
                ->relationship(resolveUsing: function (string $state): ?Groups {
                    return Groups::query()
                        ->where('groups_name', $state)
                        ->where('organization_id', Auth::user()->Organization_id)
                        ->first();
                })
                ->rules(['required']),
            ImportColumn::make('nis')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('panggilan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('jk')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('telp_ortu')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): ?Members
    {
        // return Members::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'Organization' => Auth::user()->Organization_id,
        // ]);

        return new Members();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your Members import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
