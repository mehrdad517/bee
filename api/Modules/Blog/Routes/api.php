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

    Route::prefix('blog')->middleware('auth:api')->group(function () {

        Route::group(['prefix' => '/categories'], function () {
            Route::get('/', 'CategoryController@index');
            Route::post('/', 'CategoryController@store');
            Route::get('/{id}', 'CategoryController@show');
            Route::put('/{id}', 'CategoryController@update');
            Route::get('/{id}/findMapState', 'CategoryController@findMapState'); // locations that node has replace
            Route::put('/{id}/dispatchMove', 'CategoryController@dispachMove');
        });


        Route::group(['prefix' => '/menu'], function () {
            Route::get('/', 'MenuController@index');
            Route::post('/', 'MenuController@store');
            Route::get('/{id}', 'MenuController@show');
            Route::put('/{id}', 'MenuController@update');
            Route::get('/{id}/findMapState', 'MenuController@findMapState'); // locations that node has replace
            Route::put('/{id}/dispatchMove', 'MenuController@dispachMove');
        });



        Route::get('/{id}/categories', function ($id) {
            return response(\Modules\Blog\Entities\Content::find($id)->categories()->get(['id','title']));
        });
        Route::post('/{id}/categories', function ($id, Request $request) {
            $result =\Modules\Blog\Entities\Content::find($id);

            $result->categories()->detach();
            if ($request->has('categories')) {

                $categories_result = [];

                foreach ($request->get('categories') as $category) {

                    $ancestors = \Modules\Blog\Entities\Category::ancestorsAndSelf($category);

                    foreach ($ancestors as $key=>$ancestor) {

                        $is_main = 0;

                        if (count($ancestors) == $key + 1 && count($ancestors) > 1) {
                            $is_main = 1;
                        }

                        $categories_result[$ancestor->id] = [
                            'is_main' => $is_main,
                            'category_id' => $ancestor->id
                        ];
                    }
                }

                $result->categories()->attach($categories_result);
            }

            if ($result) {
                return response(['status'=> true, 'msg' => 'عملیات موفقیت آمیز']);
            }
        });


        Route::get('/{id}/seo', function ($id, Request $request) {

            $result = \Modules\Blog\Entities\Content::with(['tags', 'products' => function($q) {
                $q->select('id', 'title');
            }])->find($id);

            if ($result) {

                return response([
                    'slug' =>  $result->slug ?? '',
                    'meta_title' =>  $result->meta_title ?? '',
                    'meta_description' =>  $result->meta_description ?? '',
                    'content' =>  $result->content ?? '',
                    'short_content' =>  $result->short_content ?? '',
                    'tags' => $result->tags,
                    'products' => $result->products
                ]);
            }

            return response()->json(['status' => false, 'msg' => 'request is invalid'], 200);
        });

        Route::put('/{id}/seo', function ($id, Request $request) {

            if ($request->get('slug') && $request->get('slug') != "") {
                $slug = \Modules\Blog\Entities\Content::where('slug', remove_special_char($request->get('slug')))->where('id', '<>', $id)->count();
                if ($slug > 0) {
                    return Response()->json(['status' => false, 'msg' => 'اسلاگ قبلا ثبت شده است.']);
                }
            }

            $result = \Modules\Blog\Entities\Content::updateOrCreate(['id' => $id] ,[
                'content' => $request->get('content'),
                'short_content' => $request->get('short_content'),
                'slug' => $request->get('slug') == '' ? null : remove_special_char($request->get('slug')),
                'meta_title' => $request->get('meta_title'),
                'meta_description' => $request->get('meta_description'),
            ]);


            $result->tags()->detach();

            if ($request->has('tags')) {
                foreach ($request->get('tags') as $tag) {

                    if (!is_numeric($tag)) {
                        $try_check = \Modules\Tag\Entities\Tag::where('name', trim($tag))->first();
                        if ($try_check) {
                            $tag = $try_check->id;
                        } else {
                            $tag = \Modules\Tag\Entities\Tag::create(['name' => $tag])->id;
                        }
                    }


                    $result->tags()->attach($tag);
                }
            }

            if ($request->has('products')) {
                $result->products()->sync($request->get('products'));
            }


            if ($result) {
                return response()->json(['status' => true, 'msg' => 'عملیات موفقیت امیز بود.'], 200);
            }

            return response()->json(['status' => false, 'msg' => 'un success'], 200);
        });


        Route::post('/init', 'ContentController@initStore');
        Route::put('/{id}/init', 'ContentController@initUpdate');
        Route::get('/{id}/init', 'ContentController@initShow');


        Route::get('/', 'ContentController@index');
        Route::put('/{id}/status', 'ContentController@status');
    });
});
