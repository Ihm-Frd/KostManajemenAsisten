<?php

namespace App\Filament\Resources\PosterBerandaResource\Pages;

use App\Filament\Resources\PosterBerandaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosterBerandas extends ListRecords
{
    protected static string $resource = PosterBerandaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
    public function getTitle(): string
    {
        return 'Daftar Poster Iklan'; // Judul yang ditampilkan di atas tabel
    }
}
