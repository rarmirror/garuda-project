<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $airports = Airport::orderBy('name')->get();

        $popularAirports = Airport::where('is_popular', true)
            ->orderBy('name')
            ->limit(5)
            ->get();
        $featuredHotels = Hotel::with('rooms')
            ->where('is_available', true)
            ->orderBy('star_rating', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($hotel) {
                $hotel->cheapest_room = $hotel->rooms->min('price_per_night');
                return $hotel;
            });

        return view('home', compact('airports', 'popularAirports', 'featuredHotels'));
    }
}
