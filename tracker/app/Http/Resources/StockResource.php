<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Cknow\Money\Money;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'product' => $this->whenLoaded('product', fn() => ProductResource::make($this->product)),
            'retailer' => $this->whenLoaded('retailer', fn() => RetailerResource::make($this->retailer)),
            'in_stock' => $this->in_stock,
            'price' => $this->price,
            'currency' => $this->currency,
            'formatted_price' => $this->formatted_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tracked' => $this->tracked,
        ];
    }
}
