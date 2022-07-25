<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cart\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('categories',\App\Http\Controllers\Categories\CategoryController::class);

Route::resource('products',\App\Http\Controllers\Products\ProductController::class);

Route::group(['prefix'=>'auth'],function(){
   Route::post('register',[RegisterController::class,'action']);
   Route::post('login',[LoginController::class,'action']);
   Route::get('me',[MeController::class,'action']);

});

Route::resource('cart',CartController::class,[
   'parameters' => [
      'cart' =>'productVariation'
   ]
]);
