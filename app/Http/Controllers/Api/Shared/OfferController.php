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
     *
     * @responseField id integer Offer id.
     * @responseField user_id integer User id.
     * @responseField url string Offer url.
     * @responseField name string Offer name.
     * @responseField is_active int Is offer active.
     * @responseField created_at string Date the offer was created.
     * @responseField updated_at string Date of the last offer updated.
     * @responseField price_history_actual_id int ID of actual price model.
     * @responseField actual_price float Actual offer price.
     *
     * @response 200 {
     * "data" : [
     *      {
     *          "id": 1,
     *          "user_id": 1,
     *          "url": "https://allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7",
     *          "name": "Zakreslacze 6szt",
     *          "is_active": 1,
     *          "created_at": "2024-02-04T10:46:41.000000Z",
     *          "updated_at": "2024-02-04T10:46:41.000000Z",
     *          "price_history_actual_id": 2,
     *          "actual_price": 223.20,
     *      },
     *      {
     *          "id": 2,
     *          "user_id": 1,
     *          "url": "https://allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7",
     *          "name": "Papier 6szt",
     *          "is_active": 1,
     *          "created_at": "2024-02-04T10:46:41.000000Z",
     *          "updated_at": "2024-02-04T10:46:41.000000Z",
     *          "price_history_actual_id": null,
     *          "actual_price": null,
     *      }
     *  ]
     * }
     */
    public function index()
    {
        $userId = auth()->user()->id;
        $offers = QueryBuilder::for(Offer::class)
            ->where('user_id', $userId)
            ->with('priceActual')
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
