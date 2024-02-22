<?php

namespace App\Http\Requests;

use App\Models\Offer;
use App\Rules\AvailableStoreRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferRequestForm extends StoreOfferRequestForm
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = $this->requiredToSometimes(parent::rules(), ['url']);
        return $rules;
    }

    public function update(Offer $offer)
    {
        $data = $this->validated();

        $offer->update($data);
        return $offer;
    }
}
