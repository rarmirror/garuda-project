@extends('layouts.app')
@section('title', 'My Booking Hotel - Garuda')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-200 to-indigo-700 flex items-center justify-center px-6 py-16">
    <div class="w-full max-w-lg">

        <div class="text-center mb-8">
            <div class="text-5xl mb-3">🏨</div>
            <h1 class="text-2xl font-extrabold">My Booking Hotel</h1>
            <p class="text-gray-500 text-sm mt-1">Masukkan kode booking dan email untuk melihat status</p>
        </div>

        {{-- Pilihan Flight / Hotel --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <a href="{{ route('my-booking.index') }}"
               class="bg-white rounded-2xl shadow-md p-6 text-center hover:shadow-lg transition group">
                <div class="text-4xl mb-2">✈️</div>
                <div class="font-bold text-gray-500 group-hover:text-blue-600">Tiket Pesawat</div>
                <div class="text-xs text-gray-400 mt-1">Cek status booking flight</div>
            </a>
            <div class="bg-blue-500 rounded-2xl shadow-md p-6 text-center">
                <div class="text-4xl mb-2">🏨</div>
                <div class="font-bold text-white">Hotel</div>
                <div class="text-xs text-blue-100 mt-1">Cek status booking hotel</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="font-bold text-lg mb-1">Cek Booking Hotel</h2>
            <p class="text-gray-400 text-sm mb-6">Masukkan kode booking dan email pemesan</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('my-booking.hotel.result') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-sm font-medium mb-1 block">Kode Booking Hotel</label>
                    <input type="text" name="code" value="{{ old('code') }}"
                           placeholder="Contoh: HTL-6A39DF57B55B7"
                           required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 uppercase">
                </div>
                <div>
                    <label class="text-sm font-medium mb-1 block">Email Pemesan</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@contoh.com"
                           required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-xl transition">
                    Cari Booking Hotel
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-gray-600">← Kembali ke Beranda</a>
        </div>
    </div>
</div>

@endsection