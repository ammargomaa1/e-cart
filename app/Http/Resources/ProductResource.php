<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends ProductsIndexResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this->variations->groupBy('type.name'));
        return array_merge(parent::toArray($request),[
            'variations' => ProductVariationsResource::collection(
                $this->variations->groupBy('type.name')
            )
        ]);
    }
}
