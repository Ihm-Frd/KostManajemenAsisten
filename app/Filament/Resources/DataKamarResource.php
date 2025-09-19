<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\DataKamar;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\DataKamarExporter;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\DataKamarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DataKamarResource\RelationManagers;

class DataKamarResource extends Resource
{
    protected static ?string $model = DataKamar::class;

    public static function getPluralModelLabel(): string
    {
        return 'Data Kamar Kost Anugrah Group'; // Judul halaman list
    }

    protected static ?string $navigationIcon = 'heroicon-s-home-modern';

    protected static ?string $navigationLabel = 'Data Kost';

    protected static ?string $navigationGroup = 'Sistem Menejemen';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('nama_kamar')
            ->label('Nama Kost')
            ->placeholder('Format: (3 angka - Nama Pemilik)')
            ->required()
            ->rule('regex:/^\d{3}-[A-Za-z\s.]+$/')
            ->helperText('(contoh: 001-Bpk.Asep)')
            ->disabled(fn (Get $get, $context) => $context === 'edit')
            ->dehydrated(fn (Get $get, $context) => $context !== 'edit')
            ->default(fn (Get $get, $record) => $record?->nama_kamar)
            ->rules(fn (Get $get, $context) => $context === 'create'
                ? ['unique:data_kamars,nama_kamar']
                : []),// <- tidak divalidasi ulang saat edit
        
        
            
            Forms\Components\TextInput::make('lokasi')
                ->label('Lokasi')
                ->placeholder('Lokasi Kost Berada')
                ->required()
                ->maxLength(20),
            Forms\Components\TextInput::make('harga_bulanan')
                ->label('Harga Sewa')
                ->helperText('10.000 ❌ | 10000 ✅')
                ->placeholder('Tuliskan Hanya Angka')
                ->numeric()
                ->required()
                ->maxLength(10),
            Forms\Components\Select::make('fasilitas')
                ->label('Fasilitas')
                ->required()
                ->options([
                    '⭐⭐⭐' => '⭐⭐⭐',
                    '⭐⭐' => '⭐⭐',
                    '⭐' => '⭐',
                ])
                ->native(false),
            Forms\Components\Select::make('status_kamar')
            ->label('Status')
            ->options([
                'Kosong' => 'Kosong',
                'Renovasi' => 'Renovasi',
            ])
            ->default('Kosong')
            ->disabled(function ($state, $record) {
                // Saat create (record null) → disable
                if (!$record) return true;
        
                // Kalau record status "Terpakai", disable
                return $record->status_kamar === 'Terpakai';
            })
            ->native(false),

            Forms\Components\TextArea::make('keterangan')
                ->label('Keterangan')
                ->placeholder('Keterangan Kost')
                ->required()
                ->maxLength(500),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('No.')
                ->searchable()
                ->getStateUsing(function ($record, $livewire) {
                    return ($livewire->getTableRecords()->search($record) + 1);
                }),
            Tables\Columns\TextColumn::make('nama_kamar')
                ->label('Nama Kost')
                ->searchable(),
            Tables\Columns\TextColumn::make('lokasi')
                ->label('Lokasi')
                ->searchable(),
            Tables\Columns\TextColumn::make('harga_bulanan')
                ->label('Harga Sewa')
                ->searchable()
                ->formatStateUsing(function ($state) {
                    // Hapus karakter selain angka
                    $clean = preg_replace('/[^\d]/', '', $state);
                    return 'Rp ' . number_format($clean, 0, ',', '.');
                }),
            
            Tables\Columns\TextColumn::make('status_kamar')
                ->label('Status')
                ->badge()
                ->color(function (string $state) {
                    return match ($state) {
                        'Terpakai' => 'success',   // hijau
                        'Kosong' => 'warning',        // biru
                        'Renovasi' => 'danger',    // merah
                        default => 'secondary',    // abu2 untuk lainnya
                    };
                })
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('fasilitas')
                ->label('Fasilitas')
                ->searchable(),    
            Tables\Columns\TextColumn::make('keterangan')
                ->label('Keterangan')
                ->limit(30)
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Input')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Tanggal Update')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(false)
                    ->tooltip('Lihat Data'),
            
                Tables\Actions\EditAction::make()
                    ->label(false)
                    ->tooltip('Edit Data'),
            
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->tooltip('Hapus Data'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
                ->color('danger')
                ->visible(fn () => ! auth()->user()?->hasRole('penghuni')),
                ExportBulkAction::make()
                    ->exporter(DataKamarExporter::class)
                    ->color('info')
                    ->label('Cetak Data 🖨️')
                    ->formats([
                        ExportFormat::Csv,
                        ExportFormat::Xlsx,
                    ])->visible(fn () => ! auth()->user()?->hasRole('penghuni')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDataKamars::route('/'),
        ];
    }

}
