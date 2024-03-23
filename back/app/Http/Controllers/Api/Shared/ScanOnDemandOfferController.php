<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\ScanOnDemandOfferStoreRequestForm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScanOnDemandOfferController extends Controller
{
    public function store(ScanOnDemandOfferStoreRequestForm $form)
    {
        $form->save();
        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
