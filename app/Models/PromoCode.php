<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'code',
        'discount_type',
        'discount',
        'valid_until',
        'is_used',
        'max_usage',
        'usage_count',
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Cek apakah promo sudah mencapai batas pemakaian.
     */
    public function isExpired(): bool
    {
        return $this->usage_count >= $this->max_usage;
    }

    /**
     * Reset hitungan pemakaian promo kembali ke 0.
     */
    public function resetUsage(): void
    {
        $this->update([
            'usage_count' => 0,
            'is_used'     => false,
        ]);
    }
}