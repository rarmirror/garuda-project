@extends('layouts.app')
@section('title', 'Pembayaran - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 pt-28 pb-20 px-6">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-3xl font-extrabold text-white mb-1">Selesaikan Pembayaran</h1>
            <p class="text-blue-200 text-sm">Pilih metode pembayaran untuk menyelesaikan booking tiket</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 -mt-8 pb-16">
        <div class="grid md:grid-cols-3 gap-6">

            {{-- LEFT: Booking Summary --}}
            <div class="md:col-span-1 space-y-4">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="font-bold text-gray-700 text-sm mb-4">Ringkasan Booking</h3>

                    @php
                        $logo    = $transaction->flight->airline?->logo ?? null;
                        $logoUrl = $logo
                            ? (\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://']) ? $logo : asset('storage/' . ltrim($logo, '/')))
                            : null;
                        $dep = $transaction->flight->segments->sortBy('sequence')->first();
                        $arr = $transaction->flight->segments->sortBy('sequence')->last();
                    @endphp

                    <div class="flex gap-3 mb-4 pb-4 border-b border-gray-50">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 border border-gray-100 flex items-center justify-center p-2">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}"
                                     alt="{{ $transaction->flight->airline->name }}"
                                     class="max-w-full max-h-full object-contain">
                            @else
                                <span class="text-2xl">✈️</span>
                            @endif
                        </div>
                        <div>
                            <div class="font-bold text-sm text-gray-800">{{ $transaction->flight->airline->name ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ $transaction->flight->flight_number }}</div>
                            <div class="text-xs text-blue-600 font-semibold mt-0.5 capitalize">
                                {{ $transaction->flightClass->class_type ?? 'Economy' }} Class
                            </div>
                        </div>
                    </div>

                    {{-- Rute mini --}}
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-50">
                        <div class="text-center">
                            <div class="text-xl font-extrabold text-gray-800">{{ $dep?->airport?->iata_code ?? '-' }}</div>
                            <div class="text-xs text-blue-600 font-bold">
                                {{ $dep?->time ? \Carbon\Carbon::parse($dep->time)->format('H:i') : '-' }}
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-center px-3">
                            <div class="text-gray-300 text-xs">✈</div>
                            <div class="w-full h-px bg-gray-200 my-0.5"></div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-extrabold text-gray-800">{{ $arr?->airport?->iata_code ?? '-' }}</div>
                            <div class="text-xs text-blue-600 font-bold">
                                {{ $arr?->time ? \Carbon\Carbon::parse($arr->time)->format('H:i') : '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Kode Booking</span>
                            <span class="font-bold text-blue-600">{{ $transaction->code }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Penumpang</span>
                            <span class="font-medium text-gray-700">{{ $transaction->number_of_passengers }} orang</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Pemesan</span>
                            <span class="font-medium text-gray-700">{{ $transaction->name }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-100">
                            <span class="font-bold text-gray-700">Total</span>
                            <span class="font-extrabold text-xl text-blue-600">
                                Rp {{ number_format($transaction->grandtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-2xl p-4 text-xs text-blue-600 border border-blue-100">
                    🔒 Pembayaran diproses secara aman oleh <strong>Midtrans</strong>
                </div>
            </div>

            {{-- RIGHT: Payment --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">

                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-5">
                        💳
                    </div>

                    <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Siap Membayar?</h2>
                    <p class="text-gray-400 text-sm mb-8">
                        Klik tombol di bawah untuk membuka halaman pembayaran Midtrans.<br>
                        Tersedia berbagai metode: VA, QRIS, Kartu Kredit, E-Wallet.
                    </p>

                    {{-- Metode Bayar --}}
                    <div class="flex flex-wrap justify-center gap-3 mb-8">
                        @foreach(['BCA', 'BNI', 'Mandiri', 'GoPay', 'OVO', 'QRIS'] as $method)
                            <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-full">
                                {{ $method }}
                            </span>
                        @endforeach
                    </div>

                    <button id="pay-button"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl text-lg transition shadow-lg shadow-blue-200 flex items-center justify-center gap-3">
                        <span>💳</span>
                        <span>Bayar Sekarang — Rp {{ number_format($transaction->grandtotal, 0, ',', '.') }}</span>
                    </button>

                    <p class="text-xs text-gray-300 mt-4">
                        Dengan melanjutkan pembayaran, kamu menyetujui syarat & ketentuan Garuda
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = '/booking/success/{{ $transaction->id }}';
        },
        onPending: function(result) {
            alert('Pembayaran pending, silakan selesaikan pembayaran.');
        },
        onError: function(result) {
            alert('Pembayaran gagal, silakan coba lagi.');
        },
        onClose: function() {
            alert('Kamu menutup popup sebelum menyelesaikan pembayaran.');
        }
    });
};
</script>

@endsection
