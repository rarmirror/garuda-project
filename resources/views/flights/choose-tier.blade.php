@extends('layouts.app')
@section('title', 'Pilih Kelas - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 pt-28 pb-16 px-6">
        <div class="max-w-7xl mx-auto">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center gap-2 text-white/80 hover:text-white text-sm mb-6 transition">
                ← Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-white mb-1">Pilih Kelas Penerbangan</h1>
            <p class="text-blue-200 text-sm">Pilih kelas yang sesuai dengan kebutuhan perjalananmu</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-6 pb-16">
        <div class="grid md:grid-cols-3 gap-6 items-start">

            {{-- LEFT: Flight Summary --}}
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="font-bold text-gray-700 mb-4 text-sm uppercase tracking-wide">Detail Penerbangan</h2>

                <div class="flex items-center justify-between mb-6">
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-gray-800">
                            {{ $firstSegment?->airport?->iata_code ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">{{ $firstSegment?->airport?->city ?? '-' }}</div>
                        <div class="text-sm font-bold text-blue-600 mt-1">
                            {{ $firstSegment?->time ? \Carbon\Carbon::parse($firstSegment->time)->format('H:i') : '-' }}
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-1 px-4">
                        <div class="text-xs text-gray-400">✈</div>
                        <div class="w-full h-px bg-gray-200 relative">
                            <div class="absolute -top-1.5 left-1/2 transform -translate-x-1/2 w-3 h-3 rounded-full bg-blue-400"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-extrabold text-gray-800">
                            {{ $lastSegment?->airport?->iata_code ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">{{ $lastSegment?->airport?->city ?? '-' }}</div>
                        <div class="text-sm font-bold text-blue-600 mt-1">
                            {{ $lastSegment?->time ? \Carbon\Carbon::parse($lastSegment->time)->format('H:i') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-2 text-sm text-gray-500">
                    <div class="flex justify-between">
                        <span>Maskapai</span>
                        <span class="font-semibold text-gray-700">{{ $flight->airline->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>No. Penerbangan</span>
                        <span class="font-semibold text-gray-700">{{ $flight->flight_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal</span>
                        <span class="font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span>Penumpang</span>
                        <span class="font-semibold text-gray-700">{{ $quantity }} orang</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Class Options --}}
            <div class="md:col-span-2 space-y-4">
                @foreach ($flight->classes as $class)
                    @php
                        $colors = match($class->class_type) {
                            'business'     => ['bg' => 'from-amber-500 to-orange-500', 'badge' => 'bg-amber-100 text-amber-700', 'btn' => 'bg-amber-500 hover:bg-amber-600'],
                            'economy'      => ['bg' => 'from-blue-500 to-indigo-500', 'badge' => 'bg-blue-100 text-blue-700', 'btn' => 'bg-blue-600 hover:bg-blue-700'],
                            'presidential' => ['bg' => 'from-purple-600 to-indigo-600', 'badge' => 'bg-purple-100 text-purple-700', 'btn' => 'bg-purple-600 hover:bg-purple-700'],
                            default        => ['bg' => 'from-gray-400 to-gray-500', 'badge' => 'bg-gray-100 text-gray-700', 'btn' => 'bg-gray-600 hover:bg-gray-700'],
                        };
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        {{-- Color Header --}}
                        <div class="bg-gradient-to-r {{ $colors['bg'] }} px-6 py-4 flex items-center justify-between">
                            <div>
                                <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full capitalize">
                                    {{ $class->class_type }} Class
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-white/80 text-xs">Harga per orang</div>
                                <div class="text-white text-2xl font-extrabold">
                                    Rp {{ number_format($class->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            {{-- Fasilitas --}}
                            @if ($class->facilities->count() > 0)
                                <div class="mb-5">
                                    <div class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-3">Fasilitas</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($class->facilities as $facility)
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-xs">✓</span>
                                                {{ $facility->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                <div class="text-sm text-gray-500">
                                    Total <strong class="text-gray-800">{{ $quantity }} penumpang</strong>
                                    <div class="text-lg font-extrabold text-gray-800 mt-0.5">
                                        Rp {{ number_format($class->price * $quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                                <a href="{{ route('flight.choose-seat', [
                                        'flight'    => $flight->id,
                                        'class'     => $class->id,
                                        'date'      => $date,
                                        'quantity'  => $quantity,
                                    ]) }}"
                                   class="{{ $colors['btn'] }} text-white font-semibold px-8 py-3 rounded-xl transition shadow-sm text-sm">
                                    Pilih Kelas Ini →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection