<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Oferta
 *
 * @property-read int $id
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Offer[] $offers
 */

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class);
    }
}
