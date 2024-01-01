<?php

namespace App\Http\Controllers\Auth\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequestForm;
use App\Http\Requests\UpdateOfferRequestForm;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function store(StoreOfferRequestForm $form)
    {
        $offer = $form->store();
        return new OfferResource($offer);
    }

    public function update(UpdateOfferRequestForm $form, Offer $offer)
    {
        $dec = $this->authorize('update', $offer);
        $offer = $form->update($offer);

        return new OfferResource($offer);
    }
}
