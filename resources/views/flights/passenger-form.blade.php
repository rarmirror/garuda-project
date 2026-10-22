@extends('layouts.app')
@section('title', 'Data Penumpang - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 pt-28 pb-16 px-6">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-3xl font-extrabold text-white mb-1">Data Penumpang</h1>
            <p class="text-blue-200 text-sm">Lengkapi data penumpang untuk menyelesaikan pemesanan</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 -mt-6 pb-16">

        {{-- Progress Steps --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="flex items-center justify-between text-xs text-gray-400">
                <div class="flex items-center gap-2 text-green-600 font-semibold">
                    <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">✓</span>
                    Pilih Penerbangan
                </div>
                <div class="flex-1 h-px bg-gray-200 mx-3"></div>
                <div class="flex items-center gap-2 text-green-600 font-semibold">
                    <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">✓</span>
                    Pilih Kelas
                </div>
                <div class="flex-1 h-px bg-gray-200 mx-3"></div>
                <div class="flex items-center gap-2 text-green-600 font-semibold">
                    <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">✓</span>
                    Pilih Kursi
                </div>
                <div class="flex-1 h-px bg-gray-200 mx-3"></div>
                <div class="flex items-center gap-2 text-blue-600 font-semibold">
                    <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">4</span>
                    Data Penumpang
                </div>
                <div class="flex-1 h-px bg-gray-200 mx-3"></div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">5</span>
                    Pembayaran
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-5 mb-6 text-sm">
                <div class="font-semibold mb-1">Ada kesalahan:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">

            {{-- Form --}}
            <div class="md:col-span-2 space-y-5">
                <form method="POST" action="{{ route('booking.store-transaction') }}" id="passenger-form">
                    @csrf

                    {{-- Informasi Pemesan --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="font-bold text-lg text-gray-800 mb-5 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                            Informasi Pemesan
                        </h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600 mb-1.5 block">Nama Lengkap <span class="text-red-600">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 mb-1.5 block">Email <span class="text-red-600">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 mb-1.5 block">No. Telepon <span class="text-red-600">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600 mb-1.5 block">Kode Promo</label>
                                <input type="text" name="promo_code" value="{{ old('promo_code') }}"
                                       placeholder="Opsional"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>

                    {{-- Data Penumpang --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="font-bold text-lg text-gray-800 mb-5 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">2</span>
                            Data Penumpang
                        </h2>

                        <div class="space-y-4">
                            @foreach ($seats as $i => $seat)
                                <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50">
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                            {{ $i + 1 }}
                                        </span>
                                        <span class="font-semibold text-gray-700 text-sm">Penumpang {{ $i + 1 }}</span>
                                        <span class="ml-auto bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                                            Kursi {{ $seat->name }}
                                        </span>
                                    </div>

                                    <input type="hidden" name="passengers[{{ $i }}][flight_seat_id]" value="{{ $seat->id }}">

                                    <div class="grid md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 mb-1 block">Nama Lengkap <span class="text-red-600">*</span></label>
                                            <input type="text" name="passengers[{{ $i }}][name]"
                                                   value="{{ old("passengers.$i.name") }}" required
                                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 mb-1 block">Tanggal Lahir <span class="text-red-600">*</span></label>
                                            <input type="date" name="passengers[{{ $i }}][date_of_birth]"
                                                   value="{{ old("passengers.$i.date_of_birth") }}" required
                                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 mb-1 block">Kewarganegaraan <span class="text-red-600">*</span></label>
                                            <input type="text" name="passengers[{{ $i }}][nationality]"
                                                   value="{{ old("passengers.$i.nationality", 'Indonesia') }}" required
                                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-2xl text-base transition shadow-lg shadow-blue-200">
                        Lanjut ke Pembayaran →
                    </button>
                </form>
            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="space-y-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
                    <h3 class="font-bold text-gray-700 text-sm mb-4">Ringkasan Pesanan</h3>

                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-50">
                        <div>
                            <div class="font-bold text-gray-800">{{ $flight->airline->name ?? '-' }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $firstSegment?->airport?->iata_code ?? '-' }}
                                → {{ $lastSegment?->airport?->iata_code ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1 capitalize">{{ $flightClass->class_type }} Class</div>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between text-gray-500">
                            <span>Kursi dipilih</span>
                            <span class="font-medium text-gray-700">
                                {{ $seats->pluck('name')->join(', ') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Harga/kursi</span>
                            <span class="font-medium text-gray-700">
                                Rp {{ number_format($flightClass->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Jumlah penumpang</span>
                            <span class="font-medium text-gray-700">{{ count($seats) }}</span>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-700">Total</span>
                            <span class="text-xl font-extrabold text-blue-600">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-400 mt-1 text-right">Belum termasuk promo</div>
                    </div>

                    <div class="mt-4 bg-blue-50 rounded-xl p-3 text-xs text-blue-600">
                        🔒 Pembayaran aman diproses oleh Midtrans
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection