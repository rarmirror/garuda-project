<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Transaksi per Bulan';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data  = [];
        $labels = [];
        $paid  = [];
        $pending = [];
        $failed = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $paid[] = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('payment_status', 'paid')
                ->count();

            $pending[] = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('payment_status', 'pending')
                ->count();

            $failed[] = Transaction::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('payment_status', 'failed')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Paid',
                    'data'            => $paid,
                    'backgroundColor' => '#22c55e',
                    'borderColor'     => '#22c55e',
                    'fill'            => false,
                ],
                [
                    'label'           => 'Pending',
                    'data'            => $pending,
                    'backgroundColor' => '#f59e0b',
                    'borderColor'     => '#f59e0b',
                    'fill'            => false,
                ],
                [
                    'label'           => 'Failed',
                    'data'            => $failed,
                    'backgroundColor' => '#ef4444',
                    'borderColor'     => '#ef4444',
                    'fill'            => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}