<?php

namespace App\Filament\Imports;

use App\Models\Kelas;
use App\Models\Sekolah;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;

class KelasImporter extends Importer
{
    protected static ?string $model = Kelas::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('sekolah')
                ->requiredMapping()
                ->relationship(resolveUsing: function (string $state): ?Sekolah {
                    return Sekolah::query()
                        ->where('nama', $state)
                        ->first();
                })
                ->rules(['nullable']),
            ImportColumn::make('nama_kelas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Kelas
    {
        // return Kelas::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Kelas();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your kelas import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
