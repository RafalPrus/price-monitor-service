<?php

namespace App\Http\Requests;

use App\Models\Offer;
use App\Rules\AvailableStoreRule;
use App\Services\UrlService;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequestForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url' => [
                'required',
                'url',
                new AvailableStoreRule,
            ],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'is_active' => ['required', 'int', Rule::in([0, 1])]
        ];
    }

    public function store(): Offer
    {
        $data = $this->validated();
        $data['domain'] = UrlService::getDomain($data['url']);
        
        $offer = auth()->user()->offers()->create($data);
        return $offer;
    }

    public function requiredToSometimes(array $rules): array
    {
        foreach ($rules as &$element) {
            if (is_array($element)) {
                $this->requiredToSometimes($element);
            } else {
                if (is_string($element) && $element == 'required') {
                    $element = 'sometimes';
                }
            }
        }

        return $rules;
    }

    public function bodyParameters(): array
    {
        return [
            'url' => [
                'description' => 'URL address heading to product',
                'example' => 'https://allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-13463150115?offerId=13463150115'
            ],
            'name' => [
                'description' => 'Name of offer',
                'example' => 'Highlighters 6 pcs',
            ],
            'is_active' => [
                'description' => 'Is monitoring price for this offer active',
                'example' => 1
            ],
        ];
    }
}
