<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Oferta
 *
 * @property-read int $id
 * @property int $user_id
 * @property string $url
 * @property string $name
 * @property bool|int $is_active
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceHistory[] $priceHistories
 * @property-read \App\Models\PriceHistory $actualPrice
 */

class Offer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function actualPrice(): BelongsTo
    {
        return $this->belongsTo(PriceHistory::class,'price_history_actual_id');
    }
}
