<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriResource\Pages;
use App\Filament\Resources\GaleriResource\RelationManagers;
use App\Models\Galeri;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;

    protected static ?string $navigationIcon = 'heroicon-s-film';

    protected static ?string $navigationLabel = 'Galeri';

    protected static ?string $navigationGroup = 'Manajemen Halaman Awal';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->helperText('Max 20 karakter')
                    ->maxLength(20),
                    Forms\Components\FileUpload::make('gambar')
                    ->directory('galeri') // tersimpan di storage/app/public/galeri
                    ->visibility('public')
                    ->required(),
                    Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(100)
                    ->helperText('Max 100 karakter')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                    Tables\Columns\ImageColumn::make('gambar')
                    ->label('Foto')
                    ->url(fn ($record) => $record->gambar ? asset('storage/' . $record->gambar) : null, shouldOpenInNewTab: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')
                        ->limit(30)
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGaleris::route('/'),
        ];
    }
}
