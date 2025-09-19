<?php

namespace App\Filament\Resources\PosterBerandaResource\Pages;

use App\Filament\Resources\PosterBerandaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPosterBeranda extends ViewRecord
{
    protected static string $resource = PosterBerandaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

}
