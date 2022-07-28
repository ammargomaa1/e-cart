<?php

namespace App\Http\Resources\Cart;

use App\Cart\Money;
use App\Http\Resources\ProductsIndexResource;
use App\Http\Resources\ProductVariationsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductVariationResource extends ProductVariationsResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        
        return array_merge(parent::toArray($request),[
            'product'=> new ProductsIndexResource($this->product),
            'quantity' => $this->pivot->quantity,
            'total' => $this->getTotal()->formatted()
        ]);
    }

    protected function getTotal(){
        return new Money($this->pivot->quantity * $this->price->amount());
    }
}
