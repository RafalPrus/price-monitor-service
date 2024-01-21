<?php

namespace App\Rules;

use App\Enums\AvailableStore;
use App\Services\UrlService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class AvailableStoreRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = UrlService::getDomain($value);

        if (!in_array($domain, AvailableStore::getAvailableStores())) {
            $fail("The {$attribute} is invalid.");
        }
    }
}
