<?php

namespace App\Filament\Pages;

use Filament\Tables;
use App\Models\Tagihan;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\DataPenghuni;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Section;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Invoice extends Page implements HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-s-printer';

    protected static string $view = 'filament.pages.invoice';
    
    protected static ?string $title = 'Cek & Cetak Tagihan';

    protected static ?string $navigationGroup = 'Pelayanan Kost';

    protected static ?int $navigationSort = 1;

    public ?int $dataPenghuniId = null;
    public ?DataPenghuni $penghuni = null;
    public int $totalTagihan = 0;
    public array $tagihanData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function updated($property): void
    {
        if ($property === 'dataPenghuniId' && $this->dataPenghuniId) {
            $this->penghuni = DataPenghuni::with('dataKamar')->find($this->dataPenghuniId);
            $tagihans = Tagihan::where('data_penghuni_id', $this->dataPenghuniId)
                ->where('status', '!=', 'lunas')
                ->orderBy('periode')
                ->get();

            $this->totalTagihan = $tagihans->sum('nominal');
            $this->tagihanData = $tagihans->toArray();
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pilih Penghuni')->schema([
                Select::make('dataPenghuniId')
                    ->label('Nama Penghuni')
                    ->options(function () {
                        // Cek role
                        $user = auth()->user();
                        if ($user->hasRole('penghuni')) {
                            $penghuni = $user->dataPenghuni;
                            if (!$penghuni) return [];

                            return [
                                $penghuni->id => $penghuni->dataKamar->nama_kamar . ' - ' . $penghuni->nama . ' - ' . $penghuni->dataKamar->lokasi
                            ];
                        }

                        // Jika admin atau role lain, tampilkan semua
                        return DataPenghuni::with(['dataKamar', 'tagihan' => function ($query) {
                                $query->where('status', '!=', 'lunas');
                            }])
                            ->get()
                            ->filter(fn ($penghuni) => $penghuni->tagihan->isNotEmpty())
                            ->mapWithKeys(function ($penghuni) {
                                return [
                                    $penghuni->id => $penghuni->dataKamar->nama_kamar . ' - ' . $penghuni->nama . ' - ' . $penghuni->dataKamar->lokasi
                                ];
                            });
                    })
                    ->default(function () {
                        $user = auth()->user();
                        if ($user->hasRole('penghuni') && $user->dataPenghuni) {
                            return $user->dataPenghuni->id;
                        }

                        return null;
                    })
                    ->searchable()
                    ->live()
                    ->required()

            ])
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->dataPenghuniId
                ? Tagihan::query()
                    ->where('data_penghuni_id', $this->dataPenghuniId)
                    ->where('status', '!=', 'lunas')
                    ->orderBy('periode')
                : Tagihan::query()->whereRaw('0 = 1'))
            ->columns([
                Tables\Columns\TextColumn::make('periode')->label('Periode')->sortable(),
                Tables\Columns\TextColumn::make('nominal')->label('Nominal')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('jatuh_tempo')->label('Jatuh Tempo')->date()->sortable(),
                Tables\Columns\TextColumn::make('catatan')->label('Catatan')->limit(30)->wrap()->toggleable(),
            ]);
    }

    public function getViewData(): array
    {
        return [
            'penghuni' => $this->penghuni,
            'totalTagihan' => $this->totalTagihan,
            'tagihanData' => $this->tagihanData,
        ];
    }

    public function printInvoice()
    {
        // Implementasi cetak bisa diatur ke PDF atau print langsung via JS
    }

    public function kirimKeWhatsApp()
{
    if (!$this->penghuni || !$this->penghuni->no_wa) return;

    $lines = [];

    $lines[] = "*ANUGRAH GROUP - STRUK TAGIHAN KOST*";
    $lines[] = "Tanggal: " . now()->format('d-m-Y');
    $lines[] = "";
    $lines[] = "*Nama:* {$this->penghuni->nama}";
    $lines[] = "*Kamar:* {$this->penghuni->dataKamar->nama_kamar}";
    $lines[] = "*Lokasi:* {$this->penghuni->dataKamar->lokasi}";
    $lines[] = "----------------------------------";
    $lines[] = "*Periode | Tempo | Nominal*";

    foreach ($this->tagihanData as $tagihan) {
        try {
            $periode = \Carbon\Carbon::createFromFormat('Y-m', $tagihan['periode'])->translatedFormat('M Y');
        } catch (\Exception $e) {
            $periode = $tagihan['periode']; // fallback
        }
    
        $tempo = \Carbon\Carbon::parse($tagihan['jatuh_tempo'])->format('d/m/Y');
        $nominal = "Rp" . number_format($tagihan['nominal'], 0, ',', '.');
        $lines[] = "{$periode} | {$tempo} | {$nominal}";
    }
    

    $lines[] = "----------------------------------";
    $lines[] = "*Total:* Rp" . number_format($this->totalTagihan, 0, ',', '.');
    $lines[] = "";
    $lines[] = "_Silakan segera melakukan pembayaran melalui sistem. Terima kasih._";

    $pesan = implode("\n", $lines);

    $url = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $this->penghuni->no_wa) . '?text=' . urlencode($pesan);

    return redirect()->away($url);
}

// public function downloadPdf()
// {
//     $penghuni = $this->penghuni;
//     $tagihans = Tagihan::where('data_penghuni_id', $penghuni->id)->where('status', '!=', 'lunas')->get();
//     $total = $tagihans->sum('nominal');

//     $pdf = Pdf::loadView('filament.pages.invoicePDF', compact('penghuni', 'tagihans', 'total'));
//     return response()->streamDownload(fn () => print($pdf->stream()), 'invoice.pdf');
// }

public function downloadPdf()
{
    $penghuni = $this->penghuni;
    $tagihans = Tagihan::where('data_penghuni_id', $penghuni->id)
        ->where('status', '!=', 'lunas')
        ->orderBy('periode') // pastikan periode urut
        ->get();

    $total = $tagihans->sum('nominal');

    $pdf = Pdf::loadView('filament.pages.invoicePDF', compact('penghuni', 'tagihans', 'total'));

    // Ambil periode paling awal & nama file yang rapi
    $periodeAwal = $tagihans->first()?->periode ?? now()->format('Y-m');
    $nama = str_replace(' ', '_', $penghuni->nama);
    $kamar = str_replace(' ', '_', $penghuni->dataKamar->nama_kamar);

    $namaFile = "{$kamar}_{$nama}_{$periodeAwal}.pdf";

    return response()->streamDownload(fn () => print($pdf->stream()), $namaFile);
}


    // public function kirimPdfKeWhatsApp()
    // {
    //     $penghuni = $this->penghuni;
    //     if (!$penghuni || !$penghuni->no_wa) return;

    //     $tagihans = \App\Models\Tagihan::where('data_penghuni_id', $penghuni->id)
    //         ->where('status', '!=', 'lunas')
    //         ->get();

    //     $total = $tagihans->sum('nominal');

    //     // ✅ Sesuaikan dengan lokasi blade kamu
    //     $pdf = Pdf::loadView('filament.pages.invoicePDF', compact('penghuni', 'tagihans', 'total'))->output();

    //     $filename = 'invoice_' . now()->timestamp . '.pdf';
    //     Storage::disk('public')->put('invoices/' . $filename, $pdf);

    //     $url = asset('storage/invoices/' . $filename);
    //     $whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $penghuni->no_wa) . '?text=' . urlencode(
    //         "*Wisma Anugrah Group*\nBerikut adalah link tagihan kost Anda dalam bentuk PDF:\n{$url}"
    //     );

    //     return redirect()->away($whatsappUrl);
    // }
}
