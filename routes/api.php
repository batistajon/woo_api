<?php

use App\Http\Controllers\WooCommerceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/customers', [WooCommerceController::class, 'customers']);
Route::get('/orders', [WooCommerceController::class, 'orders']);
Route::get('/products', [WooCommerceController::class, 'products']);
Route::get('/products/category/{category}', [WooCommerceController::class, 'productsCategory']);
Route::get('/products/{id}', [WooCommerceController::class, 'productDetails']);
Route::get('/products/categories', [WooCommerceController::class, 'categories']);
Route::get('/', [WooCommerceController::class, 'index']);
Route::get('/authenticate', [WooCommerceController::class, 'authenticate']);
Route::post('/slack', [WooCommerceController::class, 'slack']);
Route::post('/slack-form', [WooCommerceController::class, 'getFormSlack']);
