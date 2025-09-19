<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pengaduan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PengaduanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PengaduanResource\RelationManagers;

class PengaduanResource extends Resource
{
    public static function getPluralModelLabel(): string
    {
        return 'Data Pengaduan Kost'; // Judul halaman list
    }
    protected static ?string $model = Pengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-s-exclamation-triangle';

    protected static ?string $navigationLabel = 'Pengaduan';

    protected static ?string $navigationGroup = 'Pelayanan Kost';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('data_penghuni_id')
                ->label('Nama Penghuni')
                ->placeholder('Pilih Penghuni')
                ->disabled(auth()->user()->hasRole('penghuni'))
                ->default(fn () => auth()->user()->hasRole('penghuni') ? auth()->user()->dataPenghuni?->id : null)

                ->dehydrated(true)
                ->options(function () {
                    $user = auth()->user();
            
                    if ($user->hasRole('penghuni')) {
                        $penghuni = $user->dataPenghuni;
                        if (!$penghuni || !$penghuni->dataKamar) return [];
            
                        return [
                            $penghuni->id => "{$penghuni->dataKamar->nama_kamar} - {$penghuni->nama} - {$penghuni->dataKamar->lokasi}"
                        ];
                    }
            
                    // Admin atau role lain
                    return \App\Models\DataPenghuni::with(['dataKamar', 'tagihan' => fn ($q) => $q->where('status', '!=', 'lunas')])
                        ->get()
                        ->filter(fn ($penghuni) => $penghuni->tagihan->isNotEmpty() && $penghuni->dataKamar)
                        ->mapWithKeys(function ($penghuni) {
                            return [
                                $penghuni->id => "{$penghuni->dataKamar->nama_kamar} - {$penghuni->nama} - {$penghuni->dataKamar->lokasi}"
                            ];
                        });
                })
                ->searchable()
                ->required()
                ->live()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $penghuni = \App\Models\DataPenghuni::with('dataKamar')->find($state);
            
                    if ($penghuni) {
                        if ($penghuni->dataKamar) {
                            $set('lokasi', $penghuni->dataKamar->lokasi);
                            $set('kamar', $penghuni->dataKamar->nama_kamar);
                        }
            
                        $set('no_wa', $penghuni->no_wa);
                    }
                }),
                Forms\Components\FileUpload::make('foto')
                    ->placeholder('Tambahkan gambar (Opsional)')
                    ->label('Foto')
                    ->directory('pengaduan')
                    ->maxSize(2048) // 2 MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->nullable(),
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->placeholder('Silahkan tuliskan pengaduan anda ‼️')
                    ->required()
                    ->maxLength(300),
                Forms\Components\Select::make('status_pengaduan')
                    ->label('Status Pengaduan')
                    ->helperText('Status Pengaduan Otomatis ‼️')
                    ->required()
                    ->options ([
                        'diterima' => 'Diterima',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
                    ])                    
                    ->disabled() // ❗ tidak bisa diubah di create/edit
                    ->default('diterima'),
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
                Tables\Columns\TextColumn::make('Penghuni.nama')
                    ->label('Nama Lengkap')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Penghuni.dataKamar.nama_kamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Penghuni.dataKamar.lokasi')
                    ->label('Lokasi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('Penghuni.no_wa')
                    ->searchable()
                    ->label('WA')
                    ->icon('heroicon-s-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn ($record) => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->no_wa), shouldOpenInNewTab: true)
                    ->tooltip(fn ($record) => 'Hubungi : ' . $record->Penghuni?->no_wa)
                    ->visible(fn () => ! auth()->user()?->hasRole('penghuni')),
                Tables\Columns\ImageColumn::make('foto')
                    ->searchable()
                    ->label('Foto')
                    ->disk('public')
                    ->url(fn ($record) => $record->foto ? asset('storage/' . $record->foto) : null, shouldOpenInNewTab: true),
                Tables\Columns\BadgeColumn::make('status_pengaduan')
                    ->badge()
                    ->color(function (string $state) {
                        return match ($state) {
                            'selesai' => 'success',   
                                'proses' => 'info',  
                                'diterima' => 'warning',        
                                'ditolak' => 'danger',  
                                default => 'info',    
                            };
                        })
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('proses')->label('⌚ Proses')->action(fn ($record) => $record->update(['status_pengaduan' => 'proses'])),
                    Tables\Actions\Action::make('selesai')->label('✅ Selesai')->color('success')->action(fn ($record) => $record->update(['status_pengaduan' => 'selesai'])),
                    Tables\Actions\Action::make('tolak')->label('❌ Tolak')->color('danger')->action(fn ($record) => $record->update(['status_pengaduan' => 'ditolak'])),
                ])
                ->label(false)
                ->icon('heroicon-s-exclamation-triangle')
                ->tooltip('Status Pengaduan')
                ->visible(fn () => ! auth()->user()?->hasRole('penghuni')), 
                
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
                ->visible(fn () => ! auth()->user()?->hasRole('penghuni')),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePengaduans::route('/'),
        ];
    }
}
