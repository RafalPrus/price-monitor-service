<?php

namespace App\Http\Requests\Shared;

use App\Http\Requests\AbstractRequestForm;
use App\Jobs\ProcessOfferScan;
use App\Models\Offer;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;

class ScanOnDemandOfferStoreRequestForm extends AbstractRequestForm
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'offer' => [
                'required',
                Rule::exists(Offer::class, 'id')->where(function (Builder $query) {
                    return $query->where('user_id', auth()->user()->id)
                        ->where('is_active', 1);
                })
            ],
        ];
    }

    public function save(): bool
    {
        $offer = Offer::find($this->route('offer'));

        ProcessOfferScan::dispatch($offer);

        return true;
    }

    public function bodyParameters(): array
    {
        return [
            'offer' => [
                'description' => 'Offer ID',
                'example' => 1
            ],
        ];
    }
}
