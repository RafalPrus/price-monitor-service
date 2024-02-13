<?php

namespace App\Http\Requests;

use App\Models\Offer;
use App\Rules\AvailableStoreRule;
use App\Services\UrlService;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AbstractRequestForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function requiredToSometimes(array $rules, $except = []): array
    {
        foreach ($rules as &$element) {
            if (is_array($element)) {
                $element = $this->requiredToSometimes($element);
            } else {
                if (is_string($element) && $element == 'required') {
                    $element = 'sometimes';
                }
            }
        }

        return $rules;
    }
}