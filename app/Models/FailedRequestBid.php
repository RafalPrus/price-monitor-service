<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Nieudane Requesty pobierające dane z oferty
 *
 * @property-read int $id
 * @property int $offer_id
 * @property string $error_message
 * @property \App\Models\Offer $offers
 */

class FailedRequestBid extends Model
{
    use HasFactory;
    public function offers(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
