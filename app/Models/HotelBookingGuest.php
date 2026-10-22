<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelBookingGuest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hotel_booking_id', 'name', 'date_of_birth', 'nationality',
    ];

    public function booking()
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id');
    }
}