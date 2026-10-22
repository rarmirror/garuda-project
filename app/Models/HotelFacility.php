<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelFacility extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon'];

    public function rooms()
    {
        return $this->belongsToMany(
            HotelRoom::class,
            'hotel_room_facilities',
            'hotel_facility_id',
            'hotel_room_id'
        );
    }
}