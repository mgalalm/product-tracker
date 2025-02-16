<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Retailer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\Validation\ValidatorAwareRule;


class ProductUrlValidation implements ValidationRule, DataAwareRule, ValidatorAwareRule
{


    protected $validator;
    protected $data = [];
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        // get the corresponding retailer from the data given the attribute comes in this form stocks.*.url where * is the index of the array

        $index = explode('.', $attribute)[1];

        if (!isset($this->data['stocks'][$index]['retailer'])) {
            return;
        }

        // return if retailer not exists
        if (!Retailer::find($this->data['stocks'][$index]['retailer'])) {
            return;
        }
;

        $retailer = Retailer::findOrFail($this->data['stocks'][$index]['retailer']); // Get selected retailer
        // dd($retailer->toArray());
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
            }
            $this->data['stocks'][$index]['sku'] = $sku;
            $this->validator->setData($this->data);
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
        return $this;
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }


}
