<?php

namespace App\Filament\Widgets;

use App\Models\Tagihan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TagihanChart extends ChartWidget
{
    protected static ?string $heading = 'Tagihan Yang Belum Dibayarkan Berdasarkan Periode Bulan';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        // $data = Tagihan::join('data_penghunis', 'tagihans.data_penghuni_id', '=', 'data_penghunis.id')
        //     ->join('data_kamars', 'data_penghunis.data_kamar_id', '=', 'data_kamars.id')
        //     ->whereIn('tagihans.status', ['belum_dibayar', 'ditolak'])
        //     ->select('data_kamars.lokasi', DB::raw('count(*) as total'))
        //     ->groupBy('data_kamars.lokasi')
        //     ->pluck('total', 'data_kamars.lokasi');

        $data = \App\Models\Tagihan::whereIn('status', ['belum_dibayar', 'ditolak'])
        ->select('periode', DB::raw('count(*) as total'))
        ->groupBy('periode')
        ->orderBy('periode')
        ->pluck('total', 'periode');


        return [
            'datasets' => [
                [
                    'label' => 'Belum Dibayar / Ditolak',
                    'data' => $data->values(),
                    'backgroundColor' => $this->generateColorByLocation($data->keys()->toArray()),
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /**
     * Generate warna HEX yang unik dan konsisten berdasarkan nama lokasi.
     */
    protected function generateColorByLocation(array $locations): array
    {
        $colors = [];

        foreach ($locations as $lokasi) {
            // Hash lokasi → angka → ambil 6 digit hex terakhir → warna unik
            $hash = crc32($lokasi);
            $hex = substr(dechex($hash), -6);

            // Jika kurang dari 6 digit, tambahkan nol di depan
            $hex = str_pad($hex, 6, '0', STR_PAD_LEFT);

            $colors[] = '#' . strtoupper($hex);
        }

        return $colors;
    }
}
