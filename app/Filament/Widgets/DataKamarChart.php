<?php

namespace App\Filament\Widgets;

use App\Models\DataKamar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DataKamarChart extends ChartWidget
{
    protected static ?string $heading = 'Data KamarK Kost Berdasarkan Status';
    protected static ?int $sort = 4;
    
    protected function getData(): array
    {
        // Ambil jumlah tagihan berdasarkan status
        $data = DataKamar::select('status_kamar', DB::raw('count(*) as total'))
            ->groupBy('status_kamar')
            ->pluck('total', 'status_kamar');

        // Pastikan urutan label & warna konsisten
        $statusLabels = [
            'Terpakai' => 'Terpakai',
            'Kosong' => 'Kosong',
            'Renovasi' => 'Renovasi',
        ];

        $colors = [
            'Kosong' => '#f59e0b', 
            'Terpakai' => '#10b981',         
            'Renovasi' => '#ef4444',
        ];

        return [
            'datasets' => [
                [
                    'data' => collect($statusLabels)->keys()->map(fn($key) => $data[$key] ?? 0)->toArray(),
                    'backgroundColor' => collect($statusLabels)->keys()->map(fn($key) => $colors[$key])->toArray(),
                ],
            ],
            'labels' => array_values($statusLabels),
        ];
    }


    protected function getType(): string
    {
        return 'doughnut';
    }
}
