<?php

namespace App\Models;

use App\Models\Traits\CanBeScoped;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;


class Product extends Model
{
    use HasFactory;
    use CanBeScoped;
    use HasPrice;

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    public function stockCount()
    {
        return $this->variations->sum(function ($variation){
            return $variation->stockCount();
        });
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function variations(){
        return $this->hasMany(ProductVariation::class)->orderBy('order');
    }


}
