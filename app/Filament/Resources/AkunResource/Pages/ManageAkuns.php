<?php

namespace App\Filament\Resources\AkunResource\Pages;

use App\Filament\Resources\AkunResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAkuns extends ManageRecords
{
    protected static string $resource = AkunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->with(['admin', 'dataPenghuni']);
}


}
