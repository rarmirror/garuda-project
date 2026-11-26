<?php

namespace App\Filament\Pages;

use App\Models\HotelBooking;
use App\Models\Transaction;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Laporan & Export';
    protected static ?string $title = 'Laporan & Export';
    protected static ?int $navigationSort = 90;
    protected static string $view = 'filament.pages.reports';

    public ?string $from = null;
    public ?string $to = null;
    public ?string $status = null;

    protected function getViewData(): array
    {
        $flight = Transaction::query()->when($this->from, fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('created_at', '<=', $this->to));
        $hotel = HotelBooking::query()->when($this->from, fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to, fn ($q) => $q->whereDate('created_at', '<=', $this->to));

        $flightPaid = (clone $flight)->where('payment_status', 'paid');
        $hotelPaid = (clone $hotel)->where('payment_status', 'paid');

        return [
            'flightCount' => (clone $flight)->count(),
            'hotelCount' => (clone $hotel)->count(),
            'pendingCount' => (clone $flight)->where('payment_status', 'pending')->count() + (clone $hotel)->where('payment_status', 'pending')->count(),
            'failedCount' => (clone $flight)->where('payment_status', 'failed')->count() + (clone $hotel)->where('payment_status', 'failed')->count(),
            'paidCount' => $flightPaid->count() + $hotelPaid->count(),
            'revenue' => (int) $flightPaid->sum('grandtotal') + (int) $hotelPaid->sum('grandtotal'),
        ];
    }

    public function exportUrl(string $type): string
    {
        return route('admin.reports.export', array_filter([
            'type' => $type, 'from' => $this->from, 'to' => $this->to, 'status' => $this->status,
        ]));
    }
}
