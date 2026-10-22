@extends('layouts.app')
@section('title', 'My Booking - Garuda')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-200 to-indigo-700 flex items-center justify-center px-6 py-16">
    <div class="w-full max-w-lg">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                📋
            </div>
            <h1 class="text-3xl font-extrabold text-white mb-2">My Booking</h1>
            <p class="text-blue-200 text-sm">Cek status booking pesawat atau hotel kamu</p>
        </div>

        {{-- Pilihan Flight / Hotel --}}
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-white rounded-2xl p-5 text-center ring-2 ring-white">
                <div class="text-3xl mb-2">✈️</div>
                <div class="font-bold text-blue-600 text-sm">Tiket Pesawat</div>
                <div class="text-xs text-gray-400 mt-0.5">Aktif</div>
            </div>
            <a href="{{ route('my-booking.hotel.search') }}"
               class="bg-white/20 hover:bg-white/30 rounded-2xl p-5 text-center transition">
                <div class="text-3xl mb-2">🏨</div>
                <div class="font-bold text-white text-sm">Hotel</div>
                <div class="text-xs text-white/60 mt-0.5">Klik untuk cek</div>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="font-bold text-lg text-gray-800 mb-1">Cek Booking Pesawat</h2>
            <p class="text-gray-400 text-sm mb-6">Masukkan kode booking dan email pemesan</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl p-4 mb-5 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('my-booking.search') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1.5 block">Kode Booking</label>
                    <input type="text" name="code" value="{{ old('code') }}"
                           placeholder="Contoh: TRX-001"
                           required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600 mb-1.5 block">Email Pemesan</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@contoh.com"
                           required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                    Cari Booking →
                </button>
            </form>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('home') }}" class="text-white/60 hover:text-white text-sm transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

@endsection