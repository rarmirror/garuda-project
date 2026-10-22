@extends('layouts.app')

@section('title', 'Pembayaran - Garuda')

@section('content')

<div class="max-w-2xl mx-auto px-6 pt-32 pb-16 text-center">

    <div class="bg-white rounded-2xl shadow-md p-10">
        <div class="text-5xl mb-4">✈️</div>
        <h1 class="text-2xl font-extrabold mb-2">Selesaikan Pembayaran</h1>
        <p class="text-gray-500 mb-2">Kode Booking: <span class="font-semibold text-blue-600">{{ $transaction->code }}</span></p>
        <p class="text-gray-500 mb-8">Total: <span class="font-bold text-xl">Rp {{ number_format($transaction->grandtotal, 0, ',', '.') }}</span></p>

        <button id="pay-button"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-10 rounded-xl text-lg transition">
            Bayar Sekarang
        </button>

        <p class="text-xs text-gray-400 mt-6">Pembayaran diproses secara aman oleh Midtrans</p>
    </div>

</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = '/booking/success/{{ $transaction->id }}';
        },
        onPending: function(result) {
            alert('Pembayaran pending, silakan selesaikan pembayaran.');
        },
        onError: function(result) {
            alert('Pembayaran gagal, silakan coba lagi.');
        },
        onClose: function() {
            alert('Kamu menutup popup sebelum menyelesaikan pembayaran.');
        }
    });
};
</script>

@endsection