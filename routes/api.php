<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('categories',\App\Http\Controllers\Categories\CategoryController::class);

Route::resource('products',\App\Http\Controllers\Products\ProductController::class);
