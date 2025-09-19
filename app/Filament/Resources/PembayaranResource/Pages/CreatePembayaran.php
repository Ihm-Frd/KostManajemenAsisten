<?php

namespace App\Filament\Resources\PembayaranResource\Pages;

use Filament\Actions;
use App\Models\Tagihan;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PembayaranResource;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function afterCreate(): void
    {
        $tagihanIds = $this->form->getState()['tagihan_ids'] ?? [];

        if (!empty($tagihanIds)) {
            $this->record->tagihans()->sync($tagihanIds);

            Tagihan::whereIn('id', $tagihanIds)
                ->update(['status' => $this->record->status_pembayaran]);
        }
    }

    protected function afterSave(): void
{
    $tagihanIds = $this->form->getState()['tagihan_ids'] ?? [];

    if (!empty($tagihanIds)) {
        $this->record->tagihans()->sync($tagihanIds);

        \App\Models\Tagihan::whereIn('id', $tagihanIds)
            ->update(['status' => $this->record->status_pembayaran]);
    }
}

}
