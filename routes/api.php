<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('categories',\App\Http\Controllers\Categories\CategoryController::class);

Route::resource('products',\App\Http\Controllers\Products\ProductController::class);

Route::group(['prefix'=>'auth'],function(){
   Route::post('register',[\App\Http\Controllers\Auth\RegisterController::class,'action']);
});
