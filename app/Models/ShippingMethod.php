<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;
    use HasPrice;

    public function countries(){
        return $this->belongsToMany(Country::class);
    }
}
