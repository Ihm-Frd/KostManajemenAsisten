<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;

class PembayaranSaya extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $view = 'filament.pages.pembayaran-saya';

    protected static ?string $title = 'Pembayaran Saya';

    protected static ?string $navigationGroup = 'Pelayanan Kost';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'pembayaran-saya';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->hasRole('penghuni');
    }

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->hasRole('penghuni');
    }

    protected function getTableQuery()
    {
        return Pembayaran::query()
            ->whereHas('penghuni', function ($query) {
                $query->where('user_id', Auth::id());
            });
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('invoice')
                ->label('Invoice')
                ->formatStateUsing(fn($state) => $state ? '📄 Lihat File' : '-')
                ->url(fn($record) => $record->invoice ? asset('storage/' . $record->invoice) : null, shouldOpenInNewTab: true),

            Tables\Columns\ImageColumn::make('bukti_transfer')
                ->label('Bukti Transfer')
                ->disk('public')
                ->url(fn ($record) => $record->bukti_transfer ? asset('storage/' . $record->bukti_transfer) : null, shouldOpenInNewTab: true),

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
            Tables\Columns\TextColumn::make('tgl_bayar')
                ->label('Tanggal Bayar')
                ->date(),

            Tables\Columns\BadgeColumn::make('status_pembayaran')
                ->label('Status')
                ->colors([
                    'primary' => 'proses',
                    'success' => 'lunas',
                    'danger' => 'ditolak',
                ]),

            Tables\Columns\TextColumn::make('keterangan')
                ->label('Keterangan'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->label('Tambah Pembayaran')
                ->form($this->getFormSchema())
                ->mutateFormDataUsing(function (array $data) {
                    unset($data['status_pembayaran']);
                    $data['status_pembayaran'] = 'proses';
                    $data['data_penghuni_id'] = Auth::user()->dataPenghuni->id;
                    $data['tgl_bayar'] = now();
                    return $data;
                })
                ->using(function (array $data) {
                    $pembayaran = Pembayaran::create($data);
                    if (isset($data['tagihan_ids'])) {
                        $pembayaran->tagihans()->sync($data['tagihan_ids']);
                    }
                    return $pembayaran;
                }),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('invoice')
                ->label('Upload Invoice')
                ->placeholder('File PDF (max 2 MB) Berupa Tagihan Dari Pengelola Sesuai Periode')
                ->helperText('Kesalahan upload akan mempengaruhi status pembayaran Anda ‼️')
                ->directory('invoices')
                ->required()
                ->preserveFilenames()
                ->visibility('public')
                ->maxSize(2048) // 2048 KB = 2 MB
                ->acceptedFileTypes(['application/pdf']),

            Forms\Components\FileUpload::make('bukti_transfer')
                ->label('Bukti Transfer')
                ->placeholder('File Berupa Bukti Bayar / Transfer Yang Berformat Jpg/Png/Pdf')
                ->helperText('Pastikan Jumlah Bayar Sesuai Dengan Invoice Tagihan Anda ‼️')
                ->directory('bukti-transfer')
                ->required()
                ->default('bayar ya')
                ->preserveFilenames()
                ->visibility('public'),

            Forms\Components\DatePicker::make('tgl_bayar')
                ->label('Tanggal Pembayaran')
                ->native(false)
                ->default(fn () => now())
                ->disabled()
                ->dehydrated(true)
                ->required(fn (string $context) => $context === 'create'),

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
                        ? '🔒 Anda belum memiliki tagihan, silahkan konfirmasi pada admin'
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
            
    
            
            Forms\Components\Textarea::make('keterangan')
                ->label('Catatan / Keterangan')
                ->placeholder('Contoh: Mau bayar tanggal 01/07/2025')
                ->rows(3)
                ->required()
                ->maxLength(500),
        ];
    }

    protected function getTableHeading(): string
    {
        return 'Data Pembayaran Anda';
    }
}
