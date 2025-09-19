<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Pembayaran;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\PembayaranExporter;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    public static function getPluralModelLabel(): string
    {
        return 'Dokumentasi Pembayaran Kost'; // Judul halaman list
    }

    protected static ?string $navigationIcon = 'heroicon-s-scale';
    
    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $navigationGroup = 'Pelayanan Kost';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('data_penghuni_id')
            ->label('Nama Penghuni')
            ->options(function () {
                return \App\Models\DataPenghuni::whereHas('tagihan', function ($query) {
                        $query->whereIn('status', ['belum_dibayar', 'ditolak']);
                    })
                    ->with('dataKamar')
                    ->get()
                    ->mapWithKeys(function ($penghuni) {
                        $nama = $penghuni->nama;
                        $kamar = optional($penghuni->dataKamar)->nama_kamar;
                        return [$penghuni->id => "{$nama} - {$kamar}"];
                    });
            })
            ->searchable()
            ->preload()
            ->reactive()
            ->required(),
            Forms\Components\CheckboxList::make('tagihan_ids')
            ->label('Pilih Tagihan')
            ->options(function (Forms\Get $get) {
                $penghuniId = $get('data_penghuni_id') ?? Auth::user()?->dataPenghuni?->id;
                if (!$penghuniId) return [];
        
                $sudahDibayar = \App\Models\Pembayaran::query()
                    ->where('data_penghuni_id', $penghuniId)
                    ->join('transaksi_detail', 'pembayarans.id', '=', 'transaksi_detail.pembayaran_id')
                    ->pluck('transaksi_detail.tagihan_id')
                    ->unique();
        
                return \App\Models\Tagihan::where('data_penghuni_id', $penghuniId)
                    ->whereIn('status', ['belum_dibayar', 'proses', 'ditolak', 'lunas'])
                    ->whereNotIn('id', $sudahDibayar)
                    ->pluck('periode', 'id');
            })
            ->helperText(function (Forms\Get $get) {
                $penghuniId = $get('data_penghuni_id') ?? Auth::user()?->dataPenghuni?->id;
                if (!$penghuniId) return null;
        
                $sudahDibayar = \App\Models\Pembayaran::query()
                    ->where('data_penghuni_id', $penghuniId)
                    ->join('transaksi_detail', 'pembayarans.id', '=', 'transaksi_detail.pembayaran_id')
                    ->pluck('transaksi_detail.tagihan_id')
                    ->unique();
        
                $options = \App\Models\Tagihan::where('data_penghuni_id', $penghuniId)
                    ->whereIn('status', ['belum_dibayar', 'proses', 'ditolak', 'lunas'])
                    ->whereNotIn('id', $sudahDibayar)
                    ->pluck('periode', 'id');
        
                return $options->isEmpty()
                    ? '🔒 Penghuni ini tidak punya tagihan yang tersedia.'
                    : null;
            })
            ->required()
            ->validationMessages([
                'required' => '🔒 Anda belum memilih tagihan apa pun, atau tidak tersedia.',
            ])
            ->columns(2)
            ->required(fn (callable $get) => blank($get('id')))
            ->disabled(fn (callable $get) => filled($get('id')))
            ->dehydrated(true),
        

                
            Forms\Components\FileUpload::make('invoice')
            ->label('Upload Invoice')
                ->placeholder ('File Berupa Tagihan Dari Pengelola Sesuai Periode Jpg/Png/Pdf')
                ->helperText ('Kesalahan Upload Invoice Tagihan Akan Mempengaruhi Status Pembayaran Anda ‼️')
                ->directory('invoices')
                ->required()
                ->preserveFilenames()
                ->visibility('public'),
        
            Forms\Components\FileUpload::make('bukti_transfer')
                ->label('Bukti Transfer')
                ->placeholder ('File Berupa Bukti Bayar / Transfer Yang Berformat Jpg/Png/Pdf')
                ->helperText ('Pastikan Jumlah Bayar Sesuai Dengan Invoice Tagihan Anda ‼️')
                ->directory('bukti-transfer')
                ->required()
                ->default('bayar ya')
                ->preserveFilenames()
                ->visibility('public'),
        
            Forms\Components\DatePicker::make('tgl_bayar')
                ->label('Tanggal Pembayaran')
                ->native(false)
                ->default(fn () => now())
                ->disabled() // ✅ tampilkan, tapi tidak bisa diubah
                ->dehydrated(true) // ✅ tetap dikirim ke database
                ->required(fn (string $context) => $context === 'create'),
        
            Forms\Components\Select::make('status_pembayaran')
                ->label('Status Pembayaran')
                ->options([
                    'proses' => 'Proses',
                    'lunas' => 'Lunas',
                    'ditolak' => 'Ditolak',
                ])
                ->default('proses')
                ->required(),
            Forms\Components\Textarea::make('keterangan')
            ->label('Catatan / Keterangan')
            ->placeholder('Contoh: Mau bayar tanggal 01/07/2025')
            ->rows(3)
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
            Tables\Columns\TextColumn::make('penghuni.nama')
                ->label('Nama Penghuni')
                ->searchable()
                ->sortable(),
        
                Tables\Columns\TextColumn::make('periode_tagihan')
                ->label('Periode Tagihan')
                ->getStateUsing(fn ($record) => 
                    $record->tagihans
                        ->pluck('periode')
                        ->join(', ')
                )
                ->searchable()
                ->wrap()
                ->sortable(),

            Tables\Columns\TextColumn::make('invoice')
                ->searchable()
                ->label('Invoice')
                ->formatStateUsing(fn($state) => $state ? '📄 Lihat File' : '-')
                ->url(fn($record) => $record->invoice ? asset('storage/' . $record->invoice) : null, shouldOpenInNewTab: true),

            Tables\Columns\ImageColumn::make('bukti_transfer')
                ->searchable()
                ->label('Bukti Transfer')
                ->disk('public')
                ->url(fn ($record) => $record->bukti_transfer ? asset('storage/' . $record->bukti_transfer) : null, shouldOpenInNewTab: true),
        
            Tables\Columns\TextColumn::make('tgl_bayar')
                ->searchable()
                ->label('Tanggal Bayar')
                ->date()
                ->sortable(),
        
            Tables\Columns\BadgeColumn::make('status_pembayaran')
                ->searchable()
                ->colors([
                    'primary' => 'proses',
                    'success' => 'lunas',
                    'danger' => 'ditolak',
                ])
                ->label('Status'),
            Tables\Columns\TextColumn::make('keterangan')
                ->limit(30)
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui')
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

                Tables\Actions\ActionGroup::make([
                        Tables\Actions\Action::make('proses')->label('⌚ Di Proses')->action(fn ($record) => $record->update(['status_pembayaran' => 'proses'])),
                        Tables\Actions\Action::make('lunas')->label('✅ Lunas')->color('success')->action(fn ($record) => $record->update(['status_pembayaran' => 'selesai'])),
                        Tables\Actions\Action::make('tolak')->label('❌ Di Tolak')->color('danger')->action(fn ($record) => $record->update(['status_pembayaran' => 'ditolak'])),
                    ])->label(false)
                    ->icon('heroicon-s-currency-dollar')
                    ->tooltip('Status Pembayaran'),        
            
                Tables\Actions\EditAction::make()
                    ->label(false)
                    ->tooltip('Edit Data'),
            
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->tooltip('Hapus Data'),

              
            ])
            
            
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ])
                // ->color('danger'),
                ExportBulkAction::make()
                ->exporter(PembayaranExporter::class)
                ->color('info')
                ->label('Cetak Data 🖨️')
                ->formats([
                    ExportFormat::Csv,
                    ExportFormat::Xlsx,
                ])
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'view' => Pages\ViewPembayaran::route('/{record}'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
