<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MyBookingController extends Controller
{
    public function index()
    {
        return view('my-booking.search');
    }

    public function search(Request $request)
    {
        $request->validate([
            'code'  => 'required|string',
            'email' => 'required|email',
        ]);

        $transaction = Transaction::with([
            'flight.airline',
            'flight.segments' => fn($q) => $q->orderBy('sequence'),
            'flight.segments.airport',
            'flightClass',
            'passengers.flightSeat',
            'promo',
        ])
            ->where('code', strtoupper($request->code))
            ->where('email', $request->email)
            ->first();

        if (!$transaction) {
            return back()->withErrors([
                'code' => 'Booking tidak ditemukan. Pastikan kode booking dan email sudah benar.'
            ])->withInput();
        }

        return view('my-booking.result', compact('transaction'));
    }

    public function downloadPdf(Transaction $transaction)
    {
        $transaction->load([
            'flight.airline',
            'flight.segments' => fn($q) => $q->orderBy('sequence'),
            'flight.segments.airport',
            'flightClass',
            'passengers.flightSeat',
        ]);

        $pdf = Pdf::loadView('my-booking.ticket-pdf', compact('transaction'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("E-Tiket-{$transaction->code}.pdf");
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->payment_status === 'paid') {
            return back()->withErrors(['cancel' => 'Transaksi yang sudah dibayar tidak dapat dibatalkan.']);
        }

        if ($transaction->payment_status === 'failed') {
            return back()->withErrors(['cancel' => 'Transaksi ini sudah berstatus gagal.']);
        }

        // Kembalikan kursi ke available
        foreach ($transaction->passengers as $passenger) {
            if ($passenger->flightSeat) {
                $passenger->flightSeat->update(['is_available' => true]);
            }
        }

        $transaction->update(['payment_status' => 'failed']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
    public function hotelSearch()
    {
        return view('my-booking.hotel-search');
    }

    public function hotelResult(Request $request)
    {
        $request->validate([
            'code'  => 'required|string',
            'email' => 'required|email',
        ]);

        $booking = \App\Models\HotelBooking::with([
            'hotel',
            'room.facilities',
            'guests',
            'promo',
        ])
            ->where('code', strtoupper($request->code))
            ->where('email', $request->email)
            ->first();

        if (!$booking) {
            return back()->withErrors([
                'code' => 'Booking tidak ditemukan. Pastikan kode booking dan email sudah benar.'
            ])->withInput();
        }

        return view('my-booking.hotel-result', compact('booking'));
    }

    public function cancelHotel(\App\Models\HotelBooking $booking)
    {
        if ($booking->payment_status === 'paid') {
            return back()->withErrors(['cancel' => 'Booking yang sudah dibayar tidak dapat dibatalkan.']);
        }

        if ($booking->payment_status === 'failed') {
            return back()->withErrors(['cancel' => 'Booking ini sudah berstatus dibatalkan.']);
        }

        $booking->update(['payment_status' => 'failed']);

        return back()->with('success', 'Booking hotel berhasil dibatalkan.');
    }
}
