<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    public static function getPluralModelLabel(): string
    {
        return 'Pengelola Kost Anugrah Group'; // Judul halaman list
    }

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';

    protected static ?string $navigationLabel = 'Data Admin';

    protected static ?string $navigationGroup = 'Manajemen Pengelola';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->label('User Name')
                    ->placeholder('Pilih Akun Yang Anda Miliki')
                    ->searchable()
                    ->relationship('User', 'name')
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('kode')
                    ->label('Nomor ID Card')
                    ->placeholder('Id / Kode Karyawan')
                    ->required()
                    ->Unique()
                    ->maxLength(30),
                Forms\Components\TextInput::make('no_wa')
                    ->label('Nomor WA')
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
                Forms\Components\Select::make('jabatan')
                    ->label('Departemen')
                    ->placeholder('Departemen / Jabatan Anda Di Perusahaan ini')
                    ->native(false)
                    ->options ([
                    'manager' => 'Manager',
                    'officer' => 'Officer',
                    'pekerja_lapangan' => 'Pekerja Lapangan',
                ])
                    ->required(),
                Forms\Components\Textarea::make('jobdesk')
                    ->label('Job Deskripsi')
                    ->placeholder('Keterangan Deskripsi Kerja')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('foto_ktp')
                    ->label('Foto KTP / ID Card')
                    ->placeholder('File Berformat Jpg /Png')
                    ->required(),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->placeholder('Keterangan Tambahan')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('User.name')
                    ->label('User Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\IconColumn::make('no_wa')
                    ->label('Nomor WA')
                    ->searchable()
                    ->icon('heroicon-s-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn ($record) => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $record->no_wa), shouldOpenInNewTab: true)
                    ->tooltip(fn ($record) => 'Hubungi : ' . $record->no_wa),
                Tables\Columns\TextColumn::make('jabatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobdesk')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto_ktp')
                ->label('Foto KTP')
                ->url(fn ($record) => $record->pas_foto ? asset('storage/' . $record->foto_ktp) : null, shouldOpenInNewTab: true)
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable()
                    ->limit(30),
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
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAdmins::route('/'),
        ];
    }
}
