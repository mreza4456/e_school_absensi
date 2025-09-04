<?php

namespace App\Filament\Imports;

use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

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
                ->rules(['required']),
            ImportColumn::make('kelas')
                ->requiredMapping()
                ->relationship(resolveUsing: function (string $state): ?Kelas {
                    return Kelas::query()
                        ->where('nama_kelas', $state)
                        ->where('sekolah_id', Auth::user()->sekolah_id)
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

    public function resolveRecord(): ?Siswa
    {
        // return Siswa::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'sekolah' => Auth::user()->sekolah_id,
        // ]);

        return new Siswa();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your siswa import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
