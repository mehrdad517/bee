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

    Route::prefix('reports')->middleware('auth:api')->group(function () {

        Route::get('/mapReport', function (Request $request) {

            $report = \Illuminate\Support\Facades\DB::select('call sp_map_report');

            return response($report);
        });

    });



});
