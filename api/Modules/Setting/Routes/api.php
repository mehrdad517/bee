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

    Route::prefix('setting')->middleware('auth:api')->group(function () {

        Route::get('/links', function (Request $request) {
            $response = \Modules\Setting\Entities\Link::get();
            return response($response);
        });

        Route::get('/', 'SettingController@read');
        Route::put('/', 'SettingController@update');
        Route::get('/sticky', 'SettingController@readSticky');
        Route::put('/sticky', 'SettingController@updateSticky');
        Route::get('/domainLinks', 'SettingController@readDomainLinks');
        Route::put('/domainLinks', 'SettingController@updateDomainLinks');
    });

});

