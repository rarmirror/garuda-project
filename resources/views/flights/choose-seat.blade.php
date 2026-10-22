@extends('layouts.app')

@section('title', 'Choose Seats - Garuda')

@section('content')

<div x-data="seatSelector({{ $quantity }})" class="max-w-7xl mx-auto px-6 pt-32 pb-16">

    <a href="{{ route('flight.choose-tier', ['flight' => $flight->id, 'date' => $date, 'quantity' => $quantity]) }}"
       class="inline-flex items-center gap-2 bg-gray-900 text-white text-sm font-semibold px-5 py-3 rounded-full mb-8">
        ← Back to Choose Flight
    </a>

    <h1 class="text-4xl font-extrabold mb-10">Choose Seats</h1>

    <div class="grid md:grid-cols-3 gap-8 items-start">

        {{-- LEFT: Flight + Class Summary --}}
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="font-bold text-lg mb-4">Your Flight</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <div class="text-xs text-gray-400 mb-1">Departure</div>
                    <div class="font-semibold text-sm">
                        {{ $firstSegment?->airport?->name }} ({{ $firstSegment?->airport?->iata_code }})
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Arrival</div>
                    <div class="font-semibold text-sm">
                        {{ $lastSegment?->airport?->name }} ({{ $lastSegment?->airport?->iata_code }})
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <div class="text-xs text-gray-400 mb-1">Date</div>
                    <div class="font-semibold text-sm">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F y') }}
                    </div>
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-1">Quantity</div>
                    <div class="font-semibold text-sm">{{ $quantity }} people</div>
                </div>
            </div>

            <div class="border-t pt-4">
                <div class="font-semibold text-sm">{{ $flight->airline->name ?? '-' }}</div>
                <div class="text-xs text-gray-500">
                    {{ $firstSegment?->airport?->iata_code }} → {{ $lastSegment?->airport?->iata_code }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Rp {{ number_format($flightClass->price, 0, ',', '.') }}
                </div>
            </div>

            <div class="border-t mt-4 pt-4">
                <div class="font-semibold text-sm capitalize">{{ $flightClass->class_type }} Class</div>
                <div class="text-blue-600 font-bold">Rp {{ number_format($flightClass->price, 0, ',', '.') }}</div>
            </div>

            {{-- Selected seats preview --}}
            <div class="border-t mt-4 pt-4">
                <div class="text-xs text-gray-400 mb-2">Selected Seats</div>
                <div class="flex flex-wrap gap-2">
                    <template x-for="seat in selected" :key="seat.id">
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded"
                              x-text="seat.name"></span>
                    </template>
                    <span x-show="selected.length === 0" class="text-xs text-gray-400 italic">Belum ada kursi dipilih</span>
                </div>
            </div>
        </div>

        {{-- RIGHT: Seat Map --}}
        <div class="md:col-span-2 bg-white rounded-2xl shadow-md p-8">

            <h3 class="text-center font-bold text-lg mb-2 capitalize">{{ $flightClass->class_type }} Class</h3>

            <div class="flex items-center justify-center gap-6 mb-8 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded border-2 border-orange-300"></span> Available
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-gray-300"></span> Booked
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded bg-blue-500"></span> Selected
                </div>
            </div>

            <form method="POST" action="{{ route('flight.store-seats', $flight->id) }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ $flightClass->id }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="quantity" value="{{ $quantity }}">

                <template x-for="seat in selected" :key="seat.id">
                    <input type="hidden" name="seat_ids[]" :value="seat.id">
                </template>

                <div class="space-y-4 mb-8">
                    @foreach ($seats as $row => $seatRow)
                        <div class="flex items-center justify-center gap-3">
                            @foreach ($seatRow as $seat)
                                <button
                                    type="button"
                                    @click="toggleSeat({{ $seat->id }}, '{{ $seat->name }}')"
                                    :class="isSelected({{ $seat->id }})
                                        ? 'bg-blue-500 text-white border-blue-500'
                                        : 'border-orange-300 text-gray-700 hover:bg-orange-50'"
                                    class="w-14 h-14 rounded-xl border-2 font-semibold text-sm transition">
                                    {{ $seat->name }}
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                @if ($errors->any())
                    <div class="text-red-600 text-sm text-center mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit"
                        :disabled="selected.length !== {{ $quantity }}"
                        :class="selected.length === {{ $quantity }} ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'"
                        class="w-full text-white font-semibold py-3 rounded-xl transition">
                    <span x-text="'Confirm ' + selected.length + ' / {{ $quantity }} Seats'"></span>
                </button>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('seatSelector', (maxQuantity) => ({
        selected: [],
        toggleSeat(id, name) {
            const index = this.selected.findIndex(s => s.id === id);
            if (index > -1) {
                this.selected.splice(index, 1);
            } else {
                if (this.selected.length >= maxQuantity) {
                    this.selected.shift();
                }
                this.selected.push({ id, name });
            }
        },
        isSelected(id) {
            return this.selected.some(s => s.id === id);
        }
    }));
});
</script>

@endsection