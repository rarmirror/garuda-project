<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Airport;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $airline  = $request->query('airline');
        $from     = $request->query('from');
        $to       = $request->query('to');

        $query = Flight::with([
            'airline',
            'segments' => fn($q) => $q->orderBy('sequence'),
            'segments.airport',
            'classes',
        ]);

        if ($airline) {
            $query->whereHas('airline', fn($q) => $q->where('name', 'like', "%$airline%"));
        }

        if ($from) {
            $query->whereHas('segments', fn($q) => $q
                ->where('sequence', 1)
                ->whereHas('airport', fn($a) => $a->where('iata_code', $from))
            );
        }

        if ($to) {
            $query->whereHas('segments', fn($q) => $q
                ->whereHas('airport', fn($a) => $a->where('iata_code', $to))
                ->whereRaw('sequence = (select max(sequence) from flight_segments where flight_id = flights.id and deleted_at is null)')
            );
        }

        $flights  = $query->get()->map(function ($flight) {
            $sorted = $flight->segments->sortBy('sequence')->values();
            $flight->first_segment  = $sorted->first();
            $flight->last_segment   = $sorted->last();
            $flight->transit_count  = max(0, $sorted->count() - 2);
            $flight->cheapest_price = $flight->classes->min('price');
            return $flight;
        });

        $airports = Airport::orderBy('name')->get();

        return view('schedule.index', compact('flights', 'airports', 'from', 'to', 'airline'));
    }
}