<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Retailer;


class ProductUrlValidation implements ValidationRule, DataAwareRule
{


    protected $data = [];
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $retailer = Retailer::findOrFail($this->data['retailer']); // Get selected retailer
        // fail if doesn't match retailer domain with www or without www
        if (!preg_match("/https?:\/\/(www\.)?{$retailer->domain}\//", $value)) {
            $fail("The {$attribute} must be a valid URL for this retailer.");
        }
        $client = $retailer ? $retailer->client() : null;
        $patterns = $client->getProductURLPatterns();

        // iterate over patterns and check if any matches the url and store the sku which is match 1 and fail if no match

        $sku = null;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value, $matches)) {
                $sku = $matches[1];
                // store sku in data
                $this->data['sku'] = $sku;

                // add sku to session
                session()->put('sku', $sku);
            }
        }
        if (!$sku) {
            $fail("The {$attribute} must be a valid URL for this retailer.");
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        $this->data['test'] = 'test';

        return $this;
    }


}
