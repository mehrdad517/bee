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

Route::prefix('backend')->group(function () {

    Route::prefix('galleries')->middleware('auth:api')->group(function () {
        Route::get('/', 'GalleryController@index');
        Route::post('/', 'GalleryController@store');
        Route::get('/{id}', 'GalleryController@show');
        Route::put('/{id}', 'GalleryController@update');
        Route::put('/{id}/status', 'GalleryController@status');
    });

});
