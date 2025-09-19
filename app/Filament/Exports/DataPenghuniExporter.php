<?php

namespace App\Filament\Exports;

use App\Models\DataPenghuni;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DataPenghuniExporter extends Exporter
{
    protected static ?string $model = DataPenghuni::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('dataKamar.id'),
            ExportColumn::make('user.name'),
            ExportColumn::make('nama'),
            ExportColumn::make('nik'),
            ExportColumn::make('tgl_lahir'),
            ExportColumn::make('no_wa'),
            ExportColumn::make('jns_kelamin'),
            ExportColumn::make('status'),
            ExportColumn::make('keterangan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data Penghuni ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' telah siap. Silahkan pilih format download ✅';
    
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, terdapat ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' yang gagal diekspor. ';
        }
    
        return $body;
    }   
}
