@extends('layouts.app')
@section('title', $hotel->name . ' - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Hero Hotel --}}
    <div class="relative h-80 bg-gradient-to-br from-blue-500 to-indigo-600 overflow-hidden">
        @if ($hotel->image)
            <img src="{{ asset('storage/' . $hotel->image) }}"
                 alt="{{ $hotel->name }}"
                 class="w-full h-full object-cover opacity-60">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

        {{-- Back Button --}}
        <a href="{{ route('hotels.index') }}"
           class="absolute top-6 left-6 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white text-sm font-semibold px-4 py-2 rounded-full transition flex items-center gap-2">
            ← Kembali
        </a>

        {{-- Hotel Info Overlay --}}
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-yellow-400 text-xl mb-2">{{ str_repeat('⭐', $hotel->star_rating) }}</div>
                <h1 class="text-4xl font-extrabold text-white mb-1">{{ $hotel->name }}</h1>
                <div class="text-white/80 text-sm flex items-center gap-2">
                    <span>📍</span>
                    <span>{{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->country }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid md:grid-cols-3 gap-8">

            {{-- LEFT: Info Hotel --}}
            <div class="md:col-span-1 space-y-4">

                {{-- Info Check-in --}}
                <div class="bg-blue-600 rounded-2xl p-5 text-white">
                    <h3 class="font-bold mb-3 text-sm opacity-80 uppercase tracking-wide">Rincian Menginap</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="opacity-80">Check In</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($checkin)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="opacity-80">Check Out</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($checkout)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="opacity-80">Durasi</span>
                            <span class="font-semibold">{{ $nights }} malam</span>
                        </div>
                        <div class="flex justify-between border-t border-white/20 pt-2">
                            <span class="opacity-80">Tamu</span>
                            <span class="font-semibold">{{ $guests }} orang</span>
                        </div>
                    </div>
                </div>

                {{-- Kontak Hotel --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-5">
                    <h3 class="font-bold mb-3 text-gray-700">Informasi Hotel</h3>
                    <div class="space-y-2 text-sm text-gray-500">
                        @if ($hotel->phone)
                            <div class="flex items-center gap-2">
                                <span>📞</span>
                                <span>{{ $hotel->phone }}</span>
                            </div>
                        @endif
                        @if ($hotel->email)
                            <div class="flex items-center gap-2">
                                <span>📧</span>
                                <span>{{ $hotel->email }}</span>
                            </div>
                        @endif
                        <div class="flex items-start gap-2">
                            <span>📍</span>
                            <span>{{ $hotel->address }}, {{ $hotel->city }}</span>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                @if ($hotel->description)
                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <h3 class="font-bold mb-3 text-gray-700">Tentang Hotel</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $hotel->description }}</p>
                    </div>
                @endif
            </div>

            {{-- RIGHT: Daftar Kamar --}}
            <div class="md:col-span-2">
                <h2 class="text-2xl font-extrabold text-gray-800 mb-5">Pilih Kamar</h2>

                @forelse ($hotel->rooms->where('is_available', true) as $room)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-4 hover:shadow-md transition">

                        <div class="flex flex-col md:flex-row">
                            {{-- Gambar Kamar --}}
                            <div class="md:w-48 h-36 md:h-auto flex-shrink-0 bg-gradient-to-br from-indigo-400 to-blue-500 overflow-hidden">
                                @if ($room->image)
                                    <img src="{{ asset('storage/' . $room->image) }}"
                                         alt="{{ $room->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl text-white/50">🛏</div>
                                @endif
                            </div>

                            {{-- Info Kamar --}}
                            <div class="p-5 flex flex-col md:flex-row justify-between gap-4 flex-1">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-bold text-lg text-gray-800">{{ $room->name }}</h3>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium capitalize
                                            {{ match($room->type) {
                                                'standard'     => 'bg-gray-100 text-gray-600',
                                                'deluxe'       => 'bg-blue-100 text-blue-600',
                                                'suite'        => 'bg-yellow-100 text-yellow-700',
                                                'family'       => 'bg-green-100 text-green-700',
                                                'presidential' => 'bg-purple-100 text-purple-700',
                                                default        => 'bg-gray-100 text-gray-600',
                                            } }}">
                                            {{ $room->type }}
                                        </span>
                                    </div>

                                    <div class="text-sm text-gray-500 mb-3">
                                        👥 Kapasitas <strong>{{ $room->capacity }}</strong> tamu
                                        · 🚪 <strong>{{ $room->total_rooms }}</strong> kamar tersedia
                                    </div>

                                    @if ($room->description)
                                        <p class="text-xs text-gray-400 mb-3 leading-relaxed">{{ $room->description }}</p>
                                    @endif

                                    @if ($room->facilities->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($room->facilities as $facility)
                                                <span class="text-xs bg-gray-50 border border-gray-200 text-gray-600 px-2 py-1 rounded-full">
                                                    {{ $facility->icon }} {{ $facility->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Harga + CTA --}}
                                <div class="flex flex-col items-end justify-between gap-3 min-w-40">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-400">per malam</div>
                                        <div class="text-2xl font-extrabold text-blue-600">
                                            Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Total <strong class="text-gray-700">{{ $nights }} malam</strong>
                                        </div>
                                        <div class="text-lg font-bold text-gray-800">
                                            Rp {{ number_format($room->price_per_night * $nights, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <a href="{{ route('hotel.booking.form', ['room' => $room->id, 'checkin' => $checkin, 'checkout' => $checkout, 'guests' => $guests]) }}"
                                       class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl text-sm transition shadow-sm">
                                        Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                        <div class="text-4xl mb-3">🛏</div>
                        <p class="text-gray-500">Tidak ada kamar yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection