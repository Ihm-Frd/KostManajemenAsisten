<?php

namespace App\Filament\Resources\DataPenghuniResource\Pages;

use App\Filament\Resources\DataPenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDataPenghunis extends ManageRecords
{
    protected static string $resource = DataPenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
