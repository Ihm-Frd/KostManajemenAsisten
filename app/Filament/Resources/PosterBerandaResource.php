<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PosterBerandaResource\Pages;
use App\Filament\Resources\PosterBerandaResource\RelationManagers;
use App\Models\PosterBeranda;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PosterBerandaResource extends Resource
{
    protected static ?string $model = PosterBeranda::class;

    protected static ?string $navigationIcon = 'heroicon-s-rocket-launch';

    protected static ?string $navigationLabel = 'Poster Iklan';

    protected static ?string $navigationGroup = 'Manajemen Halaman Awal';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->helperText('maximal 255 karakter')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('gambar')
                    ->directory('posters') // tersimpan di storage/app/public/posters
                    ->visibility('public')
                    ->maxSize(9048) // 2 MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->placeholder('file poster berupa jpeg/png max 9mb')
                    ->helpertext('Gunakan format landscape agar sesuai')
                    ->required(),
                Forms\Components\TextInput::make('link')
                    ->nullable()
                    ->placeholder('opsional')
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
                Tables\Columns\TextColumn::make('link')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPosterBerandas::route('/'),
            'create' => Pages\CreatePosterBeranda::route('/create'),
            'view' => Pages\ViewPosterBeranda::route('/{record}'),
            'edit' => Pages\EditPosterBeranda::route('/{record}/edit'),
        ];
    }
}
