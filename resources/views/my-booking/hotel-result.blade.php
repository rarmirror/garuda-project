@extends('layouts.app')
@section('title', 'Detail Booking Hotel - Garuda')
@section('content')

<div class="max-w-3xl mx-auto px-6 pt-32 pb-16">

    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('my-booking.hotel.search') }}"
           class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-full">
            ← Cari Booking Lain
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->has('cancel'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
            {{ $errors->first('cancel') }}
        </div>
    @endif

    {{-- Status --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="text-xs text-gray-400 mb-1">Kode Booking</div>
                <div class="font-bold text-xl text-blue-600">{{ $booking->code }}</div>
            </div>
            @php
                $statusColor = match($booking->payment_status) {
                    'paid'    => 'bg-green-100 text-green-700',
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'failed'  => 'bg-red-100 text-red-700',
                    default   => 'bg-gray-100 text-gray-700',
                };
                $statusLabel = match($booking->payment_status) {
                    'paid'    => '✅ Lunas',
                    'pending' => '⏳ Menunggu Pembayaran',
                    'failed'  => '❌ Dibatalkan',
                    default   => $booking->payment_status,
                };
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColor }}">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    {{-- Info Hotel --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <h2 class="font-bold text-lg mb-4">🏨 Informasi Hotel</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-400 mb-1">Nama Hotel</div>
                <div class="font-semibold">{{ $booking->hotel->name }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Tipe Kamar</div>
                <div class="font-semibold">{{ $booking->room->name }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Check In</div>
                <div class="font-semibold">{{ $booking->check_in->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Check Out</div>
                <div class="font-semibold">{{ $booking->check_out->format('d M Y') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Durasi</div>
                <div class="font-semibold">{{ $booking->number_of_nights }} malam</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Jumlah Tamu</div>
                <div class="font-semibold">{{ $booking->number_of_guests }} orang</div>
            </div>
        </div>
    </div>

    {{-- Info Pemesan --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <h2 class="font-bold text-lg mb-4">👤 Informasi Pemesan</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-400 mb-1">Nama</div>
                <div class="font-semibold">{{ $booking->name }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Email</div>
                <div class="font-semibold">{{ $booking->email }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Telepon</div>
                <div class="font-semibold">{{ $booking->phone }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">Kode Promo</div>
                <div class="font-semibold">{{ $booking->promo?->code ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Daftar Tamu --}}
    @if ($booking->guests->count() > 0)
        <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
            <h2 class="font-bold text-lg mb-4">🧳 Daftar Tamu</h2>
            <div class="space-y-3">
                @foreach ($booking->guests as $i => $guest)
                    <div class="border rounded-xl p-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <div class="font-semibold">{{ $guest->name }}</div>
                            <div class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($guest->date_of_birth)->format('d M Y') }}
                                · {{ $guest->nationality }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Rincian Harga --}}
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <h2 class="font-bold text-lg mb-4">💰 Rincian Harga</h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Subtotal ({{ $booking->number_of_nights }} malam)</span>
                <span>Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span>
            </div>
            @if ($booking->promo)
                <div class="flex justify-between text-green-600">
                    <span>Diskon ({{ $booking->promo->code }})</span>
                    <span>- Rp {{ number_format($booking->subtotal - $booking->grandtotal, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-base border-t pt-2">
                <span>Grand Total</span>
                <span class="text-blue-600">Rp {{ number_format($booking->grandtotal, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Cancel --}}
    @if ($booking->payment_status === 'pending')
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="font-bold text-lg mb-2 text-red-600">⚠ Batalkan Booking</h2>
            <p class="text-sm text-gray-500 mb-4">
                Pembatalan hanya bisa dilakukan selama status masih <strong>Menunggu Pembayaran</strong>.
            </p>
            <form method="POST" action="{{ route('my-booking.hotel.cancel', $booking->id) }}"
                  onsubmit="return confirm('Yakin ingin membatalkan booking hotel ini?')">
                @csrf
                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-xl transition">
                    Batalkan Booking Hotel
                </button>
            </form>
        </div>
    @endif

</div>
@endsection