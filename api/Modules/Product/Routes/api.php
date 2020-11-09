<?php

use Illuminate\Http\Request;
use Modules\Product\Entities\Product;

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

    Route::prefix('products')->middleware('auth:api')->group(function () {

        Route::group(['prefix' => '/packageTypes'], function () {
            Route::get('/', 'PackageTypeController@index');
            Route::post('/', 'PackageTypeController@store');
            Route::get('/{id}', 'PackageTypeController@show');
            Route::put('/{id}', 'PackageTypeController@update');
        });


        Route::group(['prefix' => '/categories'], function () {
            Route::get('/', 'CategoryController@index');
            Route::post('/', 'CategoryController@store');
            Route::get('/{id}', 'CategoryController@show');
            Route::put('/{id}', 'CategoryController@update');
            Route::get('/{id}/findMapState', 'CategoryController@findMapState');
            Route::put('/{id}/dispatchMove', 'CategoryController@dispachMove');
            Route::get('/{id}/{title}', 'CategoryController@filters');
            Route::post('/{id}/{title}', 'CategoryController@storeFilters');
            Route::get('/{id}/{title}/selected', 'CategoryController@selectedFilters');
            Route::delete('/{id}/{title}/selected', 'CategoryController@removeFilters');
            Route::put('/{id}/movement', function ($id, Request $request) {

                switch ($request->get('type')) {
                    case 'up':
                        foreach (explode(',', $id) as $item) {
                            \Modules\Product\Entities\Category::find($item)->up();
                        }
                        break;
                    case 'down':
                        foreach (explode(',', $id) as $item) {
                            \Modules\Product\Entities\Category::find($item)->down();
                        }
                        break;
                }

                return response(['status' => true]);
            });
        });

        Route::group(['prefix' => '/priceParameters'], function () {
            Route::get('/', 'PriceParameterController@index');
            Route::post('/', 'PriceParameterController@store');
            Route::get('/{id}', 'PriceParameterController@show');
            Route::put('/{id}', 'PriceParameterController@update');
            Route::get('/{id}/findMapState', 'PriceParameterController@findMapState');
            Route::put('/{id}/dispatchMove', 'PriceParameterController@dispachMove');
        });


        Route::group(['prefix' => '/filters'], function () {
            Route::get('/', 'FilterController@index');
            Route::post('/', 'FilterController@store');
            Route::get('/{id}', 'FilterController@show');
            Route::put('/{id}', 'FilterController@update');
            Route::get('/{id}/findMapState', 'FilterController@findMapState');
            Route::put('/{id}/dispatchMove', 'FilterController@dispachMove');
        });


        Route::group(['prefix' => '/attributes'], function () {

            Route::get('/autocomplete', function (Request $request) {
                $response = [];
                if ($request->get('term')) {
                    $response = \Modules\Product\Entities\Attribute::select('id', 'title')
                        ->where('title', 'like', '%'.$request->get('term').'%')
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->take(10)
                        ->get();
                }
                return response($response);
            });

            Route::get('/', 'AttributeController@index');
            Route::post('/', 'AttributeController@store');
            Route::get('/{id}', 'AttributeController@show');
            Route::put('/{id}', 'AttributeController@update');
            Route::put('/{id}/status', 'AttributeController@status');

        });

        Route::group(['prefix' => '/brands'], function () {
            Route::get('/autocomplete', function (Request $request) {
                $response = [];
                if ($request->get('term')) {
                    $response = \Modules\Product\Entities\Brand::select('id', 'title')
                        ->where('title', 'like', '%'.$request->get('term').'%')
                        ->where('deleted', 0)
                        ->where('status', 1)
                        ->take(10)
                        ->get();
                }
                return response($response);
            });
            Route::get('/', 'BrandController@index');
            Route::post('/', 'BrandController@store');
            Route::get('/{id}', 'BrandController@show');
            Route::put('/{id}', 'BrandController@update');
            Route::put('/{id}/status', 'BrandController@status');

        });


        Route::group([ 'prefix' => 'itemProduct'], function () {
            Route::get('/', 'ItemProductController@index');
            Route::post('/', 'ItemProductController@store');
            Route::get('/{id}', 'ItemProductController@show');
            Route::put('/{id}', 'ItemProductController@update');
            Route::put('/{id}/status', 'ItemProductController@status');
        });


        Route::get('/autocomplete', function (Request $request) {
            $response = [];
            if ($request->get('term')) {
                $response = \Modules\Product\Entities\Product::select('id', 'title')
                    ->where('title', 'like', '%'.$request->get('term').'%')
                    ->where('status', 1)
                    ->take(10)
                    ->get();
            }
            return response($response);
        });


        Route::get('/{id}/categories', function ($id) {
            return response(\Modules\Product\Entities\Product::find($id)->categories()->get(['id','title']));
        });

        Route::post('/{id}/categories', function ($id, Request $request) {
            $result = \Modules\Product\Entities\Product::find($id);

            $result->categories()->detach();
            if ($request->has('categories')) {

                $categories_result = [];

                foreach ($request->get('categories') as $category) {

                    $ancestors = \Modules\Product\Entities\Category::ancestorsAndSelf($category);

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

        Route::get('/{id}/filters', function ($id) {
            return response(\Modules\Product\Entities\Product::find($id)->filters()->where('status', 1)->where('deleted', 0)->get(['id','title']));
        });

        Route::post('/{id}/filters', function ($id, Request $request) {
            $result = \Modules\Product\Entities\Product::find($id);

            $result->filters()->detach();
            if ($request->has('filters')) {

                $categories_result = [];

                foreach ($request->get('filters') as $category) {

                    $ancestors = \Modules\Product\Entities\Filter::ancestorsAndSelf($category);

                    foreach ($ancestors as $key=>$ancestor) {

                        $is_main = 0;

                        if (count($ancestors) == ($key + 1) && count($ancestors) > 1) {
                            $is_main = 1;
                        }

                        $categories_result[$ancestor->id] = [
                            'is_main' => $is_main,
                            'filter_id' => $ancestor->id
                        ];
                    }
                }

                $result->filters()->attach($categories_result);
            }

            if ($result) {
                return response(['status'=> true, 'msg' => 'عملیات موفقیت آمیز']);
            }
        });

        Route::get('/{id}/attributes', function ($id, Request $request) {
            $value = \Illuminate\Support\Facades\DB::select('call sp_product_fetch_attributes_with_values(?)', [$id]);
            return response($value);
        });

        Route::post('/{id}/attributes', function ($id, Request $request) {
            $model = \Modules\Product\Entities\Product::find($id);

            $model->attributes()->detach();

            foreach ($request->get('attributes') as $key => $item) {

                if ($item['attr_values']) {

                    foreach (json_decode($item['attr_selected'], true) as $value) {

                        try {
                            $model->attributes()->attach([
                                $item['id'] => [
                                    'tag_id' => $value['id'],
                                    'sort' => $item['sort'] ?? $key,
                                ]
                            ]);
                        } catch (Exception $exception) {
                            continue;
                        }
                    }

                } else {

                    $model->attributes()->attach([
                        $item['id'] => [
                            'value' => $item['text_value'],
                            'sort' => $item['sort'] ?? $key,
                        ]
                    ]);
                }
            }

            return response(['status' => true, 'msg' => 'عملیات موفقیت آمیز']);

        });

        Route::get('/{id}/seo', function ($id, Request $request) {

            $result = Product::with(['tags'])->find($id);

            if ($result) {

                return response([
                    'slug' =>  $result->slug ?? '',
                    'meta_title' =>  $result->meta_title ?? '',
                    'meta_description' =>  $result->meta_description ?? '',
                    'content' =>  $result->content ?? '',
                    'short_content' =>  $result->short_content ?? '',
                    'tags' => $result->tags
                ]);
            }

            return response()->json(['status' => false, 'msg' => 'request is invalid'], 200);
        });

        Route::put('/{id}/seo', function ($id, Request $request) {

            if ($request->get('slug') && $request->get('slug') != "") {
                $slug = Product::where('slug', remove_special_char($request->get('slug')))->where('id', '<>', $id)->count();
                if ($slug > 0) {
                    return Response()->json(['status' => false, 'msg' => 'اسلاگ قبلا ثبت شده است.']);
                }
            }

            $result = Product::updateOrCreate(['id' => $id] ,[
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

            if ($result) {
                return response()->json(['status' => true, 'msg' => 'عملیات موفقیت امیز بود.'], 200);
            }

            return response()->json(['status' => false, 'msg' => 'un success'], 200);
        });

        Route::get('/{id}/pins', 'ProductController@productPins');
        Route::put('/{id}/pins', 'ProductController@storePins');

        Route::post('/init', 'ProductController@initStore');
        Route::put('/{id}/init', 'ProductController@initUpdate');
        Route::get('/{id}/init', 'ProductController@initShow');

        Route::get('/', 'ProductController@index');
        Route::put('/{id}/status', 'ProductController@status');

    });
});
