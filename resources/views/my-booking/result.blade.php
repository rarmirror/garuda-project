@extends('layouts.app')
@section('title', 'Detail Booking - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-200 to-indigo-700 pt-28 pb-16 px-6">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <div>
                <a href="{{ route('my-booking.index') }}"
                   class="text-white/70 hover:text-white text-sm mb-2 inline-flex items-center gap-1 transition">
                    ← Cari Booking Lain
                </a>
                <h1 class="text-3xl font-extrabold text-white">Detail Booking</h1>
            </div>
            <a href="{{ route('my-booking.download', $transaction->id) }}"
               class="bg-white text-blue-600 font-semibold px-5 py-3 rounded-xl text-sm hover:bg-blue-50 transition flex items-center gap-2 shadow">
                📄 Download E-Tiket
            </a>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-6 -mt-6 pb-16 space-y-4">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 text-sm flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('cancel'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-sm">
                {{ $errors->first('cancel') }}
            </div>
        @endif

        {{-- Status Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-400 mb-1">Kode Booking</div>
                    <div class="text-2xl font-extrabold text-blue-600">{{ $transaction->code }}</div>
                </div>
                @php
                    $statusColor = match($transaction->payment_status) {
                        'paid'    => 'bg-green-100 text-green-700 border-green-200',
                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        default   => 'bg-red-100 text-red-700 border-red-200',
                    };
                    $statusLabel = match($transaction->payment_status) {
                        'paid'    => '✅ Lunas',
                        'pending' => '⏳ Menunggu Pembayaran',
                        default   => '❌ Dibatalkan',
                    };
                @endphp
                <span class="px-4 py-2 rounded-full text-sm font-bold border {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        {{-- Penerbangan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                <span class="text-lg">✈️</span> Informasi Penerbangan
            </h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-xs text-gray-400 mb-1">Maskapai</div>
                    <div class="font-semibold">{{ $transaction->flight->airline->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">No. Penerbangan</div>
                    <div class="font-semibold">{{ $transaction->flight->flight_number ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Keberangkatan</div>
                    <div class="font-semibold">
                        {{ $transaction->flight->segments->first()?->airport?->name ?? '-' }}
                        ({{ $transaction->flight->segments->first()?->airport?->iata_code ?? '-' }})
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ $transaction->flight->segments->first()?->time
                            ? \Carbon\Carbon::parse($transaction->flight->segments->first()->time)->format('d M Y, H:i')
                            : '-' }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Kedatangan</div>
                    <div class="font-semibold">
                        {{ $transaction->flight->segments->last()?->airport?->name ?? '-' }}
                        ({{ $transaction->flight->segments->last()?->airport?->iata_code ?? '-' }})
                    </div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        {{ $transaction->flight->segments->last()?->time
                            ? \Carbon\Carbon::parse($transaction->flight->segments->last()->time)->format('d M Y, H:i')
                            : '-' }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Kelas</div>
                    <div class="font-semibold capitalize">{{ $transaction->flightClass->class_type ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Penumpang</div>
                    <div class="font-semibold">{{ $transaction->number_of_passengers }} orang</div>
                </div>
            </div>
        </div>

        {{-- Pemesan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                <span class="text-lg">👤</span> Informasi Pemesan
            </h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-xs text-gray-400 mb-1">Nama</div>
                    <div class="font-semibold">{{ $transaction->name }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Email</div>
                    <div class="font-semibold">{{ $transaction->email }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Telepon</div>
                    <div class="font-semibold">{{ $transaction->phone }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Kode Promo</div>
                    <div class="font-semibold">{{ $transaction->promo?->code ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Penumpang --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                <span class="text-lg">🧳</span> Daftar Penumpang
            </h2>
            <div class="space-y-3">
                @foreach ($transaction->passengers as $i => $passenger)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                                {{ $i + 1 }}
                            </span>
                            <div>
                                <div class="font-semibold text-sm">{{ $passenger->name }}</div>
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($passenger->date_of_birth)->format('d M Y') }}
                                    · {{ $passenger->nationality }}
                                </div>
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                            Kursi {{ $passenger->flightSeat?->name ?? '-' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Harga --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
                <span class="text-lg">💰</span> Rincian Harga
            </h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ($transaction->promo)
                    <div class="flex justify-between text-green-600">
                        <span>Diskon ({{ $transaction->promo->code }})</span>
                        <span>- Rp {{ number_format($transaction->subtotal - $transaction->grandtotal, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-base pt-3 border-t">
                    <span>Grand Total</span>
                    <span class="text-blue-600 text-xl">Rp {{ number_format($transaction->grandtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Cancel --}}
        @if ($transaction->payment_status === 'pending')
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
                <h2 class="font-bold text-red-600 mb-2 flex items-center gap-2">
                    <span>⚠️</span> Batalkan Booking
                </h2>
                <p class="text-sm text-gray-500 mb-4">
                    Pembatalan hanya bisa dilakukan selama status masih <strong>Menunggu Pembayaran</strong>. Kursi akan dikembalikan ke sistem.
                </p>
                <form method="POST" action="{{ route('my-booking.cancel', $transaction->id) }}"
                      onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                    @csrf
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-xl transition text-sm">
                        Batalkan Booking
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>

@endsection