@extends('layouts.app')

@section('title', 'Garuda - Explore Magical Wonderful Worlds')

@section('content')

{{-- HERO SECTION --}}
<section class="relative min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 overflow-hidden">

    {{-- Background decorative circles --}}
    <div class="absolute top-20 right-0 w-96 h-96 bg-blue-200 rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-purple-200 rounded-full opacity-20 blur-3xl"></div>

    <<div class="max-w-7xl mx-auto px-6 pt-32 pb-12">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            {{-- Left --}}
            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full text-sm font-medium shadow-sm mb-6 border border-gray-100">
                    🏆 Top Flight Awards Fly Group Sky 500
                </span>

                <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6 text-gray-900">
                    Explore <span class="text-blue-600">Magical</span><br>
                    Wonderful Worlds
                </h1>

                <p class="text-gray-500 text-lg max-w-md mb-8 leading-relaxed">
                    Temukan penerbangan dan hotel terbaik untuk perjalananmu. Pesan tiket pesawat dan kamar hotel dengan mudah, cepat, dan aman.
                </p>

                <div class="flex gap-4 flex-wrap">
                    <a href="#search-form"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-4 rounded-2xl transition shadow-lg shadow-blue-200">
                        ✈ Cari Penerbangan
                    </a>
                    <a href="{{ route('hotels.index') }}"
                        class="bg-white hover:bg-gray-50 text-gray-800 font-semibold px-8 py-4 rounded-2xl transition shadow-md border border-gray-200">
                        🏨 Cari Hotel
                    </a>
                </div>

                {{-- Stats --}}
                <div class="flex gap-8 mt-12 flex-wrap">
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900">31+</div>
                        <div class="text-sm text-gray-500">Rute Penerbangan</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900">8+</div>
                        <div class="text-sm text-gray-500">Hotel Tersedia</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900">10+</div>
                        <div class="text-sm text-gray-500">Maskapai Partner</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold text-gray-900">20+</div>
                        <div class="text-sm text-gray-500">Kota Tujuan</div>
                    </div>
                </div>
            </div>

            {{-- Right: Plane Image --}}
            <div class="relative hidden md:block">

                {{-- Background shapes --}}
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-[3rem] opacity-15 transform rotate-3"></div>
                <div class="absolute inset-0 bg-gradient-to-tl from-purple-300 to-blue-300 rounded-[3rem] opacity-10 transform -rotate-2 scale-95"></div>

                {{-- Gambar utama --}}
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&q=80"
                    alt="Pesawat"
                    class="relative z-10 w-full rounded-[2.5rem] shadow-2xl object-cover h-[440px]">

                {{-- Gradient overlay bawah supaya blend dengan background --}}
                <div class="absolute bottom-0 left-0 right-0 h-28 bg-gradient-to-t from-indigo-50/70 to-transparent rounded-b-[2.5rem] z-20"></div>

                {{-- Badge maskapai partner --}}
                <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-30">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl px-5 py-3 shadow-lg border border-gray-100 flex items-center gap-3 whitespace-nowrap">
                        <div class="flex -space-x-2">
                            @foreach(['✈','🌏','🛫'] as $icon)
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-sm border-2 border-white">{{ $icon }}</div>
                            @endforeach
                        </div>
                        <div>
                            <div class="font-bold text-xs text-gray-800">10+ Maskapai Partner</div>
                            <div class="text-xs text-gray-400">Terpercaya & Terjangkau</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            {{-- SEARCH FORM --}}
            <div id="search-form" class="w-full mx-auto mt-12 pb-16">
                <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">

                    {{-- Tab Flight/Hotel --}}
                    <div x-data="{ tab: 'flight' }" class="mb-6">
                        <div class="flex gap-2 mb-6 bg-gray-100 p-1 rounded-xl w-fit">
                            <button @click="tab = 'flight'"
                                :class="tab === 'flight' ? 'bg-white shadow text-blue-600' : 'text-gray-500'"
                                class="px-5 py-2 rounded-lg text-sm font-semibold transition">
                                ✈ Penerbangan
                            </button>
                            <button @click="tab = 'hotel'"
                                :class="tab === 'hotel' ? 'bg-white shadow text-blue-600' : 'text-gray-500'"
                                class="px-5 py-2 rounded-lg text-sm font-semibold transition">
                                🏨 Hotel
                            </button>
                        </div>

                        {{-- Flight Search --}}
                        <div x-show="tab === 'flight'">
                            <h2 class="text-lg font-bold mb-4 text-gray-700">Cari Penerbangan</h2>
                            <form action="{{ route('flights.search') }}" method="GET"
                                class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">✈ Keberangkatan</label>
                                    <select name="departure"
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                        <option value="">Pilih Bandara</option>
                                        @foreach ($airports as $airport)
                                        <option value="{{ $airport->iata_code }}">
                                            {{ $airport->iata_code }} — {{ $airport->city }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">✈ Kedatangan</label>
                                    <select name="arrival"
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                        <option value="">Pilih Bandara</option>
                                        @foreach ($airports as $airport)
                                        <option value="{{ $airport->iata_code }}">
                                            {{ $airport->iata_code }} — {{ $airport->city }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">📅 Tanggal</label>
                                    <input type="date" name="date"
                                        value="{{ now()->addDay()->format('Y-m-d') }}"
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">👥 Penumpang</label>
                                    <input type="number" name="quantity" min="1" value="1"
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                </div>

                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl py-3 px-6 flex items-center justify-center gap-2 transition shadow-lg shadow-blue-200">
                                    🔍 Cari
                                </button>
                            </form>
                        </div>

                        {{-- Hotel Search --}}
                        <div x-show="tab === 'hotel'" style="display:none">
                            <h2 class="text-lg font-bold mb-4 text-gray-700">Cari Hotel</h2>
                            <form action="{{ route('hotels.index') }}" method="GET"
                                class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">📍 Kota</label>
                                    <input type="text" name="city" placeholder="Jakarta, Bali..."
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">📅 Check In</label>
                                    <input type="date" name="checkin"
                                        value="{{ now()->addDay()->format('Y-m-d') }}"
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">📅 Check Out</label>
                                    <input type="date" name="checkout"
                                        value="{{ now()->addDays(2)->format('Y-m-d') }}"
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">👥 Tamu</label>
                                    <input type="number" name="guests" min="1" value="1"
                                        class="w-full border-0 border-b-2 border-gray-200 focus:border-blue-500 focus:ring-0 font-semibold py-2 bg-transparent">
                                </div>

                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl py-3 px-6 flex items-center justify-center gap-2 transition shadow-lg shadow-blue-200">
                                    🔍 Cari
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</section>

{{-- KENAPA PILIH GARUDA --}}
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold mb-3">Kenapa Pilih Garuda?</h2>
        <p class="text-gray-500">Kami hadir untuk memberikan pengalaman perjalanan terbaik</p>
    </div>

    <div class="grid md:grid-cols-4 gap-6">
        @foreach ([
        ['icon' => '🔒', 'title' => 'Pembayaran Aman', 'desc' => 'Transaksi diproses secara aman melalui Midtrans dengan enkripsi SSL'],
        ['icon' => '⚡', 'title' => 'Pemesanan Cepat', 'desc' => 'Pesan tiket pesawat dan hotel dalam hitungan menit tanpa ribet'],
        ['icon' => '💰', 'title' => 'Harga Terbaik', 'desc' => 'Dapatkan harga terbaik dengan berbagai pilihan kode promo menarik'],
        ['icon' => '📞', 'title' => 'CS 24/7', 'desc' => 'Tim customer service kami siap membantu kamu kapanpun dibutuhkan'],
        ] as $feature)
        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition border border-gray-100 text-center">
            <div class="text-4xl mb-4">{{ $feature['icon'] }}</div>
            <h3 class="font-bold text-lg mb-2">{{ $feature['title'] }}</h3>
            <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- POPULAR THIS YEAR --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold mb-1">Destinasi Populer</h2>
                <p class="text-gray-500">Temukan penerbangan ke destinasi favorit</p>
            </div>
            <a href="{{ route('schedule.index') }}"
                class="text-blue-600 font-semibold text-sm hover:underline">
                Lihat Semua →
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($popularAirports as $airport)
            <a href="{{ route('flights.search', ['departure' => $airport->iata_code, 'date' => now()->addDay()->format('Y-m-d'), 'quantity' => 1]) }}"
                class="block rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition group bg-white">
                <div class="h-32 bg-gradient-to-br from-blue-400 to-purple-500 overflow-hidden relative">
                    @if ($airport->image)
                    <img src="{{ asset('storage/' . $airport->image) }}"
                        alt="{{ $airport->name }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-300 opacity-80">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-white text-4xl">✈</div>
                    @endif
                    <div class="absolute bottom-2 left-2">
                        <span class="bg-white/90 text-blue-700 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $airport->iata_code }}
                        </span>
                    </div>
                </div>
                <div class="p-3">
                    <div class="font-semibold text-sm truncate">{{ $airport->city }}</div>
                    <div class="text-xs text-gray-400 truncate">{{ $airport->country }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- HOTEL SECTION --}}
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-extrabold mb-1">Hotel Pilihan</h2>
            <p class="text-gray-500">Kamar terbaik untuk istirahat yang nyaman</p>
        </div>
        <a href="{{ route('hotels.index') }}"
            class="text-blue-600 font-semibold text-sm hover:underline">
            Lihat Semua →
        </a>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        @foreach ($featuredHotels as $hotel)
        <a href="{{ route('hotels.show', $hotel->slug) }}"
            class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition group">
            <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 overflow-hidden">
                @if ($hotel->image)
                <img src="{{ asset('storage/' . $hotel->image) }}"
                    alt="{{ $hotel->name }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                @else
                <div class="w-full h-full flex items-center justify-center text-white text-6xl">🏨</div>
                @endif
            </div>
            <div class="p-5">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <h3 class="font-bold text-base leading-tight">{{ $hotel->name }}</h3>
                    <div class="text-yellow-400 text-sm flex-shrink-0">{{ str_repeat('⭐', $hotel->star_rating) }}</div>
                </div>
                <div class="text-xs text-gray-400 mb-3">📍 {{ $hotel->city }}, {{ $hotel->country }}</div>
                @if ($hotel->cheapest_room)
                <div class="text-blue-600 font-bold">
                    Rp {{ number_format($hotel->cheapest_room, 0, ',', '.') }}
                    <span class="text-xs font-normal text-gray-400">/ malam</span>
                </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-gray-900 text-gray-400 pt-16 pb-8 mt-8">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-10 mb-10">

            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">✈</span>
                    <span class="text-white font-bold text-lg">Garuda</span>
                </div>
                <p class="text-sm leading-relaxed">Platform booking tiket pesawat dan hotel terpercaya untuk perjalananmu ke seluruh dunia.</p>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Layanan</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('flights.search') }}" class="hover:text-white transition">Tiket Pesawat</a></li>
                    <li><a href="{{ route('hotels.index') }}" class="hover:text-white transition">Hotel</a></li>
                    <li><a href="{{ route('schedule.index') }}" class="hover:text-white transition">Jadwal Penerbangan</a></li>
                    <li><a href="{{ route('my-booking.index') }}" class="hover:text-white transition">My Booking</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Informasi</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('testimonials.index') }}" class="hover:text-white transition">Testimoni</a></li>
                    <li><a href="{{ route('contact.index') }}" class="hover:text-white transition">Hubungi Kami</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Kontak</h4>
                <ul class="space-y-2 text-sm">
                    <li>📞 +62 812 345 678 910 </li>
                    <li>📧 admin@garuda.id</li>
                    <li>📍 Jl. Kramat Raya No.98, RT.2/RW.9, Kwitang, Kec. Senen, Kota Jakarta Pusat</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
            <div>&copy; {{ date('Y') }} Garuda. All rights reserved.</div>
            <div class="flex items-center gap-2 text-xs">
                <span>Powered by</span>
                <span class="text-white font-semibold">Midtrans</span>
                <span>•</span>
                <span class="text-white font-semibold">Laravel</span>
                <span>•</span>
                <span class="text-white font-semibold">Filament</span>
            </div>
        </div>
    </div>
</footer>

@endsection