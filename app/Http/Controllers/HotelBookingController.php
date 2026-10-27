<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use App\Models\HotelBooking;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelBookingController extends Controller
{
    public function bookingForm(Request $request, HotelRoom $room)
    {
        $room->load('hotel', 'facilities');

        $checkin  = $request->query('checkin', now()->addDay()->format('Y-m-d'));
        $checkout = $request->query('checkout', now()->addDays(2)->format('Y-m-d'));
        $guests   = $request->query('guests', 1);
        $nights   = max(1, \Carbon\Carbon::parse($checkin)->diffInDays(\Carbon\Carbon::parse($checkout)));
        $subtotal = $room->price_per_night * $nights;

        return view('hotels.booking-form', compact('room', 'checkin', 'checkout', 'guests', 'nights', 'subtotal'));
    }

    public function storeBooking(Request $request, HotelRoom $room)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:20',
            'checkin'    => 'required|date',
            'checkout'   => 'required|date|after:checkin',
            'guests'     => 'required|integer|min:1',
            'promo_code' => 'nullable|string',
            'guest_details'              => 'required|array|min:1',
            'guest_details.*.name'       => 'required|string|max:255',
            'guest_details.*.date_of_birth' => 'required|date',
            'guest_details.*.nationality'   => 'required|string|max:255',
        ]);

        $nights   = max(1, \Carbon\Carbon::parse($request->checkin)->diffInDays(\Carbon\Carbon::parse($request->checkout)));
        $subtotal = $room->price_per_night * $nights;
        $grandtotal = $subtotal;
        $promoCode  = null;

        if ($request->filled('promo_code')) {
            $promoCode = PromoCode::where('code', $request->promo_code)
                ->whereColumn('usage_count', '<', 'max_usage')
                ->where('valid_until', '>=', now())
                ->first();

            if (!$promoCode) {
                return back()->withErrors(['promo_code' => 'Kode promo tidak valid atau sudah kadaluarsa.'])->withInput();
            }

            $grandtotal = $promoCode->discount_type === 'percentage'
                ? $subtotal - ($subtotal * $promoCode->discount / 100)
                : max(0, $subtotal - $promoCode->discount);
        }

        $booking = DB::transaction(function () use ($request, $room, $promoCode, $subtotal, $grandtotal, $nights) {
            $b = HotelBooking::create([
                'code'             => 'HTL-' . str_pad(\App\Models\HotelBooking::withTrashed()->count() + 1, 3, '0', STR_PAD_LEFT),
                'hotel_id'         => $room->hotel_id,
                'hotel_room_id'    => $room->id,
                'name'             => $request->name,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'check_in'         => $request->checkin,
                'check_out'        => $request->checkout,
                'number_of_guests' => $request->guests,
                'number_of_nights' => $nights,
                'promo_code_id'    => $promoCode?->id,
                'payment_status'   => 'pending',
                'subtotal'         => $subtotal,
                'grandtotal'       => $grandtotal,
            ]);

            foreach ($request->guest_details as $guest) {
                $b->guests()->create([
                    'name'          => $guest['name'],
                    'date_of_birth' => $guest['date_of_birth'],
                    'nationality'   => $guest['nationality'],
                ]);
            }

            if ($promoCode) {
                $promoCode->increment('usage_count');
            }

            return $b;
        });

        // Midtrans Snap
        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        // Total item_details harus persis sama dengan gross_amount, jadi diskon promo
        // dicatat sebagai item berharga negatif.
        $items = [
            [
                'id'       => $booking->hotel_room_id,
                'price'    => (int) $room->price_per_night,
                'quantity' => (int) $nights,
                'name'     => 'Hotel ' . $room->hotel->name . ' - ' . $room->name . ' (' . $nights . ' malam)',
            ],
        ];

        if ($promoCode) {
            $items[] = [
                'id'       => 'PROMO-' . $promoCode->id,
                'price'    => -((int) $subtotal - (int) $grandtotal),
                'quantity' => 1,
                'name'     => 'Diskon ' . $promoCode->code,
            ];
        }

        $snapToken = \Midtrans\Snap::getSnapToken([
            'transaction_details' => [
                'order_id'     => $booking->code,
                'gross_amount' => (int) $grandtotal,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
            ],
            'item_details' => $items,
            'enabled_payments' => [
                'credit_card',
                'bca_va',
                'bni_va',
                'bri_va',
                'mandiri_va',
                'permata_va',
                'other_va',
                'gopay',
                'shopeepay',
                'qris',
            ],
            'expiry' => [
                'unit'     => 'hours',
                'duration' => 24,
            ],
        ]);

        return redirect()->route('hotel.booking.payment', $booking->id)
            ->with('snap_token', $snapToken);
    }

    public function payment(HotelBooking $booking)
    {
        $snapToken = session('snap_token');

        if (!$snapToken) {
            return redirect()->route('hotels.index')->with('error', 'Sesi pembayaran tidak ditemukan.');
        }

        $booking->load('hotel', 'room');

        return view('hotels.payment', compact('booking', 'snapToken'));
    }

    public function success(HotelBooking $booking)
    {
        $booking->load('hotel', 'room', 'guests');
        return view('hotels.success', compact('booking'));
    }

    /**
     * Notifikasi Midtrans untuk flight dan hotel diproses di satu tempat,
     * karena dashboard hanya punya satu Payment Notification URL.
     */
    public function callback(Request $request)
    {
        return app(MidtransWebhookController::class)->handle($request);
    }
}