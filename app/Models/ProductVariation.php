<?php

namespace App\Models;

use App\Cart\Money;
use App\Models\Collections\ProductVariationCollection;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    use HasPrice;

    public function getPriceAttribute($value){

        if ($value === null) {
            return $this->product->price;
        }
        return new Money($value);
    }

    public function priceVaries()
    {
        return  $this->price->amount() !== $this->product->price->amount() ;
    }

    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    public function stockCount()
    {
        return $this->stock->sum('pivot.stock');
    }


    public function type(){
        return $this->hasOne(ProductVariationType::class,'id','product_variation_type_id');
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function stocks(){
        return $this->hasMany(Stock::class);
    }

    public function minStock($amount){
        return min($this->stockCount(), $amount);
    }

    public function stock()
    {
        return $this->belongsToMany(
            ProductVariation::class,
            'product_variation_stock_view',
            'id',
            'id'

        )
            ->withPivot([
                'stock',
                'in_stock'
            ])
            ;
    }

    public function newCollection(array $models = [])
    {
        return new ProductVariationCollection($models);
    }
}
