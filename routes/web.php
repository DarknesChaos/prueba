<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(array('middleware' => 'auth'), function (){
    Route::get('/deposits', 'DepositsController')->name('deposits');
    Route::get('/quotes', 'QuotesController')->name('quotes');
});
