<?php

namespace App\Models;

use App\Models\Traits\HasChildren;
use App\Models\Traits\IsOrderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
    use HasChildren;
    use IsOrderable;
    protected $fillable = [
        'name',
        'order'
    ];





    public function children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }
}
