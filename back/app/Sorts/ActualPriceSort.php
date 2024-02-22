<?php

namespace App\Sorts;

use App\Models\PriceHistory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ActualPriceSort implements \Spatie\QueryBuilder\Sorts\Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        // Below is equivalent to: Person::select($property)->whereColumn('people.id', 'person_id')
        // We're using a standard DB call to return Illuminate\Database\Query\Builder to satisfy static analysis
        $subQuery = DB::table(with(new PriceHistory())->getTable())
            ->select($property)
            ->whereColumn('price_histories.id', 'price_history_actual_id');

        $query->orderBy($subQuery, $direction);
    }
}