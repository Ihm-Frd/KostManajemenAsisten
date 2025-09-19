<?php

namespace App\Filament\Widgets;

use App\Models\DataKamar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TrendKamarChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Fasilitas Yang Banyak Dipilih';
    protected static ?int $sort = 5; // Nomor urut tampilan (ubah sesuai kebutuhan)


    protected function getData(): array
    {
        $data = DataKamar::where('status_kamar', 'Terpakai')
            ->select('fasilitas', DB::raw('count(*) as total'))
            ->groupBy('fasilitas')
            ->pluck('total', 'fasilitas');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kamar',
                    'data' => $data->values(),
                    'backgroundColor' => $this->generateColorByFasilitas($data->keys()->toArray()),
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }

    /**
     * Generate warna unik berdasarkan fasilitas (agar tetap dan tidak kembar)
     */
    protected function generateColorByFasilitas(array $fasilitasList): array
    {
        $colors = [];

        foreach ($fasilitasList as $fasilitas) {
            // Hash fasilitas → kode warna tetap
            $hash = crc32($fasilitas);
            $hex = substr(dechex($hash), -6);
            $hex = str_pad($hex, 6, '0', STR_PAD_LEFT);
            $colors[] = '#' . strtoupper($hex);
        }

        return $colors;
    }
}
