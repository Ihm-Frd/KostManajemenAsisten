<?php

namespace App\Filament\Resources\PosterBerandaResource\Pages;

use App\Filament\Resources\PosterBerandaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosterBeranda extends EditRecord
{
    protected static string $resource = PosterBerandaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
