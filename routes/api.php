<?php

use Illuminate\Http\Request;

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

Route::apiResource('deposits', 'DepositsController');
Route::apiResource('quotes', 'QuotesController');
Route::get('get-deposits/{id}', 'QuotesController@getDeposits');
Route::put('add-payments/{id}','QuotesController@addDeposits');
