<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AkunResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AkunResource\RelationManagers;

class AkunResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getPluralModelLabel(): string
    {
        return 'Data Akun Kost Manajemen Asisten'; // Judul halaman list
    }

    protected static ?string $navigationIcon = 'heroicon-s-finger-print';

    protected static ?string $title = 'Akun';

    protected static ?string $navigationLabel = 'Akun ';

    protected static ?string $navigationGroup = 'Manajemen Pengelola';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable() // ✅ ini untuk tombol intip password
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state)) // ✅ hanya proses jika tidak kosong
                    ->required(fn (string $context) => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->options(function () {
                        $query = \Spatie\Permission\Models\Role::query();
                
                        // Kalau bukan super_admin, filter role super_admin dari list
                        if (!auth()->user()->hasRole('super_admin')) {
                            $query->where('name', '!=', 'super_admin');
                        }
                
                        return $query->pluck('name', 'id'); // [id => name]
                    })
                    ->disabled(function (?User $record) {
                        // Kalau user yang sedang diedit punya role super_admin, disable field-nya
                        return $record?->hasRole('super_admin');
                    }),
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
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                // Tables\Columns\TextColumn::make('roles.name')
                // ->searchable(),
                Tables\Columns\TextColumn::make('email')
                ->searchable(),
               
                Tables\Columns\TextColumn::make('user_detail')
                ->alignCenter()
                ->label('Identitas Akun')
                ->searchable()
                ->badge()
                ->color(function ($record) {
                    if ($record->admin) {
                        return 'success'; // Hijau
                    } elseif ($record->dataPenghuni) {
                        return 'info'; // Biru
                    }
                    return 'gray'; // Abu-abu
                })
                ->getStateUsing(function ($record) {
                    if ($record->admin) {
                        return 'Admin - ' . $record->admin->jabatan;
                    } elseif ($record->dataPenghuni) {
                        return 'Penghuni - ' . $record->dataPenghuni->nama;
                    }
                    return 'Akun Belum Digunakan';
                }),
            Tables\Columns\TextColumn::make('password')
                ->searchable()
                ->formatStateUsing(fn ($state) => '●●●●●') // tampilkan simbol saja
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
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAkuns::route('/'),
        ];
    }
}
