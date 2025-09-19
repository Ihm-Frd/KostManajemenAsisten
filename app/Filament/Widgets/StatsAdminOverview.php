<?php

namespace App\Filament\Widgets;

use App\Models\Tagihan;
use App\Models\DataKamar;
use App\Models\DataPenghuni;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('DataKamar', DataKamar::query()->count())
            ->description('Data Kamar Kost Anugrah Group')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
            Stat::make('DataPenghuni', DataPenghuni::query()->count())
            ->description('Data Penghuni Kost Anugrah Group')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('warning'),
            Stat::make('Tagihan', Tagihan::where('status', '!=', 'lunas')->count())
            ->description('Data Tagihan Kost Anugrah Group')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('info'),
        
        ];
    }
}
