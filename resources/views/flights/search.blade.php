@extends('layouts.app')
@section('title', 'Penerbangan Tersedia - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Summary Bar --}}
    <div class="bg-white border-b border-gray-100 shadow-sm pt-20">
        <div class="max-w-5xl mx-auto px-6 py-4">
            <div class="flex flex-wrap items-center gap-4 text-sm">

                <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl">
                    <span class="text-gray-400">✈</span>
                    <span class="font-bold text-gray-800">{{ $departure ?: 'Semua' }}</span>
                    <span class="text-gray-300">→</span>
                    <span class="font-bold text-gray-800">{{ $arrival ?: 'Semua' }}</span>
                </div>

                <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl">
                    <span class="text-gray-400">📅</span>
                    <span class="font-bold text-gray-800">{{ $date }}</span>
                </div>

                <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl">
                    <span class="text-gray-400">👥</span>
                    <span class="font-bold text-gray-800">{{ $quantity }} orang</span>
                </div>

                <a href="{{ route('home') }}"
                   class="ml-auto text-blue-600 text-sm font-medium hover:underline">
                    ← Ubah Pencarian
                </a>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-5xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold text-gray-800">Penerbangan Tersedia</h1>
            <span class="text-sm text-gray-400 bg-white px-3 py-1.5 rounded-full border border-gray-200">
                {{ $flights->count() }} penerbangan
            </span>
        </div>

        @forelse ($flights as $flight)
            @php
                $first = $flight->first_segment;
                $last  = $flight->last_segment;
                $dep   = $first?->time ? \Carbon\Carbon::parse($first->time) : null;
                $arr   = $last?->time ? \Carbon\Carbon::parse($last->time) : null;

                // Durasi: jam tiba bisa lebih kecil dari jam berangkat (tiba keesokan harinya).
                $mins = null;
                if ($dep && $arr) {
                    $mins = (int) round($dep->diffInMinutes($arr, false));
                    if ($mins < 0) {
                        $mins += 1440;
                    }
                }

                // Logo maskapai: URL penuh atau path di storage publik.
                $logo    = $flight->airline?->logo;
                $logoUrl = $logo
                    ? (\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://']) ? $logo : asset('storage/' . ltrim($logo, '/')))
                    : null;
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 hover:shadow-md transition overflow-hidden">

                {{-- Airline + Rute --}}
                <div class="px-6 pt-5 pb-4">
                    <div class="flex flex-wrap items-center gap-6">

                        {{-- Maskapai --}}
                        <div class="flex items-center gap-3 min-w-52">
                            <div class="w-16 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center p-1.5 flex-shrink-0">
                                @if ($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="Logo {{ $flight->airline->name }}" loading="lazy"
                                         class="max-h-full max-w-full object-contain">
                                @else
                                    <span class="text-xl text-blue-300">✈</span>
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-sm">{{ $flight->airline->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $flight->flight_number }}</div>
                            </div>
                        </div>

                        {{-- Rute --}}
                        <div class="flex items-center gap-4 flex-1">
                            <div class="text-center">
                                <div class="text-2xl font-extrabold text-gray-800">
                                    {{ $first?->airport?->iata_code ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $first?->airport?->city ?? '-' }}
                                </div>
                                <div class="text-sm font-bold text-blue-600 mt-0.5">
                                    {{ $dep ? $dep->format('H:i') : '-' }}
                                </div>
                            </div>

                            <div class="flex flex-col items-center flex-1 gap-1">
                                @if ($flight->transit_count === 0)
                                    <span class="text-xs text-green-600 font-semibold">Direct</span>
                                @else
                                    <span class="text-xs text-orange-600 font-semibold">Transit {{ $flight->transit_count }}x</span>
                                @endif
                                <div class="w-full flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></div>
                                    <div class="flex-1 h-px bg-gray-200"></div>
                                    <div class="text-gray-300 text-xs">✈</div>
                                    <div class="flex-1 h-px bg-gray-200"></div>
                                    <div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>
                                </div>
                                <span class="text-xs text-gray-400">
                                    {{ $mins !== null ? intdiv($mins, 60) . 'j ' . ($mins % 60) . 'm' : '' }}
                                </span>
                            </div>

                            <div class="text-center">
                                <div class="text-2xl font-extrabold text-gray-800">
                                    {{ $last?->airport?->iata_code ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $last?->airport?->city ?? '-' }}
                                </div>
                                <div class="text-sm font-bold text-blue-600 mt-0.5">
                                    {{ $arr ? $arr->format('H:i') : '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- Harga termurah --}}
                        <div class="text-right flex-shrink-0">
                            <div class="text-xs text-gray-400">Mulai dari</div>
                            <div class="text-xl font-extrabold text-blue-600">
                                Rp {{ number_format($flight->cheapest_class->price ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kelas --}}
                <div class="border-t border-gray-50 bg-gray-50/50 px-6 py-3 flex flex-wrap gap-3">
                    @foreach ($flight->classes as $class)
                        <div class="flex items-center gap-4 bg-white rounded-xl px-4 py-2.5 border border-gray-100 flex-1 min-w-44">
                            <div>
                                <div class="text-xs text-gray-400 capitalize">{{ $class->class_type }}</div>
                                <div class="font-bold text-blue-600 text-sm">
                                    Rp {{ number_format($class->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('flight.choose-tier', [
                                    'flight'   => $flight->id,
                                    'date'     => $date,
                                    'quantity' => $quantity,
                                ]) }}"
                               class="ml-auto bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap">
                                Pilih →
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
                <div class="text-5xl mb-4">✈️</div>
                <h3 class="font-bold text-gray-700 mb-2">Tidak ada penerbangan ditemukan</h3>
                <p class="text-gray-400 text-sm mb-6">Coba ubah tanggal atau bandara keberangkatan</p>
                <a href="{{ route('home') }}"
                   class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition">
                    Cari Ulang
                </a>
            </div>
        @endforelse

    </div>
</div>

@endsection