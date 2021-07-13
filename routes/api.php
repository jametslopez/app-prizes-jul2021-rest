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
    // Campaigns
    Route::get('/{campaignId}/settings', [
        'as' => 'campaigns.setting',
        'uses' => 'CampaignController@setting'
    ]);
    
    // Clients
    Route::get('/{campaignId}/clients', [
        'as' => 'clients.index',
        'uses' => 'ClientController@index'
    ]);
    Route::post('/{campaignId}/clients', [
        'as' => 'clients.store',
        'uses' => 'ClientController@store'
    ]);
    Route::get('/{campaignId}/clients/{client}', [
        'as' => 'clients.shot',
        'uses' => 'ClientController@show'
    ]);
    Route::post('/{campaignId}/clients/{client}', [
        'as' => 'clients.update',
        'uses' => 'ClientController@update'
    ]);

    // Valid DNI
    Route::post('/dni', [
        'as' => 'dni.index',
        'uses' => 'DniController@index'
    ]);

    Route::post('/{campaignId}/coupons/search', [
        'as' => 'coupons.search',
        'uses' => 'CouponController@search'
    ]);
    Route::post('/{campaignId}/coupons/use', [
        'as' => 'coupons.use',
        'uses' => 'CouponController@use'
    ]);
});
