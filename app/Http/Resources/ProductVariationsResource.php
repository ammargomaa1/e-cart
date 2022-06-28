<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ProductVariationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection){
            return ProductVariationsResource::collection($this->resource);
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->formattedPrice,
            'price_varies' => $this->priceVaries(),
            'stock_count' => (int) $this->stockCount(),
            'in_stock' => $this->inStock()
        ];
    }
}
