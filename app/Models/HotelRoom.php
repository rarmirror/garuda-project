<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_id', 'name', 'type', 'price_per_night',
        'capacity', 'total_rooms', 'description',
        'image', 'is_available',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(
            HotelFacility::class,
            'hotel_room_facilities',
            'hotel_room_id',
            'hotel_facility_id'
        );
    }

    public function bookings()
    {
        return $this->hasMany(HotelBooking::class);
    }
}