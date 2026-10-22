@extends('layouts.app')
@section('title', 'Testimoni - Garuda')
@section('content')

<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-blue-200 to-indigo-700 pt-28 pb-20 px-6 text-center">
        <h1 class="text-4xl font-extrabold text-white mb-2">Testimoni Pelanggan</h1>
        <p class="text-blue-200">Apa kata mereka tentang pengalaman terbang bersama Garuda</p>
    </div>

    <div class="max-w-6xl mx-auto px-6 -mt-8 pb-16">

        {{-- Testimoni Grid --}}
        @if ($testimonials->count() > 0)
            <div class="grid md:grid-cols-3 gap-5 mb-12">
                @foreach ($testimonials as $t)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition flex flex-col">

                        {{-- Bintang --}}
                        <div class="flex gap-0.5 mb-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="text-{{ $i <= $t->rating ? 'yellow' : 'gray' }}-{{ $i <= $t->rating ? '400' : '200' }} text-xl">★</span>
                            @endfor
                        </div>

                        {{-- Pesan --}}
                        <p class="text-gray-600 text-sm leading-relaxed flex-1 italic">
                            "{{ $t->message }}"
                        </p>

                        {{-- Info --}}
                        <div class="flex items-center gap-3 pt-4 mt-4 border-t border-gray-50">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($t->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-sm text-gray-800">{{ $t->name }}</div>
                                @if ($t->origin_city)
                                    <div class="text-xs text-gray-400">📍 {{ $t->origin_city }}</div>
                                @endif
                            </div>
                            <div class="ml-auto text-xs text-gray-300">
                                {{ $t->created_at->format('M Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center mb-12">
                <div class="text-5xl mb-3">💬</div>
                <p class="font-semibold text-gray-600 mb-1">Belum ada testimoni</p>
                <p class="text-sm text-gray-400">Jadilah yang pertama berbagi pengalaman!</p>
            </div>
        @endif

        {{-- Form Submit --}}
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

                <div class="text-center mb-6">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3">✍️</div>
                    <h2 class="font-bold text-xl text-gray-800">Bagikan Pengalamanmu</h2>
                    <p class="text-gray-400 text-sm mt-1">Testimoni akan ditampilkan setelah disetujui admin</p>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm text-center">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('testimonials.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600 mb-1.5 block">Nama <span class="text-red-600">*</span></label>
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
                            <label class="text-sm font-medium text-gray-600 mb-1.5 block">Kota Asal</label>
                            <input type="text" name="origin_city" value="{{ old('origin_city') }}"
                                   placeholder="Cth: Jakarta, Bali..."
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600 mb-1.5 block">Rating <span class="text-red-600">*</span></label>
                            <div x-data="{ rating: {{ old('rating', 5) }} }" class="flex gap-2 pt-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            @click="rating = {{ $i }}"
                                            :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"
                                            class="text-3xl transition hover:scale-110">★</button>
                                @endfor
                                <input type="hidden" name="rating" :value="rating">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600 mb-1.5 block">Pesan <span class="text-red-600">*</span></label>
                        <textarea name="message" rows="4" required
                                  placeholder="Ceritakan pengalamanmu terbang bersama Garuda..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('message') }}</textarea>
                        <div class="text-xs text-gray-400 mt-1">Minimal 10 karakter</div>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                        Kirim Testimoni →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection