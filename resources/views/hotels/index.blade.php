@extends('layouts.app')
@section('title', 'Cari Hotel - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Hero Search --}}
    <div class="bg-gradient-to-br from-blue-200 to-indigo-700 pt-32 pb-20 px-6">
        <div class="max-w-4xl mx-auto text-center mb-8">
            <h1 class="text-4xl font-extrabold text-white mb-2">Temukan Hotel Terbaik</h1>
            <p class="text-white-400">Ribuan pilihan hotel untuk perjalanan nyaman</p>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-6">
            <form method="GET" action="{{ route('hotels.index') }}"
                  class="grid md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">📍 Kota</label>
                    <select name="city"
                            class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent text-sm">
                        <option value="">Semua Kota</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">📅 Check In</label>
                    <input type="date" name="checkin" value="{{ $checkin }}"
                           class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">📅 Check Out</label>
                    <input type="date" name="checkout" value="{{ $checkout }}"
                           class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-400 mb-1 block">👥 Tamu</label>
                    <input type="number" name="guests" value="{{ $guests }}" min="1"
                           class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent text-sm">
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition shadow-lg shadow-blue-200 text-sm">
                    🔍 Cari Hotel
                </button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- Filter Bintang --}}
        <div class="flex gap-2 mb-6 flex-wrap items-center">
            <span class="text-sm text-gray-500 mr-2">Filter:</span>
            <a href="{{ route('hotels.index', array_merge(request()->query(), ['star' => ''])) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition {{ !$star ? 'bg-blue-600 text-white shadow' : 'bg-white border border-gray-200 hover:border-blue-300 text-gray-600' }}">
                Semua
            </a>
            @foreach ([5, 4, 3, 2, 1] as $s)
                <a href="{{ route('hotels.index', array_merge(request()->query(), ['star' => $s])) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition {{ $star == $s ? 'bg-blue-600 text-white shadow' : 'bg-white border border-gray-200 hover:border-blue-300 text-gray-600' }}">
                    {{ str_repeat('⭐', $s) }}
                </a>
            @endforeach
            <span class="ml-auto text-sm text-gray-400">
                <span class="font-semibold text-gray-700">{{ $hotels->count() }}</span> hotel ditemukan
            </span>
        </div>

        {{-- Hotel Grid --}}
        @forelse ($hotels as $hotel)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5 overflow-hidden hover:shadow-md transition flex flex-col md:flex-row">

                {{-- Gambar --}}
                <div class="md:w-72 h-52 md:h-auto flex-shrink-0 relative overflow-hidden bg-gradient-to-br from-blue-400 to-indigo-500">
                    @if ($hotel->image)
                        <img src="{{ asset('storage/' . $hotel->image) }}"
                             alt="{{ $hotel->name }}"
                             class="w-full h-full object-cover hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-6xl text-white/50">🏨</div>
                    @endif

                    {{-- Badge bintang --}}
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg text-xs font-semibold text-yellow-600">
                        {{ str_repeat('⭐', $hotel->star_rating) }}
                    </div>
                </div>

                {{-- Info --}}
                <div class="p-6 flex flex-col justify-between flex-1">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div>
                                <h2 class="font-bold text-xl text-gray-800">{{ $hotel->name }}</h2>
                                <div class="flex items-center gap-1 text-sm text-gray-400 mt-1">
                                    <span>📍</span>
                                    <span>{{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->country }}</span>
                                </div>
                            </div>
                        </div>

                        @if ($hotel->description)
                            <p class="text-gray-500 text-sm mt-3 leading-relaxed line-clamp-2">
                                {{ $hotel->description }}
                            </p>
                        @endif

                        {{-- Fasilitas unggulan dari kamar --}}
                        @php
                            $topFacilities = $hotel->rooms
                                ->flatMap(fn($r) => $r->facilities)
                                ->unique('id')
                                ->take(5);
                        @endphp
                        @if ($topFacilities->count() > 0)
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach ($topFacilities as $f)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                        {{ $f->icon }} {{ $f->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-50">
                        <div>
                            @if ($hotel->cheapest_room)
                                <div class="text-xs text-gray-400 mb-0.5">Mulai dari</div>
                                <div class="text-2xl font-extrabold text-blue-600">
                                    Rp {{ number_format($hotel->cheapest_room, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-gray-400">/ malam</span>
                                </div>
                            @else
                                <div class="text-gray-400 text-sm">Belum ada kamar tersedia</div>
                            @endif
                        </div>
                        <a href="{{ route('hotels.show', ['slug' => $hotel->slug, 'checkin' => $checkin, 'checkout' => $checkout, 'guests' => $guests]) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow-sm text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
                <div class="text-5xl mb-4">🏨</div>
                <h3 class="font-bold text-gray-700 mb-2">Tidak ada hotel ditemukan</h3>
                <p class="text-gray-400 text-sm mb-6">Coba ubah filter atau kota pencarian</p>
                <a href="{{ route('hotels.index') }}"
                   class="bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-700 transition">
                    Reset Filter
                </a>
            </div>
        @endforelse

    </div>
</div>

@endsection