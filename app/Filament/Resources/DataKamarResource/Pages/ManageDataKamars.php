<?php

namespace App\Filament\Resources\DataKamarResource\Pages;

use DB;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\DataKamarResource;

class ManageDataKamars extends ManageRecords
{
    protected static string $resource = DataKamarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}
