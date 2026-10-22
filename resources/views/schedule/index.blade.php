@extends('layouts.app')
@section('title', 'Jadwal Penerbangan - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-200 to-indigo-700 pt-28 pb-20 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-extrabold text-white mb-2">Jadwal Penerbangan</h1>
            <p class="text-blue-200">Semua penerbangan yang tersedia dari berbagai maskapai</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 -mt-8 pb-16">

        {{-- Filter --}}
        <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('schedule.index') }}"
                  class="grid md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">✈ Dari</label>
                    <select name="from"
                            class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 py-2 text-sm font-medium bg-transparent">
                        <option value="">Semua Bandara</option>
                        @foreach ($airports as $airport)
                            <option value="{{ $airport->iata_code }}"
                                {{ $from === $airport->iata_code ? 'selected' : '' }}>
                                {{ $airport->iata_code }} — {{ $airport->city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">🛬 Ke</label>
                    <select name="to"
                            class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 py-2 text-sm font-medium bg-transparent">
                        <option value="">Semua Bandara</option>
                        @foreach ($airports as $airport)
                            <option value="{{ $airport->iata_code }}"
                                {{ $to === $airport->iata_code ? 'selected' : '' }}>
                                {{ $airport->iata_code }} — {{ $airport->city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">🏢 Maskapai</label>
                    <input type="text" name="airline" value="{{ $airline }}"
                           placeholder="Cth: Garuda, Lion Air..."
                           class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 py-2 text-sm font-medium bg-transparent">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                        Filter
                    </button>
                    <a href="{{ route('schedule.index') }}"
                       class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Count --}}
        <div class="flex items-center justify-between mb-4">
            <div class="text-sm text-gray-500">
                Menampilkan <span class="font-bold text-gray-800">{{ $flights->count() }}</span> penerbangan
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Maskapai</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">No. Penerbangan</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Rute</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Keberangkatan</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Kedatangan</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Tipe</th>
                            <th class="text-left px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Harga Mulai</th>
                            <th class="text-center px-5 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($flights as $flight)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-800">{{ $flight->airline->name ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="bg-blue-50 text-blue-700 font-bold text-xs px-2.5 py-1.5 rounded-lg">
                                        {{ $flight->flight_number }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 font-bold text-gray-800">
                                        <span>{{ $flight->first_segment?->airport?->iata_code ?? '-' }}</span>
                                        <span class="text-gray-300 text-lg">→</span>
                                        <span>{{ $flight->last_segment?->airport?->iata_code ?? '-' }}</span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ $flight->first_segment?->airport?->city ?? '-' }}
                                        → {{ $flight->last_segment?->airport?->city ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $flight->first_segment?->time
                                        ? \Carbon\Carbon::parse($flight->first_segment->time)->format('d M Y')
                                        : '-' }}
                                    <div class="text-xs font-bold text-blue-600">
                                        {{ $flight->first_segment?->time
                                            ? \Carbon\Carbon::parse($flight->first_segment->time)->format('H:i')
                                            : '' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $flight->last_segment?->time
                                        ? \Carbon\Carbon::parse($flight->last_segment->time)->format('d M Y')
                                        : '-' }}
                                    <div class="text-xs font-bold text-blue-600">
                                        {{ $flight->last_segment?->time
                                            ? \Carbon\Carbon::parse($flight->last_segment->time)->format('H:i')
                                            : '' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($flight->transit_count === 0)
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Direct</span>
                                    @else
                                        <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">Transit {{ $flight->transit_count }}x</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-bold text-blue-600">
                                    Rp {{ number_format($flight->cheapest_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('flight.choose-tier', [
                                            'flight'   => $flight->id,
                                            'date'     => $flight->first_segment?->time
                                                ? \Carbon\Carbon::parse($flight->first_segment->time)->format('Y-m-d')
                                                : now()->format('Y-m-d'),
                                            'quantity' => 1,
                                        ]) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                                        Pesan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center text-gray-400">
                                    <div class="text-4xl mb-2">✈️</div>
                                    <div>Tidak ada penerbangan yang ditemukan</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection