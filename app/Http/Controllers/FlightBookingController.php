<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\FlightClass;
use App\Models\FlightSeat;
use App\Models\PromoCode;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightBookingController extends Controller
{
    public function chooseTier(Request $request, Flight $flight)
    {
        $date     = $request->query('date');
        $quantity = $request->query('quantity', 1);

        $flight->load(['airline', 'segments' => fn($q) => $q->orderBy('sequence'), 'segments.airport', 'classes']);

        $firstSegment = $flight->segments->first();
        $lastSegment  = $flight->segments->last();

        return view('flights.choose-tier', compact('flight', 'firstSegment', 'lastSegment', 'date', 'quantity'));
    }

    public function chooseSeat(Request $request, Flight $flight)
    {
        $classId  = $request->query('class');
        $date     = $request->query('date');
        $quantity = (int) $request->query('quantity', 1);

        $flightClass = FlightClass::with('facilities')->findOrFail($classId);

        $flight->load(['airline', 'segments' => fn($q) => $q->orderBy('sequence'), 'segments.airport']);

        $firstSegment = $flight->segments->first();
        $lastSegment  = $flight->segments->last();

        $seats = FlightSeat::where('flight_id', $flight->id)
            ->where('class_type', $flightClass->class_type)
            ->where('is_available', true)
            ->orderBy('row')
            ->orderBy('column')
            ->get()
            ->groupBy('row');

        return view('flights.choose-seat', compact(
            'flight',
            'flightClass',
            'firstSegment',
            'lastSegment',
            'date',
            'quantity',
            'seats'
        ));
    }

    public function storeSeats(Request $request, Flight $flight)
    {
        $request->validate([
            'class_id'    => 'required|exists:flight_classes,id',
            'date'        => 'required|date',
            'quantity'    => 'required|integer|min:1',
            'seat_ids'    => 'required|array|min:1',
            'seat_ids.*'  => 'exists:flight_seats,id',
        ]);

        if (count($request->seat_ids) != $request->quantity) {
            return back()->withErrors(['seat_ids' => 'Jumlah kursi yang dipilih harus sesuai quantity penumpang.']);
        }

        session([
            'booking' => [
                'flight_id' => $flight->id,
                'class_id'  => $request->class_id,
                'date'      => $request->date,
                'quantity'  => $request->quantity,
                'seat_ids'  => $request->seat_ids,
            ]
        ]);

        return redirect()->route('booking.passenger-form');
    }

    public function passengerForm()
    {
        $booking = session('booking');

        if (!$booking) {
            return redirect()->route('home')->with('error', 'Sesi booking tidak ditemukan, silakan ulangi pencarian.');
        }

        $flight = Flight::with(['airline', 'segments' => fn($q) => $q->orderBy('sequence'), 'segments.airport'])
            ->findOrFail($booking['flight_id']);

        $flightClass = FlightClass::findOrFail($booking['class_id']);

        $seats = FlightSeat::whereIn('id', $booking['seat_ids'])->get();

        $firstSegment = $flight->segments->first();
        $lastSegment  = $flight->segments->last();

        $subtotal = $flightClass->price * $booking['quantity'];

        return view('flights.passenger-form', compact(
            'flight',
            'flightClass',
            'firstSegment',
            'lastSegment',
            'seats',
            'booking',
            'subtotal'
        ));
    }

    public function storeTransaction(Request $request)
    {
        $booking = session('booking');

        if (!$booking) {
            return redirect()->route('home')->with('error', 'Sesi booking tidak ditemukan.');
        }

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',
            'promo_code' => 'nullable|string',
            'passengers'             => 'required|array|min:1',
            'passengers.*.name'          => 'required|string|max:255',
            'passengers.*.date_of_birth' => 'required|date',
            'passengers.*.nationality'   => 'required|string|max:255',
            'passengers.*.flight_seat_id' => 'required|exists:flight_seats,id',
        ]);

        $flightClass = FlightClass::findOrFail($booking['class_id']);
        $subtotal    = $flightClass->price * $booking['quantity'];
        $grandtotal  = $subtotal;
        $promoCode   = null;

        if ($request->filled('promo_code')) {
            $promoCode = PromoCode::where('code', $request->promo_code)
                ->whereColumn('usage_count', '<', 'max_usage')
                ->where('valid_until', '>=', now())
                ->first();

            if (!$promoCode) {
                return back()->withErrors(['promo_code' => 'Kode promo tidak valid atau sudah kedaluwarsa.'])->withInput();
            }

            $grandtotal = $promoCode->discount_type === 'percentage'
                ? $subtotal - ($subtotal * $promoCode->discount / 100)
                : max(0, $subtotal - $promoCode->discount);
        }

        $transaction = DB::transaction(function () use ($request, $booking, $promoCode, $subtotal, $grandtotal) {
            $trx = Transaction::create([
                'code'                 => 'TRX-' . str_pad(\App\Models\Transaction::withTrashed()->count() + 1, 3, '0', STR_PAD_LEFT),
                'flight_id'            => $booking['flight_id'],
                'flight_class_id'      => $booking['class_id'],
                'name'                 => $request->name,
                'email'                => $request->email,
                'phone'                => $request->phone,
                'number_of_passengers' => $booking['quantity'],
                'promo_code_id'        => $promoCode?->id,
                'payment_status'       => 'pending',
                'subtotal'             => $subtotal,
                'grandtotal'           => $grandtotal,
            ]);

            foreach ($request->passengers as $passenger) {
                $trx->passengers()->create([
                    'flight_seat_id' => $passenger['flight_seat_id'],
                    'name'           => $passenger['name'],
                    'date_of_birth'  => $passenger['date_of_birth'],
                    'nationality'    => $passenger['nationality'],
                ]);
                FlightSeat::where('id', $passenger['flight_seat_id'])->update(['is_available' => false]);
            }

            if ($promoCode) {
                $promoCode->increment('usage_count');
            }

            return $trx;
        });

        // Setup Midtrans
        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        // Total item_details harus persis sama dengan gross_amount, jadi diskon promo
        // dicatat sebagai item berharga negatif.
        $items = [
            [
                'id'       => $transaction->flight_class_id,
                'price'    => (int) $flightClass->price,
                'quantity' => (int) $booking['quantity'],
                'name'     => 'Tiket Penerbangan ' . ($flightClass->class_type ?? 'Economy') . ' - ' . $transaction->code,
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
                'order_id'     => $transaction->code,
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

        session()->forget('booking');

        return redirect()->route('booking.payment', $transaction->id)
            ->with('snap_token', $snapToken);
    }
    public function payment(Transaction $transaction)
    {
        $snapToken = session('snap_token');

        if (!$snapToken) {
            return redirect()->route('home')->with('error', 'Sesi pembayaran tidak ditemukan.');
        }

        return view('flights.payment', compact('transaction', 'snapToken'));
    }
    public function success(Transaction $transaction)
    {
        return view('flights.success', compact('transaction'));
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