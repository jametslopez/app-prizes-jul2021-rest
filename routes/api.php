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

Route::group(['namespace' => 'Api'], function () {
    Route::get('/clients', [
        'as' => 'clients.index',
        'uses' => 'ClientController@index'
    ]);
    Route::post('/clients', [
        'as' => 'clients.store',
        'uses' => 'ClientController@store'
    ]);
    Route::get('/clients/{client}', [
        'as' => 'clients.shot',
        'uses' => 'ClientController@show'
    ]);
    Route::post('/clients/{client}', [
        'as' => 'clients.update',
        'uses' => 'ClientController@update'
    ]);

    Route::post('/dni', [
        'as' => 'dni.index',
        'uses' => 'DniController@index'
    ]);

    Route::post('/coupons/search', [
        'as' => 'coupons.search',
        'uses' => 'CouponController@search'
    ]);
    Route::post('/coupons/use', [
        'as' => 'coupons.use',
        'uses' => 'CouponController@use'
    ]);
});
