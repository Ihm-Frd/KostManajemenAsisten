<?php

namespace App\Filament\Exports;

use App\Models\Pembayaran;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PembayaranExporter extends Exporter
{
    protected static ?string $model = Pembayaran::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('penghuni.nama'),
            ExportColumn::make('invoice'),
            ExportColumn::make('bukti_transfer'),
            ExportColumn::make('tagihans_periode')->label('Periode Tagihan')->formatStateUsing(
                fn($state, $record) => $record->tagihans->pluck('periode')->join(', ')
            ),
            ExportColumn::make('tgl_bayar'),
            ExportColumn::make('status_pembayaran'),
            ExportColumn::make('keterangan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data Pembayaran ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' telah siap. Silahkan pilih format download ✅';
    
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, terdapat ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' yang gagal diekspor. ';
        }
    
        return $body;
    }   

    public static function with(): array
{
    return ['tagihans', 'tagihans.pivot'];
}

}
