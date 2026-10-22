<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Transaction::where('payment_status', 'paid')->sum('grandtotal');
        $pendingCount = Transaction::where('payment_status', 'pending')->count();
        $paidCount    = Transaction::where('payment_status', 'paid')->count();

        return [
            Stat::make('Total Penerbangan', Flight::count())
                ->description('Penerbangan aktif')
                ->descriptionIcon('heroicon-o-globe-alt')
                ->color('info'),

            Stat::make('Total Transaksi', Transaction::count())
                ->description("{$paidCount} paid · {$pendingCount} pending")
                ->descriptionIcon('heroicon-o-ticket')
                ->color('warning'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Dari transaksi paid')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Total Maskapai', Airline::count())
                ->description('Maskapai terdaftar')
                ->descriptionIcon('heroicon-o-paper-airplane')
                ->color('primary'),

            Stat::make('Total Bandara', Airport::count())
                ->description('Bandara terdaftar')
                ->descriptionIcon('heroicon-o-map-pin')
                ->color('gray'),
        ];
    }
}