<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequestForm;
use App\Http\Requests\UpdateOfferRequestForm;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Sorts\ActualPriceSort;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class OfferController extends Controller
{
    protected $indexRelations = ['priceActual'];
    protected $relations = ['priceActual'];
    /**
     * Offers - list
     *
     * sorts: name, url
     * filters: domain, name
     *
     * @group Shared.Offers
     *
     * @responseField id integer Offer id.
     * @responseField user_id integer User id.
     * @responseField url string Offer url.
     * @responseField name string Offer name.
     * @responseField is_active int Is offer active.
     * @responseField created_at string Date the offer was created.
     * @responseField updated_at string Date of the last offer updated.
     * @responseField price_history_actual_id int ID of actual price model.
     * @responseField price_history_actual_id int ID of actual price model.
     * @responseField price_actual arra Actual offer price model properties.
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
     *          "price_history_actual_id": null,
     *          "actual_price": null,
     *      },
     *      {
     *          "id": 2,
     *          "user_id": 1,
     *          "url": "https://allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7",
     *          "name": "Papier 6szt",
     *          "is_active": 1,
     *          "created_at": "2024-02-04T10:46:41.000000Z",
     *          "updated_at": "2024-02-04T10:46:41.000000Z",
     *          "price_history_actual_id" => 21
     *          "price_actual" => array:4 [
     *              "id" => 21,
     *              "price" => "99722.79",
     *              "offer_id" => 5,
     *              "created_at" => "1997-04-01T01:50:45.000000Z"
     *          ],
     *      }
     *  ]
     * }
     */
    public function index()
    {
        $userId = auth()->user()->id;

        $offers = QueryBuilder::for(Offer::class)
            ->where('user_id', $userId)
            ->with($this->indexRelations)
            ->defaultSort('created_at')
            ->allowedSorts(['created_at', 'name', 'domain', 'price_histories.price',
                AllowedSort::custom('price-actual', new ActualPriceSort(), 'price'),
            ])
            ->allowedFilters([
                AllowedFilter::exact('domain'),
                AllowedFilter::partial('name'),
            ])
            ->jsonPaginate()
        ;

        foreach($offers as $offer) {
            $offer->append('price_last_previous');
        }

        return OfferResource::collection($offers);
    }

    /**
     * Offers - add
     *
     * @group Shared.Offers
     */
    public function store(StoreOfferRequestForm $form)
    {
        $this->authorize('store', Offer::class);
        $offer = $form->store();
        return new OfferResource($offer);
    }

    /**
     * Offers - update
     *
     * @group Shared.Offers
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
     * @group Shared.Offers
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
     * @group Shared.Offers
     */
    public function destroy(Offer $offer)
    {
        $this->authorize('delete', $offer);
        $offer->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
