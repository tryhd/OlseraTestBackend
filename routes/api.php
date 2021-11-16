<?php

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


Route::group(['prefix' => 'taxes','as' => 'tax.'], function() {
    Route::get('index','API\TaxController@index')->name('index');
    Route::post('store','API\TaxController@store')->name('store');
    Route::put('update/{id}','API\TaxController@update')->name('update');
    Route::delete('delete/{id}','API\TaxController@delete')->name('delete');
});

Route::group(['prefix' => 'items','as' => 'item.'], function() {
    Route::get('index','API\ItemController@index')->name('index');
    Route::post('store','API\ItemController@store')->name('store');
    Route::put('update/{id}','API\ItemController@update')->name('update');
    Route::delete('delete/{id}','API\ItemController@delete')->name('delete');
});

Route::get('item-tax/{id}','API\ItemTaxController@show')->name('item-tax');


