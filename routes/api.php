<?php

use App\Http\Controllers\AkauntingController;
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

/**
 * woocommerce
 */ 
Route::get('/customers', [WooCommerceController::class, 'customers']);
Route::get('/customers/email/{email}', [WooCommerceController::class, 'customersTest']);
Route::get('/orders', [WooCommerceController::class, 'orders']);
Route::post('/products/array', [WooCommerceController::class, 'productsArray']);
Route::get('/products', [WooCommerceController::class, 'products']);
Route::get('/products/category/{category}', [WooCommerceController::class, 'productsCategory']);
Route::get('/products/details/{id}', [WooCommerceController::class, 'productDetails']);
Route::get('/products/categories', [WooCommerceController::class, 'categories']);
Route::get('/products/{id}/variations', [WooCommerceController::class, 'productVariations']);

/**
 * slack
 *  */
Route::get('/authenticate', [WooCommerceController::class, 'authenticate']);
Route::post('/slack', [WooCommerceController::class, 'slack']);
Route::post('/slack-form', [WooCommerceController::class, 'getFormSlack']);

/**
 * akaunting
 */
 Route::get('/akaunting/companies', [AkauntingController::class, 'companies']);
 Route::get('/akaunting/owner-company/{id}', [AkauntingController::class, 'ownerCompany']);
