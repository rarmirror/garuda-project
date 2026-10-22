@extends('layouts.app')
@section('title', 'Hubungi Kami - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-200 to-indigo-700 pt-28 pb-20 px-6 text-center">
        <h1 class="text-4xl font-extrabold text-white mb-2">Hubungi Kami</h1>
        <p class="text-blue-200">Ada pertanyaan atau butuh bantuan? Kami siap membantu 24/7</p>
    </div>

    <div class="max-w-6xl mx-auto px-6 -mt-10 pb-16">
        <div class="grid md:grid-cols-3 gap-6">

            {{-- Info CS --}}
            <div class="space-y-4">

                @foreach ([
                    ['icon' => '📞', 'title' => 'Telepon', 'sub' => 'Senin–Minggu, 06.00–22.00 WIB', 'value' => '+62 21 555-1234', 'href' => 'tel:+62215551234', 'color' => 'blue'],
                    ['icon' => '📧', 'title' => 'Email', 'sub' => 'Respon dalam 1x24 jam', 'value' => 'cs@garuda.id', 'href' => 'mailto:cs@garuda.id', 'color' => 'indigo'],
                    ['icon' => '💬', 'title' => 'WhatsApp', 'sub' => 'Chat langsung dengan CS kami', 'value' => '+62 812-3456-7890', 'href' => 'https://wa.me/6281234567890', 'color' => 'green'],
                ] as $contact)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-{{ $contact['color'] }}-100 flex items-center justify-center text-2xl flex-shrink-0">
                                {{ $contact['icon'] }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">{{ $contact['title'] }}</div>
                                <div class="text-xs text-gray-400 mb-1">{{ $contact['sub'] }}</div>
                                <a href="{{ $contact['href'] }}" target="_blank"
                                   class="text-blue-600 font-semibold text-sm hover:underline">
                                    {{ $contact['value'] }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center text-2xl flex-shrink-0">
                            📍
                        </div>
                        <div>
                            <div class="font-bold text-gray-800">Kantor Kami</div>
                            <div class="text-xs text-gray-400 mb-1">Kunjungi kami langsung</div>
                            <div class="text-sm text-gray-600">
                                Jl. Sudirman No. 123<br>
                                Jakarta Pusat 10220
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="font-bold text-xl text-gray-800 mb-1">Kirim Pesan</h2>
                <p class="text-gray-400 text-sm mb-6">Isi form di bawah dan tim kami akan segera merespons</p>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm flex items-center gap-2">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" class="space-y-4">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600 mb-1.5 block">Nama Lengkap <span class="text-red-600">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 mb-1.5 block">Email <span class="text-red-600">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600 mb-1.5 block">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 mb-1.5 block">Subjek <span class="text-red-600">*</span></label>
                            <select name="subject" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih subjek...</option>
                                <option value="Pertanyaan Booking"   {{ old('subject') === 'Pertanyaan Booking' ? 'selected' : '' }}>Pertanyaan Booking</option>
                                <option value="Pembatalan Tiket"     {{ old('subject') === 'Pembatalan Tiket' ? 'selected' : '' }}>Pembatalan Tiket</option>
                                <option value="Refund"               {{ old('subject') === 'Refund' ? 'selected' : '' }}>Refund</option>
                                <option value="Keluhan"              {{ old('subject') === 'Keluhan' ? 'selected' : '' }}>Keluhan</option>
                                <option value="Lainnya"              {{ old('subject') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600 mb-1.5 block">Pesan <span class="text-red-600">*</span></label>
                        <textarea name="message" rows="5" required
                                  placeholder="Tuliskan pesan atau pertanyaan kamu di sini..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-xl transition shadow-sm">
                        Kirim Pesan →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection