<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Admin;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DataPenghuni;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\DataPenghuniExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DataPenghuniResource\Pages;
use App\Filament\Resources\DataPenghuniResource\RelationManagers;

class DataPenghuniResource extends Resource
{
    protected static ?string $model = DataPenghuni::class;

    public static function getPluralModelLabel(): string
    {
        return 'Data Penghuni Kost'; // Judul halaman list
    }

    protected static ?string $navigationIcon = 'heroicon-s-identification';

    protected static ?string $navigationLabel = 'Data Penghuni';

    protected static ?string $navigationGroup = 'Sistem Menejemen';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('data_kamar_id')
                ->relationship(
                    name: 'dataKamar',
                    titleAttribute: 'lokasi',
                    modifyQueryUsing: fn ($query) => $query->where('status_kamar', 'Kosong')
                )
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_kamar} - {$record->lokasi}")
                ->placeholder(function () {
                    return \App\Models\DataKamar::where('status_kamar', 'Kosong')->count() === 0
                        ? 'Maaf semua kamar penuh'
                        : 'Pilih kamar tersedia';
                })
                ->required()
                ->dehydrated(true)
                ->preload()
                ->live()
                ->reactive()
                ->searchable(),
            Forms\Components\Select::make('user_id')
                ->label('Akun Penghuni')
                ->placeholder('Pilih Akun Untuk Penghuni')
                ->unique(ignoreRecord: true)
                ->disabled(fn (Get $get) => filled($get('id')))
                ->dehydrated(true)
                ->searchable()
                ->label('Akun Penghuni')
                ->preload()
                ->live()
                ->reactive()
                ->relationship(
                    name: 'User',
                    titleAttribute: 'name',
                    modifyQueryUsing: function ($query) {
                        $usedInPenghuni = DataPenghuni::pluck('user_id')->toArray();
                        $usedInAdmin = Admin::pluck('user_id')->toArray();
                        $excludedIds = array_unique(array_merge($usedInPenghuni, $usedInAdmin));
            
                        return $query->whereNotIn('id', $excludedIds);
                    }
                )
                ->required(),
            Forms\Components\TextInput::make('nik')
                ->label('Nik')
                ->placeholder('Pastikan NIK Berjumlah 16 Digit')
                ->required()
                ->numeric()
                ->minLength(16)
                ->maxLength(16)
                ->rule('digits:16')
                ->unique(ignoreRecord: true)
                ->disabled(fn (Get $get) => filled($get('id'))),
            Forms\Components\TextInput::make('nama')
                ->label('Nama Lengkap')
                ->placeholder('Nama max 30 karakter')
                ->unique(ignoreRecord: true)
                ->disabled(fn (Get $get) => filled($get('id')))
                ->maxLength(30)
                ->required(),
            Forms\Components\DatePicker::make('tgl_lahir')
                ->label('Tanggal Lahir')
                ->placeholder('Pilih Tanggal Yang Sesuai')
                ->native(false)
                ->required(),
            Forms\Components\TextInput::make('no_wa')
                ->label('Nomor WA')
                ->numeric()
                ->label('No. WhatsApp')
                ->required()
                ->maxLength(15)
                ->prefixIcon('heroicon-o-phone')
                ->tel()
                ->placeholder('Contoh: 089876543210')
                    ->dehydrateStateUsing(function (string $state) {
                        // Hapus semua karakter selain angka
                        $cleaned = preg_replace('/[^0-9]/', '', $state);

                        // Jika diawali 0 (misal: 08xxx), ganti ke 62
                        if (str_starts_with($cleaned, '0')) {
                            $cleaned = '62' . substr($cleaned, 1);
                        }

                        return $cleaned;
                    })
                    ->helperText('Masukkan dengan awalan 08 !!!'),

            Forms\Components\Select::make('jns_kelamin')
                ->label('Jenis Kelamin')
                ->required()
                ->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ]),
            Forms\Components\Select::make('status')
                ->label('Status Perkawinan')
                ->required()
                ->native()
                ->options([
                    'Lajang' => 'Lajang',
                    'Menikah' => 'Menikah',
                ]),
            Forms\Components\Select::make('keterangan')
                ->label('Keterangan')
                ->required()
                ->native()
                ->options([
                    'Aktif' => 'Aktif',
                    'NonAktif' => 'NonAktif',
                ]),
            Forms\Components\FileUpload::make('pas_foto')
                ->label('Foto KTP')
                ->placeholder('Foto Berformat jpg/png Max 3mb')
                ->directory('KTP_Penghuni')
                ->label('Foto KTP')
                ->maxSize(3048) // 2 MB
                ->acceptedFileTypes(['image/jpeg', 'image/png' ])
                ->required(),
        ])->columns(3);
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
            Tables\Columns\TextColumn::make('dataKamar.nama_kamar')
                ->label('No.Pintu')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('User.name')
                ->label('User Name')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama')
                ->searchable(),
            Tables\Columns\IconColumn::make('no_wa')
            ->label('WA')
            ->icon('heroicon-s-chat-bubble-left-ellipsis')
            ->color('success')
            ->url(fn ($record) => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->no_wa), shouldOpenInNewTab: true)
            ->tooltip(fn ($record) => 'Hubungi : ' . $record->no_wa),
            Tables\Columns\TextColumn::make('nik')
                ->label('Nik')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),   
            Tables\Columns\TextColumn::make('tgl_lahir')
                ->label('Tgl Lahir')
                ->date()
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('jns_kelamin')
                ->label('Jenis Kelamin')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('keterangan')
                ->limit(30)
                ->label('Catatan')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\ImageColumn::make('pas_foto')
                ->label('Foto KTP')
                ->url(fn ($record) => $record->pas_foto ? asset('storage/' . $record->pas_foto) : null, shouldOpenInNewTab: true)
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Buat')
                ->searchable()
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Tanggal Update')
                ->searchable()
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
                    ->exporter(DataPenghuniExporter::class)
                    ->color('info')
                    ->label('Cetak Data 🖨️')
                    ->formats([
                        ExportFormat::Csv,
                        ExportFormat::Xlsx,
                    ])
                    ->visible(fn () => ! auth()->user()?->hasRole('penghuni')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDataPenghunis::route('/'),
        ];
    }
}
