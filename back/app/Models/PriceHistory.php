<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Cena - historia
 *
 * @property-read int $id
 * @property float $price
 * @property int $offer_id
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property \App\Models\Offer $offer
 */
class PriceHistory extends Model
{
    protected $guarded = [];
    use HasFactory;
    const UPDATED_AT = null;

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
