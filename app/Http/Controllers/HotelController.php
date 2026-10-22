<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $city    = $request->query('city');
        $star    = $request->query('star');
        $checkin = $request->query('checkin', now()->addDay()->format('Y-m-d'));
        $checkout= $request->query('checkout', now()->addDays(2)->format('Y-m-d'));
        $guests  = $request->query('guests', 1);

        $query = Hotel::with(['rooms'])->where('is_available', true);

        if ($city) {
            $query->where('city', 'like', "%$city%");
        }

        if ($star) {
            $query->where('star_rating', $star);
        }

        $hotels = $query->get()->map(function ($hotel) use ($checkin, $checkout) {
            $hotel->cheapest_room = $hotel->rooms->where('is_available', true)->min('price_per_night');
            return $hotel;
        });

        $cities = Hotel::where('is_available', true)
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        return view('hotels.index', compact('hotels', 'cities', 'city', 'star', 'checkin', 'checkout', 'guests'));
    }

    public function show(Request $request, $slug)
    {
        $hotel = Hotel::with(['rooms.facilities'])
            ->where('slug', $slug)
            ->where('is_available', true)
            ->firstOrFail();

        $checkin  = $request->query('checkin', now()->addDay()->format('Y-m-d'));
        $checkout = $request->query('checkout', now()->addDays(2)->format('Y-m-d'));
        $guests   = $request->query('guests', 1);

        $nights = max(1, \Carbon\Carbon::parse($checkin)->diffInDays(\Carbon\Carbon::parse($checkout)));

        return view('hotels.show', compact('hotel', 'checkin', 'checkout', 'guests', 'nights'));
    }
}