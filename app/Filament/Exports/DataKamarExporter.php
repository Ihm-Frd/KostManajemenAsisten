<?php

namespace App\Filament\Exports;

use App\Models\DataKamar;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DataKamarExporter extends Exporter
{
    protected static ?string $model = DataKamar::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nama_kamar'),
            ExportColumn::make('lokasi'),
            ExportColumn::make('harga_bulanan'),
            ExportColumn::make('fasilitas'),
            ExportColumn::make('status_kamar'),
            ExportColumn::make('keterangan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data Kamar Kost ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' telah siap. Silahkan pilih format download ✅';
    
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, terdapat ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' yang gagal diekspor. ';
        }
    
        return $body;
    }   

    // public static function getCompletedNotificationBody(Export $export): string
    // {
    //     $body = 'Your data kamar export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

    //     if ($failedRowsCount = $export->getFailedRowsCount()) {
    //         $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
    //     }

    //     return $body;
    // }
}
