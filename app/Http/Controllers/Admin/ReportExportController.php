<?php

namespace App\\Http\\Controllers\\Admin;

use App\\Http\\Controllers\\Controller;
use App\\Models\\HotelBooking;
use App\\Models\\Transaction;
use Illuminate\\Http\\Request;
use Symfony\\Component\\HttpFoundation\\StreamedResponse;

class ReportExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        $type = $request->query('type', 'finance');
        abort_unless(in_array($type, ['flights', 'hotels', 'finance', 'customers'], true), 404);

        $from = $request->date('from');
        $to = $request->date('to');
        $status = $request->query('status');
        abort_if($status && !in_array($status, ['paid', 'pending', 'failed'], true), 422);

        $flights = Transaction::query()->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->when($status, fn ($q) => $q->where('payment_status', $status))
            ->with(['flight', 'flightClass', 'passengers'])->orderByDesc('created_at');

        $hotels = HotelBooking::query()->when($from, fn ($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('created_at', '<=', $to))
            ->when($status, fn ($q) => $q->where('payment_status', $status))
            ->with(['hotel', 'room', 'guests'])->orderByDesc('created_at');

        $filename = 'garuda-' . $type . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($type, $flights, $hotels) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM so Microsoft Excel opens Indonesian text correctly.
            fwrite($out, "\xEF\xBB\xBF");

            if ($type === 'flights') {
                fputcsv($out, ['Tanggal', 'Kode Transaksi', 'Nama Pemesan', 'Email', 'Telepon', 'Penerbangan', 'Kelas', 'Jumlah Penumpang', 'Subtotal', 'Total', 'Status']);
                foreach ($flights->get() as $row) {
                    fputcsv($out, [$row->created_at, $row->code, $row->name, $row->email, $row->phone,
                        $row->flight?->flight_number, $row->flightClass?->class_type, $row->number_of_passengers,
                        $row->subtotal, $row->grandtotal, $row->payment_status]);
                }
            } elseif ($type === 'hotels') {
                fputcsv($out, ['Tanggal Booking', 'Kode Booking', 'Nama Pemesan', 'Email', 'Telepon', 'Hotel', 'Kamar', 'Check-in', 'Check-out', 'Tamu', 'Malam', 'Subtotal', 'Total', 'Status']);
                foreach ($hotels->get() as $row) {
                    fputcsv($out, [$row->created_at, $row->code, $row->name, $row->email, $row->phone,
                        $row->hotel?->name, $row->room?->name, $row->check_in, $row->check_out,
                        $row->number_of_guests, $row->number_of_nights, $row->subtotal, $row->grandtotal, $row->payment_status]);
                }
            } elseif ($type === 'finance') {
                fputcsv($out, ['Tanggal', 'Jenis', 'Kode', 'Nama Pemesan', 'Email', 'Nilai', 'Status Pembayaran', 'Pendapatan Terealisasi']);
                foreach ($flights->get() as $row) {
                    fputcsv($out, [$row->created_at, 'Pesawat', $row->code, $row->name, $row->email, $row->grandtotal, $row->payment_status, $row->payment_status === 'paid' ? $row->grandtotal : 0]);
                }
                foreach ($hotels->get() as $row) {
                    fputcsv($out, [$row->created_at, 'Hotel', $row->code, $row->name, $row->email, $row->grandtotal, $row->payment_status, $row->payment_status === 'paid' ? $row->grandtotal : 0]);
                }
            } else {
                $people = collect();
                foreach ($flights->get() as $row) $people->push(['name' => $row->name, 'email' => $row->email, 'phone' => $row->phone, 'orders' => 1, 'spend' => $row->payment_status === 'paid' ? (int) $row->grandtotal : 0]);
                foreach ($hotels->get() as $row) $people->push(['name' => $row->name, 'email' => $row->email, 'phone' => $row->phone, 'orders' => 1, 'spend' => $row->payment_status === 'paid' ? (int) $row->grandtotal : 0]);
                fputcsv($out, ['Nama Pelanggan', 'Email', 'Telepon', 'Jumlah Booking', 'Total Pembayaran Berstatus Paid']);
                $people->groupBy(fn ($p) => strtolower($p['email']))->each(function ($group) use ($out) {
                    $last = $group->last();
                    fputcsv($out, [$last['name'], $last['email'], $last['phone'], $group->sum('orders'), $group->sum('spend')]);
                });
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
