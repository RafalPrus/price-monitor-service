<?php

namespace App\Rules;

use App\Enums\AvailableStore;
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
        $host = parse_url($value)['host'];
        if (str_starts_with($host, 'www.')) {
            $host = substr($host, 4);
        }

        if (!in_array($host, array_column(AvailableStore::cases(), 'value'))) {
            $fail("The {$attribute} is invalid.");
        }
    }
}
