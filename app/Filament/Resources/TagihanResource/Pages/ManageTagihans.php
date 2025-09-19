<?php

namespace App\Filament\Resources\TagihanResource\Pages;

use DB;
use Exception;
use Filament\Actions;
use App\Filament\Resources\TagihanResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTagihans extends ManageRecords
{
    protected static string $resource = TagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $sudahAda = \DB::table('pembayarans')
            ->join('transaksi_detail', 'pembayarans.id', '=', 'transaksi_detail.pembayaran_id')
            ->join('tagihans', 'tagihans.id', '=', 'transaksi_detail.tagihan_id')
            ->where('tagihans.data_penghuni_id', $data['data_penghuni_id'])
            ->where('tagihans.periode', $data['periode'])
            ->exists();
    
        if ($sudahAda) {
            throw new Exception('❌ Sudah ada pembayaran untuk periode dan penghuni ini!');
        }
    
        return $data;
    }

        protected function afterDelete(): void
    {
        $tagihan = $this->record;

        $masihAda = \DB::table('transaksi_detail')
            ->where('tagihan_id', $tagihan->id)
            ->exists();

        if (! $masihAda) {
            $tagihan->update(['status' => 'belum_dibayar']);
        }
    }

    
}
