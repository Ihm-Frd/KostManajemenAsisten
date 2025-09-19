<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Pengaduan;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PengaduanTerbaru extends BaseWidget
{
    protected static ?int $sort = 7;

    public function table(Table $table): Table
    {
        return $table
            ->query(Pengaduan::query())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('Penghuni.nama')
                ->label('Nama Lengkap')
                ->searchable(),
                Tables\Columns\TextColumn::make('Penghuni.dataKamar.nama_kamar')
                ->label('Kamar')
                ->searchable(),
                Tables\Columns\TextColumn::make('Penghuni.dataKamar.lokasi')
                ->label('Lokasi')
                ->searchable(),
                Tables\Columns\ImageColumn::make('foto')
                ->searchable()
                ->label('Foto')
                ->disk('public')
                ->url(fn ($record) => $record->foto ? asset('storage/' . $record->foto) : null, shouldOpenInNewTab: true),
            Tables\Columns\badgeColumn::make('status_pengaduan')
                ->label('Status')
                ->badge()
                ->color(function (string $state) {
                    return match ($state) {
                        'selesai' => 'success',   
                            'proses' => 'info',  
                            'diterima' => 'warning',        
                            'ditolak' => 'danger',  
                            default => 'info',    
                        };
                    }),
            Tables\Columns\TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->limit(10)
                ->searchable(),
            ]);
    }
}
