<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Oferta
 *
 * @property-read int $id
 * @property int $user_id
 * @property string $url
 * @property string $domain
 * @property string $name
 * @property bool|int $is_active
 * @property null|int $price_history_actual_id
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 * @property-read null|float $price_current
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\PriceHistory[] $priceHistories
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property \App\Models\PriceHistory $priceActual
 */

class Offer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function priceActual(): BelongsTo
    {
        return $this->belongsTo(PriceHistory::class,'price_history_actual_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected function priceCurrent(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->priceActual->price,
        );
    }
}
