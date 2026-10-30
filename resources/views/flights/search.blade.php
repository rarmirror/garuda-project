@extends('layouts.app')
@section('title', 'Penerbangan Tersedia - Garuda')
@section('content')

@php
    // Siapkan data flight sebagai JSON untuk Alpine.js
    $flightsJson = $flights->map(function ($flight) use ($date, $quantity) {
        $first = $flight->first_segment;
        $last  = $flight->last_segment;
        $dep   = $first?->time ? \Carbon\Carbon::parse($first->time) : null;
        $arr   = $last?->time  ? \Carbon\Carbon::parse($last->time)  : null;

        $durationMins = null;
        if ($dep && $arr) {
            $durationMins = (int) round($dep->diffInMinutes($arr, false));
            if ($durationMins < 0) $durationMins += 1440;
        }

        $logo    = $flight->airline?->logo;
        $logoUrl = $logo
            ? (\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://']) ? $logo : asset('storage/' . ltrim($logo, '/')))
            : null;

        $classes = $flight->classes->sortBy('price')->map(fn($c) => [
            'id'         => $c->id,
            'type'       => $c->class_type,
            'price'      => $c->price,
            'choose_url' => route('flight.choose-tier', ['flight' => $flight->id, 'date' => $date, 'quantity' => $quantity]),
        ])->values()->toArray();

        return [
            'id'             => $flight->id,
            'airline_name'   => $flight->airline->name ?? '-',
            'airline_logo'   => $logoUrl,
            'flight_number'  => $flight->flight_number,
            'dep_iata'       => $first?->airport?->iata_code ?? '-',
            'dep_city'       => $first?->airport?->city ?? '-',
            'dep_time'       => $dep ? $dep->format('H:i') : '-',
            'dep_minutes'    => $dep ? $dep->hour * 60 + $dep->minute : 9999,
            'arr_iata'       => $last?->airport?->iata_code ?? '-',
            'arr_city'       => $last?->airport?->city ?? '-',
            'arr_time'       => $arr ? $arr->format('H:i') : '-',
            'transit_count'  => $flight->transit_count,
            'duration_mins'  => $durationMins ?? 9999,
            'cheapest_price' => $flight->cheapest_class?->price ?? 0,
            'classes'        => $classes,
        ];
    })->values()->toArray();

    // Daftar maskapai unik
    $airlines = $flights->pluck('airline.name')->filter()->unique()->sort()->values()->toArray();
@endphp

<div class="min-h-screen bg-gray-50"
     x-data="flightSearch({{ json_encode($flightsJson) }}, {{ json_encode($airlines) }})"
     x-init="init()">

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

        {{-- Header + Sort --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold text-gray-800">Penerbangan Tersedia</h1>
                <span class="text-sm text-gray-400 bg-white px-3 py-1.5 rounded-full border border-gray-200"
                      x-text="filtered.length + ' penerbangan'"></span>
            </div>

            {{-- Sort Buttons --}}
            <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-400 text-xs">Urutkan:</span>
                <button @click="setSort('termurah')"
                        :class="sortBy === 'termurah' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                        class="px-3 py-1.5 rounded-lg font-semibold transition text-xs">
                    💰 Termurah
                </button>
                <button @click="setSort('tercepat')"
                        :class="sortBy === 'tercepat' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                        class="px-3 py-1.5 rounded-lg font-semibold transition text-xs">
                    🕐 Terdahulu
                </button>
                <button @click="setSort('terpendek')"
                        :class="sortBy === 'terpendek' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                        class="px-3 py-1.5 rounded-lg font-semibold transition text-xs">
                    ⚡ Terpendek
                </button>
            </div>
        </div>

        <div class="flex gap-6">

            {{-- Filter Sidebar --}}
            <div class="w-56 flex-shrink-0 hidden lg:block">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-24">
                    <h3 class="font-bold text-gray-700 mb-4 text-sm">Filter</h3>

                    {{-- Jenis Penerbangan --}}
                    <div class="mb-5">
                        <div class="text-xs text-gray-400 uppercase font-semibold mb-2">Jenis Penerbangan</div>
                        <label class="flex items-center gap-2 cursor-pointer mb-2">
                            <input type="checkbox" x-model="filterDirect" class="rounded text-blue-600">
                            <span class="text-sm text-gray-700">Langsung (Direct)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="filterTransit" class="rounded text-blue-600">
                            <span class="text-sm text-gray-700">Transit</span>
                        </label>
                    </div>

                    {{-- Maskapai --}}
                    <template x-if="allAirlines.length > 0">
                        <div class="mb-5">
                            <div class="text-xs text-gray-400 uppercase font-semibold mb-2">Maskapai</div>
                            <template x-for="airline in allAirlines" :key="airline">
                                <label class="flex items-center gap-2 cursor-pointer mb-2">
                                    <input type="checkbox"
                                           :value="airline"
                                           x-model="selectedAirlines"
                                           class="rounded text-blue-600">
                                    <span class="text-sm text-gray-700" x-text="airline"></span>
                                </label>
                            </template>
                        </div>
                    </template>

                    {{-- Harga Maks --}}
                    <div class="mb-4">
                        <div class="text-xs text-gray-400 uppercase font-semibold mb-2">Harga Maks</div>
                        <input type="range"
                               x-model="maxPrice"
                               :min="minPriceAll"
                               :max="maxPriceAll"
                               step="50000"
                               class="w-full accent-blue-600">
                        <div class="text-xs text-blue-600 font-semibold mt-1"
                             x-text="'Rp ' + Number(maxPrice).toLocaleString('id-ID')"></div>
                    </div>

                    {{-- Reset --}}
                    <button @click="resetFilters()"
                            class="w-full text-xs text-gray-400 hover:text-red-500 transition mt-1">
                        Reset Filter
                    </button>
                </div>
            </div>

            {{-- Flight List --}}
            <div class="flex-1 min-w-0">

                {{-- Mobile Filter Toggle --}}
                <div class="lg:hidden mb-4">
                    <button @click="showMobileFilter = !showMobileFilter"
                            class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-700 flex items-center justify-between">
                        <span>🔧 Filter & Sort</span>
                        <span x-text="showMobileFilter ? '▲' : '▼'"></span>
                    </button>

                    <div x-show="showMobileFilter" x-transition class="bg-white rounded-xl border border-gray-200 mt-2 p-4 grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-400 uppercase font-semibold mb-2">Jenis</div>
                            <label class="flex items-center gap-2 text-sm mb-1">
                                <input type="checkbox" x-model="filterDirect" class="rounded text-blue-600">
                                Direct
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" x-model="filterTransit" class="rounded text-blue-600">
                                Transit
                            </label>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 uppercase font-semibold mb-2">Harga Maks</div>
                            <input type="range" x-model="maxPrice" :min="minPriceAll" :max="maxPriceAll" step="50000" class="w-full accent-blue-600">
                            <div class="text-xs text-blue-600 font-semibold mt-1" x-text="'Rp ' + Number(maxPrice).toLocaleString('id-ID')"></div>
                        </div>
                    </div>
                </div>

                {{-- No results --}}
                <div x-show="filtered.length === 0" class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="font-bold text-gray-700 mb-2">Tidak ada penerbangan sesuai filter</h3>
                    <p class="text-gray-400 text-sm mb-4">Coba ubah filter atau hapus beberapa kriteria</p>
                    <button @click="resetFilters()"
                            class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition text-sm">
                        Reset Filter
                    </button>
                </div>

                {{-- Flight Cards --}}
                <template x-for="flight in filtered" :key="flight.id">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 hover:shadow-md transition overflow-hidden">

                        {{-- Airline + Rute --}}
                        <div class="px-6 pt-5 pb-4">
                            <div class="flex flex-wrap items-center gap-6">

                                {{-- Maskapai --}}
                                <div class="flex items-center gap-3 min-w-52">
                                    <div class="w-16 h-12 rounded-xl bg-white border border-gray-100 flex items-center justify-center p-1.5 flex-shrink-0">
                                        <template x-if="flight.airline_logo">
                                            <img :src="flight.airline_logo"
                                                 :alt="'Logo ' + flight.airline_name"
                                                 loading="lazy"
                                                 class="max-h-full max-w-full object-contain">
                                        </template>
                                        <template x-if="!flight.airline_logo">
                                            <span class="text-xl text-blue-300">✈</span>
                                        </template>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-sm" x-text="flight.airline_name"></div>
                                        <div class="text-xs text-gray-400" x-text="flight.flight_number"></div>
                                    </div>
                                </div>

                                {{-- Rute --}}
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="text-center">
                                        <div class="text-2xl font-extrabold text-gray-800" x-text="flight.dep_iata"></div>
                                        <div class="text-xs text-gray-400" x-text="flight.dep_city"></div>
                                        <div class="text-sm font-bold text-blue-600 mt-0.5" x-text="flight.dep_time"></div>
                                    </div>

                                    <div class="flex flex-col items-center flex-1 gap-1">
                                        <span class="text-xs font-semibold"
                                              :class="flight.transit_count === 0 ? 'text-green-600' : 'text-orange-600'"
                                              x-text="flight.transit_count === 0 ? 'Direct' : 'Transit ' + flight.transit_count + 'x'"></span>
                                        <div class="w-full flex items-center gap-1">
                                            <div class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></div>
                                            <div class="flex-1 h-px bg-gray-200"></div>
                                            <div class="text-gray-300 text-xs">✈</div>
                                            <div class="flex-1 h-px bg-gray-200"></div>
                                            <div class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></div>
                                        </div>
                                        <span class="text-xs text-gray-400"
                                              x-text="flight.duration_mins < 9999 ? Math.floor(flight.duration_mins/60) + 'j ' + (flight.duration_mins%60) + 'm' : ''"></span>
                                    </div>

                                    <div class="text-center">
                                        <div class="text-2xl font-extrabold text-gray-800" x-text="flight.arr_iata"></div>
                                        <div class="text-xs text-gray-400" x-text="flight.arr_city"></div>
                                        <div class="text-sm font-bold text-blue-600 mt-0.5" x-text="flight.arr_time"></div>
                                    </div>
                                </div>

                                {{-- Harga --}}
                                <div class="text-right flex-shrink-0">
                                    <div class="text-xs text-gray-400">Mulai dari</div>
                                    <div class="text-xl font-extrabold text-blue-600"
                                         x-text="'Rp ' + Number(flight.cheapest_price).toLocaleString('id-ID')"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Kelas --}}
                        <div class="border-t border-gray-50 bg-gray-50/50 px-6 py-3 flex flex-wrap gap-3">
                            <template x-for="cls in flight.classes" :key="cls.id">
                                <div class="flex items-center gap-4 bg-white rounded-xl px-4 py-2.5 border border-gray-100 flex-1 min-w-44">
                                    <div>
                                        <div class="text-xs text-gray-400 capitalize" x-text="cls.type"></div>
                                        <div class="font-bold text-blue-600 text-sm"
                                             x-text="'Rp ' + Number(cls.price).toLocaleString('id-ID')"></div>
                                    </div>
                                    <a :href="cls.choose_url"
                                       class="ml-auto bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition whitespace-nowrap">
                                        Pilih →
                                    </a>
                                </div>
                            </template>
                        </div>

                    </div>
                </template>

            </div>
        </div>

    </div>
</div>

<script>
function flightSearch(allFlights, allAirlines) {
    return {
        flights: allFlights,
        allAirlines: allAirlines,
        sortBy: 'default',
        filterDirect: false,
        filterTransit: false,
        selectedAirlines: [],
        maxPrice: 0,
        minPriceAll: 0,
        maxPriceAll: 0,
        showMobileFilter: false,

        init() {
            const prices = this.flights.map(f => f.cheapest_price).filter(p => p > 0);
            this.minPriceAll = prices.length ? Math.min(...prices) : 0;
            this.maxPriceAll = prices.length ? Math.max(...prices) : 10000000;
            this.maxPrice    = this.maxPriceAll;
        },

        setSort(s) {
            this.sortBy = s;
        },

        resetFilters() {
            this.sortBy          = 'default';
            this.filterDirect    = false;
            this.filterTransit   = false;
            this.selectedAirlines = [];
            this.maxPrice        = this.maxPriceAll;
            this.showMobileFilter = false;
        },

        get filtered() {
            let result = [...this.flights];

            // Filter jenis penerbangan
            if (this.filterDirect && !this.filterTransit) {
                result = result.filter(f => f.transit_count === 0);
            } else if (this.filterTransit && !this.filterDirect) {
                result = result.filter(f => f.transit_count > 0);
            }

            // Filter maskapai
            if (this.selectedAirlines.length > 0) {
                result = result.filter(f => this.selectedAirlines.includes(f.airline_name));
            }

            // Filter harga maks
            result = result.filter(f => f.cheapest_price <= Number(this.maxPrice));

            // Sorting
            if (this.sortBy === 'termurah') {
                result.sort((a, b) => a.cheapest_price - b.cheapest_price);
            } else if (this.sortBy === 'tercepat') {
                result.sort((a, b) => a.dep_minutes - b.dep_minutes);
            } else if (this.sortBy === 'terpendek') {
                result.sort((a, b) => a.duration_mins - b.duration_mins);
            }

            return result;
        }
    };
}
</script>

@endsection
