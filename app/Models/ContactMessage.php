<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /** Subjek yang tersedia di form Contact (harus sama dengan pilihan di view). */
    public const SUBJECTS = [
        'Pertanyaan Booking',
        'Pembatalan Tiket',
        'Refund',
        'Keluhan',
        'Lainnya',
    ];

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status',
    ];
}