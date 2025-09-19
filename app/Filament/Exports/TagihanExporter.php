<?php

namespace App\Filament\Exports;

use App\Models\Tagihan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TagihanExporter extends Exporter
{
    protected static ?string $model = Tagihan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('no')->label('No'),          
            ExportColumn::make('dataPenghuni.nama'),
            ExportColumn::make('periode'),
            ExportColumn::make('nominal'),
            ExportColumn::make('status'),
            ExportColumn::make('jatuh_tempo'),
            ExportColumn::make('catatan'),
        ];
        
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data Tagihan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' telah siap. Silahkan pilih format download ✅';
    
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, terdapat ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' yang gagal diekspor. ';
        }
    
        return $body;
    }    
}
