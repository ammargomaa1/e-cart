<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartStoreRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->middleware(['auth:api']);
        $this->cart = $cart;
    }

    
    public function store(CartStoreRequest $request){

        $this->cart->add($request->products);
    }
}
