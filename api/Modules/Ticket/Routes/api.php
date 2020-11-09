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

    Route::prefix('tickets')->middleware('auth:api')->group(function () {

        Route::group(['prefix' => '/categories'], function () {
            Route::get('/', 'CategoryController@index');
            Route::post('/', 'CategoryController@store');
            Route::get('/{id}', 'CategoryController@show');
            Route::put('/{id}', 'CategoryController@update');
            Route::get('/{id}/findMapState', 'CategoryController@findMapState');
            Route::put('/{id}/dispatchMove', 'CategoryController@dispachMove');
            Route::post('/{id}/document', 'CategoryController@document');
        });


        Route::get('/', 'TicketController@index');
        Route::post('/', 'TicketController@store');
        Route::get('/{id}/conversations', 'TicketController@conversations');
        Route::post('/{id}/conversations', 'TicketController@storeConversations');
        Route::delete('/{ticket}/conversations/{id}', 'TicketController@deleteConversation');
        Route::put('/{id}', 'TicketController@update');

    });
});
