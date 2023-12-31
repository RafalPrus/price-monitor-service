<?php

namespace App\Http\Controllers\Auth\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequestForm;
use App\Http\Resources\OfferResource;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function store(StoreOfferRequestForm $form)
    {
        $offer = $form->store();
        return new OfferResource($offer);
    }
}
