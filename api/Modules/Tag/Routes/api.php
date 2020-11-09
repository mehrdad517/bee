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

    Route::prefix('tags')->group(function () {

        Route::get('/autocomplete', function (Request $request) {
            $response = [];
            if ($request->get('term')) {
                $response = \Modules\Tag\Entities\Tag::select('id', 'name')
                    ->where('name', 'like', $request->get('term'))
                    ->take(10)
                    ->get();
            }
            return response($response);
        });

        Route::post('/', function (Request $request) {
            $model = \Modules\Tag\Entities\Tag::firstOrCreate([
                'name' => $request->get('term')
            ]);
            return response($model);
        });

    });
});
