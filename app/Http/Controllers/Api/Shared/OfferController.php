<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequestForm;
use App\Http\Requests\UpdateOfferRequestForm;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class OfferController extends Controller
{
    /**
     * Offers - list
     *
     * sorts: name, url
     * filters:
     *
     * @group Auth.User
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $offers = QueryBuilder::for(Offer::class)
            ->where('user_id', $userId)
            ->with('actualPrice')
            ->allowedSorts(['name', 'url'])
            ->jsonPaginate();

        return OfferResource::collection($offers);
    }

    /**
     * Offers - add
     *
     * @group Auth.User
     */
    public function store(StoreOfferRequestForm $form)
    {
        $offer = $form->store();
        return new OfferResource($offer);
    }

    /**
     * Offers - update
     *
     * @group Auth.User
     */
    public function update(UpdateOfferRequestForm $form, Offer $offer)
    {
        $this->authorize('update', $offer);
        $offer = $form->update($offer);

        return new OfferResource($offer);
    }

    /**
     * Offers - update
     *
     * @group Auth.User
     */
    public function show(Offer $offer)
    {
        $this->authorize('view', $offer);
        $offer->load('priceHistories');

        return new OfferResource($offer);
    }

    /**
     * Offers - delete
     *
     * @group Auth.User
     */
    public function destroy(Offer $offer)
    {
        $this->authorize('delete', $offer);
        $offer->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
