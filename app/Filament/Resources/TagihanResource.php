<?php

namespace App\Filament\Resources;

use DB;
use Filament\Forms;
use Filament\Tables;
use App\Models\Tagihan;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use App\Filament\Exports\TagihanExporter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\TagihanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TagihanResource\RelationManagers;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    public static function getPluralModelLabel(): string
    {
        return 'Data Tagihan Kost'; // Judul halaman list
    }

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    protected static ?string $navigationLabel = 'Tagihan Penghuni';

    protected static ?string $navigationGroup = 'Sistem Menejemen';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('Periode')
                ->description('Buat Tagihan')
                ->schema([
                    Forms\Components\Select::make('periode')
                    ->label('Periode Tagihan')
                    ->placeholder('Pilih Periode')
                    ->disabled(fn (Get $get) => filled($get('id')))
                    ->dehydrated(fn (Get $get) => blank($get('id')))

                    ->options([
                        'Januari' => 'Januari',
                        'Februari' => 'Februari',
                        'Maret' => 'Maret',
                        'April' => 'April',
                        'Mei' => 'Mei',
                        'Juni' => 'Juni',
                        'Juli' => 'Juli',
                        'Agustus' => 'Agustus',
                        'September' => 'September',
                        'Oktober' => 'Oktober',
                        'November' => 'November',
                        'Desember' => 'Desember',
                        ])
                    ->label('Periode')
                    ->required()
                    ->rules([
                        function (callable $get) {
                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                $penghuniId = $get('data_penghuni_id');
                                $periode = $value;
                    
                                if (!$penghuniId || !$periode) return;
                    
                                $sudahAda = \App\Models\Tagihan::where('data_penghuni_id', $penghuniId)
                                    ->where('periode', $periode)
                                    ->exists();
                    
                                if ($sudahAda) {
                                    $fail("Penghuni ini sudah memiliki tagihan untuk periode $periode.");
                                }
                            };
                        },
                    ])
                    
                ->hint(function (callable $get) {
                    $penghuniId = $get('data_penghuni_id');
                    $periode = $get('periode');
                
                    if (!$penghuniId || !$periode) return null;
                
                    $sudahDilunasi = \DB::table('pembayarans')
                        ->join('transaksi_detail', 'pembayarans.id', '=', 'transaksi_detail.pembayaran_id')
                        ->join('tagihans', 'transaksi_detail.tagihan_id', '=', 'tagihans.id')
                        ->where('pembayarans.status_pembayaran', 'lunas')
                        ->where('tagihans.data_penghuni_id', $penghuniId)
                        ->where('tagihans.periode', $periode)
                        ->exists();
                
                    return $sudahDilunasi ? '⚠️ Periode ini sudah dilunasi oleh penghuni.' : null;
                })
                       
                    ->native(false),
                    Forms\Components\DatePicker::make('jatuh_tempo')
                        ->label('Jatuh Tempo Tagihan')
                        ->placeholder('Pilih Tanggal Jatuh Tempo')
                        ->disabled(fn (Get $get) => filled($get('id')))
                        ->dehydrated(fn (Get $get) => blank($get('id')))
                        ->label('Jatuh Tempo')
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->unique(
                            table: 'tagihans',
                            column: 'jatuh_tempo',
                            modifyRuleUsing: function ($rule, callable $get, $livewire) {
                                return $rule
                                    ->where('data_penghuni_id', $get('data_penghuni_id'))
                                    ->ignore(data_get($livewire, 'record.id')); // FIX INI JUGA
                            }
                        ),
                        Forms\Components\Select::make('data_penghuni_id')
                        ->label('Nama Penghuni')
                        ->placeholder('Tagihan Tertuju Pada :')
                        ->disabled(fn (Get $get) => filled($get('id')))
                        ->dehydrated(fn (Get $get) => blank($get('id')))
                        ->preload()
                        ->live()
                        ->reactive()
                        ->options(function () {
                            return \App\Models\DataPenghuni::with('dataKamar')->get()->mapWithKeys(function ($record) {
                                $nama = $record->nama;
                                $kamar = optional($record->dataKamar)->nama_kamar;
                                return [$record->id => "{$kamar} - {$nama}"];
                            });
                        })
                        ->searchable()
                        ->required()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $penghuni = \App\Models\DataPenghuni::with('dataKamar')->find($state);
                            if ($penghuni && $penghuni->dataKamar) {
                                $set('nominal', $penghuni->dataKamar->harga_bulanan);
                            }
                        }),
                    
                    Forms\Components\TextInput::make('nominal')
                        ->label('Nominal Tagihan')
                        ->placeholder('Nominal Akan Otomatis Terisi')
                        ->required()
                        ->maxLength(10)
                        ->readOnly()
                        ->disabled(fn (Get $get) => filled($get('id')))
                        ->live(),
                       
                ])
                ->columns(2),
            Forms\Components\Section::make('Keterangan')
                ->description('Masukkan Keterangan Tagihan')
                ->schema([
            Forms\Components\Select::make('status')
                    ->label('Status Tagihan')
                    ->helperText('Status Tagihan Otomatis ‼️')
                    ->required()
                    ->options ([
                        'belum_dibayar' => 'Belum Dibayar',
                        'lunas' => 'Lunas',
                        'ditolak' => 'Ditolak',
                    ])                    
                    ->disabled() // ❗ tidak bisa diubah di create/edit
                    ->default('belum_dibayar'),  
            Forms\Components\Textarea::make('catatan')
                    ->label('Catatan / Keterangan')
                    ->placeholder('Contoh: mau bayar tanggal 01/01/2020')
                    ->rows(3)
                    ->maxLength(500),
                 ])->columns(2),
           
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
            Tables\Columns\TextColumn::make('dataPenghuni.nama')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('nominal')
                ->label('Nominal')
                ->searchable()
                ->formatStateUsing(function ($state) {
                    // Hapus karakter selain angka
                    $clean = preg_replace('/[^\d]/', '', $state);
                    return 'Rp ' . number_format($clean, 0, ',', '.');
                }),
            
            Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->badge()
                ->color(function (string $state) {
                    return match ($state) {
                        'lunas' => 'success',   // hijau
                        'belum_dibayar' => 'warning',        // biru
                        'ditolak' => 'danger',    // merah
                        default => 'info',    // abu2 untuk lainnya
                    };
                }),
            Tables\Columns\TextColumn::make('periode')
                ->searchable()
                ->sortable(),    
            Tables\Columns\TextColumn::make('jatuh_tempo')
                ->searchable()
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('catatan')
                ->searchable()
                ->sortable(),
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
                ->exporter(TagihanExporter::class)
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
            'index' => Pages\ManageTagihans::route('/'),
        ];
    }
}
