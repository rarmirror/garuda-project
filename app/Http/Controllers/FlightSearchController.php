<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;

class FlightSearchController extends Controller
{
    public function index(Request $request)
    {
        $departure = $request->query('departure');
        $arrival   = $request->query('arrival');
        $date      = $request->query('date');
        $quantity  = $request->query('quantity', 1);

        $query = Flight::with(['airline', 'segments.airport', 'classes']);

        if ($departure) {
            $query->whereHas('segments', function ($q) use ($departure, $date) {
                $q->where('sequence', 1)
                    ->whereHas('airport', fn($a) => $a->where('iata_code', $departure));

                if ($date) {
                    // Tampilkan penerbangan dari tanggal dipilih sampai 30 hari ke depan,
                    // supaya tetap ada hasil walau tidak ada di tanggal persis yang dipilih.
                    $from = \Carbon\Carbon::parse($date)->startOfDay();
                    $to   = $from->copy()->addDays(30)->endOfDay();
                    $q->whereBetween('time', [$from, $to]);
                }
            });
        }

        if ($arrival) {
            $query->whereHas('segments', function ($q) use ($arrival) {
                $q->whereHas('airport', fn($a) => $a->where('iata_code', $arrival))
                    ->whereRaw('sequence = (select max(sequence) from flight_segments where flight_id = flights.id)');
            });
        }

        $flights = $query->get()->map(function ($flight) {
            $sortedSegments = $flight->segments->sortBy('sequence')->values();

            $flight->first_segment  = $sortedSegments->first();
            $flight->last_segment   = $sortedSegments->last();
            $flight->transit_count  = max(0, $sortedSegments->count() - 2);
            $flight->cheapest_class = $flight->classes->sortBy('price')->first();

            return $flight;
        });

        // Urutkan hasil: penerbangan yang paling dekat dengan tanggal yang dipilih muncul dulu.
        if ($date) {
            $flights = $flights->sortBy(function ($flight) use ($date) {
                $segTime = $flight->first_segment?->time;
                return $segTime
                    ? abs(\Carbon\Carbon::parse($date)->diffInMinutes(\Carbon\Carbon::parse($segTime)))
                    : PHP_INT_MAX;
            })->values();
        }

        $airports = Airport::orderBy('name')->get();

        return view('flights.search', compact('flights', 'airports', 'departure', 'arrival', 'date', 'quantity'));
    }
}
