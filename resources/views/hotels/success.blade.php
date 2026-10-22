@extends('layouts.app')
@section('title', 'Booking Hotel Berhasil - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-6 py-16">
    <div class="w-full max-w-lg">

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            {{-- Top Banner --}}
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-8 py-10 text-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-4xl mx-auto mb-4 shadow-lg">
                    🎉
                </div>
                <h1 class="text-2xl font-extrabold text-white mb-1">Booking Hotel Berhasil!</h1>
                <p class="text-green-100 text-sm">Terima kasih telah memesan hotel bersama Garuda</p>
            </div>

            {{-- Kode Booking --}}
            <div class="bg-green-50 border-b border-dashed border-green-200 px-8 py-4 flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-400 mb-0.5">Kode Booking</div>
                    <div class="text-2xl font-extrabold text-blue-600 tracking-wider">{{ $booking->code }}</div>
                </div>
                @php
                    $statusColor = match($booking->payment_status) {
                        'paid'    => 'bg-green-100 text-green-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        default   => 'bg-red-100 text-red-700',
                    };
                    $statusLabel = match($booking->payment_status) {
                        'paid'    => '✅ Lunas',
                        'pending' => '⏳ Pending',
                        default   => '❌ Gagal',
                    };
                @endphp
                <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- Detail --}}
            <div class="px-8 py-6 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Hotel</span>
                    <span class="font-semibold text-gray-800">{{ $booking->hotel->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Tipe Kamar</span>
                    <span class="font-semibold text-gray-800">{{ $booking->room->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Check In</span>
                    <span class="font-semibold text-gray-800">{{ $booking->check_in->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Check Out</span>
                    <span class="font-semibold text-gray-800">{{ $booking->check_out->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Durasi</span>
                    <span class="font-semibold text-gray-800">{{ $booking->number_of_nights }} malam</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Pemesan</span>
                    <span class="font-semibold text-gray-800">{{ $booking->name }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-100">
                    <span class="font-bold text-gray-700">Grand Total</span>
                    <span class="font-extrabold text-xl text-blue-600">
                        Rp {{ number_format($booking->grandtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-8 pb-8 space-y-3">
                <a href="{{ route('my-booking.hotel.search') }}"
                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                    Lihat Detail Booking
                </a>
                <a href="{{ route('hotels.index') }}"
                   class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition">
                    Cari Hotel Lain
                </a>
                <a href="{{ route('home') }}"
                   class="block w-full text-center text-gray-400 hover:text-gray-600 text-sm py-2 transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-4">
            Simpan kode booking untuk mengecek status di <strong>My Booking</strong>
        </p>
    </div>
</div>

@endsection