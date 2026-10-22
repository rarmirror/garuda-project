<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'hotel_id', 'hotel_room_id',
        'name', 'email', 'phone',
        'check_in', 'check_out',
        'number_of_guests', 'number_of_nights',
        'promo_code_id', 'payment_status',
        'subtotal', 'grandtotal',
    ];

    protected $casts = [
        'check_in'  => 'date',
        'check_out' => 'date',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class)->withTrashed();
    }

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id')->withTrashed();
    }

    public function promo()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id');
    }

    public function guests()
    {
        return $this->hasMany(HotelBookingGuest::class);
    }
}