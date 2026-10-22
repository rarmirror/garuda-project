<?php

namespace App\Http\Controllers;

use App\Models\FlightSeat;
use App\Models\HotelBooking;
use App\Models\PromoCode;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    /**
     * Penerima tunggal notifikasi Midtrans untuk flight dan hotel.
     * Dashboard Midtrans hanya punya satu Payment Notification URL, jadi produknya
     * ditentukan dari order_id, bukan dari URL yang dipanggil.
     */
    public function handle(Request $request)
    {
        // Tombol "Test notification URL" di dashboard mengirim data palsu yang tidak
        // punya transaksi asli di Midtrans, jadi cukup dijawab 200.
        if (str_starts_with((string) $request->input('order_id'), 'payment_notif_test_')) {
            return response()->json(['status' => 'test notification ignored']);
        }

        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        // Notification memverifikasi payload dengan menanyakan status asli ke API Midtrans.
        // Kalau API-nya gagal dihubungi, exception dibiarkan naik (respons 500) agar Midtrans mengirim ulang.
        $notification = new \Midtrans\Notification();

        // Dibaca langsung tanpa "??": class ini memakai __get tanpa __isset, sehingga
        // "$notification->fraud_status ?? null" selalu bernilai null.
        $status      = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        $orderId     = $notification->order_id;

        $paid   = $status === 'settlement' || ($status === 'capture' && $fraudStatus === 'accept');
        $failed = in_array($status, ['cancel', 'deny', 'expire'], true);

        if ($booking = HotelBooking::where('code', $orderId)->first()) {
            $this->updateHotel($booking, $paid, $failed);
        } elseif ($transaction = Transaction::where('code', $orderId)->first()) {
            $this->updateFlight($transaction, $paid, $failed);
        } else {
            abort(404, 'Order tidak dikenal: ' . $orderId);
        }

        return response()->json(['status' => 'ok']);
    }

    /*
     * Aturan status: paid tidak pernah diturunkan (notifikasi pending yang telat tidak
     * boleh menimpanya), dan failed hanya berlaku untuk pesanan yang masih pending.
     */

    private function updateHotel(HotelBooking $booking, bool $paid, bool $failed): void
    {
        if ($paid && $booking->payment_status !== 'paid') {
            $booking->update(['payment_status' => 'paid']);
        } elseif ($failed && $booking->payment_status === 'pending') {
            $booking->update(['payment_status' => 'failed']);
            $this->releasePromo($booking->promo_code_id);
        }
    }

    private function updateFlight(Transaction $transaction, bool $paid, bool $failed): void
    {
        $seatIds = $transaction->passengers()->pluck('flight_seat_id');

        if ($paid && $transaction->payment_status !== 'paid') {
            $transaction->update(['payment_status' => 'paid']);
            FlightSeat::whereIn('id', $seatIds)->update(['is_available' => false]);
        } elseif ($failed && $transaction->payment_status === 'pending') {
            $transaction->update(['payment_status' => 'failed']);

            // Kursi yang dikunci saat booking dilepas lagi, dan kode promo dikembalikan.
            FlightSeat::whereIn('id', $seatIds)->update(['is_available' => true]);
            $this->releasePromo($transaction->promo_code_id);
        }
    }

    private function releasePromo(?int $promoCodeId): void
    {
        if ($promoCodeId) {
            PromoCode::whereKey($promoCodeId)->update(['is_used' => false]);
        }
    }
}