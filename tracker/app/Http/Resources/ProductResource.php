<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
 use App\Http\Resources\StockResource;
use App\Http\Resources\TypeResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->whenLoaded('type', fn() => new TypeResource($this->type)),
            'stocks' => $this->whenLoaded('stocks', fn() => StockResource::collection($this->stocks)),
            'created_at' => $this->created_at,
            'price_range' => $this->whenLoaded('stocks', fn() => $this->price_range),
            'routes' => [
                'show' => $this->showRoute(),
            ]
        ];
    }
}
